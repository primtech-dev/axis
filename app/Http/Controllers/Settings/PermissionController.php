<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama permission (slug) tidak boleh kosong',
        'name.unique' => 'Nama permission sudah ada',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::select(['id','name','display_name','group','created_at']);

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'ilike', "%{$searchValue}%")
                        ->orWhere('display_name', 'ilike', "%{$searchValue}%")
                        ->orWhere('group', 'ilike', "%{$searchValue}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('created_at', function ($p) {
                    return $p->created_at ? $p->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function ($p) {
                    return view('settings.permissions._column_action', ['p' => $p])->render();
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('settings.permissions.index');
    }

    public function create()
    {
        return view('settings.permissions.create', ['permission' => new Permission()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
        ], self::VALIDATION_MESSAGES);

        try {
            Permission::create(array_merge($validated, ['guard_name' => 'web']));
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil dibuat');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $permission = Permission::findOrFail($id);
        return view('settings.permissions.edit', compact('permission'));
    }

    public function update(Request $request, int $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('permissions','name')->ignore($permission->id)],
            'display_name' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
        ], self::VALIDATION_MESSAGES);

        try {
            $permission->update($validated);
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $permission = Permission::findOrFail($id);
        try {
            $permission->delete();
            return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(int $id)
    {
        $permission = Permission::findOrFail($id);
        return view('settings.permissions.show', compact('permission'));
    }
}
