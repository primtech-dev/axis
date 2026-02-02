<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama role tidak boleh kosong',
        'name.unique' => 'Nama role sudah digunakan',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Role::select(['id','name','created_at'])
                ->withCount('permissions');

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where('name', 'ilike', "%{$searchValue}%");
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('permissions', function (Role $r) {
                    // show up to 5 permission badges; controller expects partial view for action
                    $perms = $r->permissions()->limit(6)->get()->pluck('display_name', 'name');
                    if ($perms->isEmpty()) return '-';
                    $badges = $perms->map(function($display, $name) {
                        $label = $display ?: $name;
                        return "<span class=\"badge bg-secondary me-1 mb-1\">".e($label)."</span>";
                    })->implode(' ');
                    if ($r->permissions_count > 6) {
                        $badges .= " <small class=\"text-muted\">+".($r->permissions_count - 6)."</small>";
                    }
                    return $badges;
                })
                ->addColumn('created_at', function ($r) {
                    return $r->created_at ? $r->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function ($r) {
                    // expected partial: resources/views/settings/roles/_column_action.blade.php
                    return view('settings.roles._column_action', ['r' => $r])->render();
                })
                ->rawColumns(['permissions','action'])
                ->toJson();
        }

        return view('settings.roles.index');
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->get();
        $permissionsGrouped = $permissions->groupBy(function($p) {
            return $p->group ?: 'Umum';
        });

        return view('settings.roles.create', [
            'role' => new Role(),
            'permissionsGrouped' => $permissionsGrouped
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], self::VALIDATION_MESSAGES);

        try {
            $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
            if (!empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }
            return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $role = Role::find($id);
        if (!$role) return abort(404);

        $permissions = Permission::orderBy('group')->get();
        $permissionsGrouped = $permissions->groupBy(function($p) {
            return $p->group ?: 'Umum';
        });

        return view('settings.roles.edit', compact('role', 'permissionsGrouped'));
    }

    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('roles','name')->ignore($role->id)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], self::VALIDATION_MESSAGES);

        try {
            $role->update(['name' => $validated['name']]);
            $role->syncPermissions($validated['permissions'] ?? []);
            return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);
        try {
            // optional: prevent deleting super-admin role by name
            if (strtolower($role->name) === 'super-admin') {
                return redirect()->back()->with('error', 'Role ini tidak boleh dihapus.');
            }
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(int $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('settings.roles.show', compact('role'));
    }
}
