<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak benar',
        'email.unique' => 'Email sudah terdaftar',
        'password.min' => 'Password minimal 6 karakter',
        'branch_id.exists' => 'Cabang tidak ditemukan',
    ];

    public function index(Request $request)
    {
        // treat both ajax requests and requests that want JSON
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $query = User::select(['id','name','email','branch_id','is_superadmin','is_active','created_at'])
                    ->with('branch')
                    ->with('roles'); // ambil roles agar ->roles tersedia saat rendering badges

                $searchValue = $request->input('search.value');
                if (!empty($searchValue)) {
                    $driver = \DB::getDriverName();
                    if ($driver === 'pgsql') {
                        // Postgres supports ILIKE
                        $query->where(function($q) use ($searchValue) {
                            $q->where('name', 'ilike', "%{$searchValue}%")
                                ->orWhere('email', 'ilike', "%{$searchValue}%")
                                ->orWhere('phone', 'ilike', "%{$searchValue}%");
                        });
                    } else {
                        // MySQL / others: use LIKE (case-insensitive depends on collation)
                        $like = "%{$searchValue}%";
                        $query->where(function($q) use ($like) {
                            $q->where('name', 'LIKE', $like)
                                ->orWhere('email', 'LIKE', $like)
                                ->orWhere('phone', 'LIKE', $like);
                        });
                    }
                }

                return datatables()->eloquent($query)
                    ->addIndexColumn()
                    ->addColumn('branch', function(User $u) {
                        return $u->branch ? e($u->branch->name) : '-';
                    })
                    ->addColumn('roles', function(User $u) {
                        $names = $u->roles->pluck('name')->map(function($r){ return e($r); })->toArray();
                        if (empty($names)) return '-';
                        return collect($names)->map(fn($n) => "<span class=\"badge bg-info me-1 mb-1\">{$n}</span>")->implode(' ');
                    })
                    ->addColumn('is_superadmin', function(User $u) {
                        return $u->is_superadmin ? '<span class="badge bg-warning">Superadmin</span>' : '-';
                    })
                    ->addColumn('is_active', function(User $u) {
                        return $u->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Non-aktif</span>';
                    })
                    ->addColumn('created_at', function($u) {
                        return $u->created_at ? $u->created_at->format('d M Y H:i') : '-';
                    })
                    ->addColumn('action', function($u) {
                        return view('users._column_action', ['u' => $u])->render();
                    })
                    ->rawColumns(['roles','is_superadmin','is_active','action'])
                    ->toJson();
            } catch (\Throwable $e) {
                \Log::error('UserController@index error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
                return response()->json(['error' => 'Server error, cek logs.'], 500);
            }
        }

        return view('users.index');
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        return view('users.create', [
            'user' => new User(),
            'branches' => $branches,
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'is_superadmin' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
//            'meta' => 'nullable|array',
        ], self::VALIDATION_MESSAGES);

        try {
            $userData = $validated;
            $userData['password'] = Hash::make($validated['password']);
            if (isset($userData['meta']) && is_array($userData['meta'])) {
                $userData['meta'] = $userData['meta'];
            }

            $user = User::create($userData);
            if (!empty($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            }

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        $branches = Branch::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user','branches','roles'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'is_superadmin' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
//            'meta' => 'nullable|array',
        ], self::VALIDATION_MESSAGES);

        try {
            // only update password if provided
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            // sync roles
            if (array_key_exists('roles', $validated)) {
                $user->syncRoles($validated['roles'] ?? []);
            }

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        try {
            // prevent deleting self (optional)
            if (auth()->check() && auth()->id() === $user->id) {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
            }

            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(int $id)
    {
        $user = User::with('branch','roles')->findOrFail($id);
        return view('users.show', compact('user'));
    }
}
