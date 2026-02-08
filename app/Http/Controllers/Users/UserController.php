<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required'     => 'Nama harus diisi',
        'email.required'    => 'Email harus diisi',
        'email.email'       => 'Format email tidak benar',
        'email.unique'      => 'Email sudah terdaftar',
        'password.min'      => 'Password minimal 6 karakter',
        'roles.array'       => 'Role tidak valid',
        'roles.*.exists'    => 'Role tidak ditemukan',
    ];

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $query = User::query()
                    ->select(['id','name','email','is_active','created_at'])
                    ->with('roles');

                $searchValue = $request->input('search.value');
                if (!empty($searchValue)) {
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%{$searchValue}%")
                            ->orWhere('email', 'LIKE', "%{$searchValue}%");
                    });
                }

                return datatables()->eloquent($query)
                    ->addIndexColumn()

                    ->addColumn('roles', function (User $u) {
                        if ($u->roles->isEmpty()) {
                            return '-';
                        }

                        return $u->roles->pluck('name')
                            ->map(fn ($r) => "<span class='badge bg-info me-1 mb-1'>{$r}</span>")
                            ->implode(' ');
                    })

                    ->addColumn('is_superadmin', function (User $u) {
                        return $u->hasRole('superadmin')
                            ? '<span class="badge bg-warning">Superadmin</span>'
                            : '-';
                    })

                    ->addColumn('is_active', function (User $u) {
                        return $u->is_active
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-secondary">Non-aktif</span>';
                    })

                    ->addColumn('created_at', function (User $u) {
                        return $u->created_at
                            ? $u->created_at->format('Y-m-d H:i:s')
                            : '-';
                    })

                    ->addColumn('action', function (User $u) {
                        return view('users._column_action', ['u' => $u])->render();
                    })

                    ->rawColumns(['roles','is_active','action'])
                    ->toJson();

            } catch (\Throwable $e) {
                \Log::error('UserController@index error', [
                    'message' => $e->getMessage(),
                    'trace'   => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'Server error, cek logs.'
                ], 500);
            }
        }

        return view('users.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('users.create', [
            'user'  => new User(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|max:150|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'is_active' => 'sometimes|boolean',
            'roles'     => 'nullable|array',
            'roles.*'   => 'string|exists:roles,name',
        ], self::VALIDATION_MESSAGES);

        try {
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'is_active' => $validated['is_active'] ?? 1,
            ]);

            if (!empty($validated['roles'])) {
                $user->syncRoles($validated['roles']);
            }

            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil ditambahkan');

        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /* =========================
     * SHOW
     * ========================= */
    public function show(int $id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        return view('users.edit', [
            'user'  => User::findOrFail($id),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'email'     => [
                'required',
                'email',
                'max:150',
                Rule::unique('users','email')->ignore($user->id)
            ],
            'password'  => 'nullable|string|min:6|confirmed',
            'is_active' => 'sometimes|boolean',
            'roles'     => 'nullable|array',
            'roles.*'   => 'string|exists:roles,name',
        ], self::VALIDATION_MESSAGES);

        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            if (array_key_exists('roles', $validated)) {
                $user->syncRoles($validated['roles'] ?? []);
            }

            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil diperbarui');

        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /* =========================
     * DESTROY
     * ========================= */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        try {
            if (auth()->check() && auth()->id() === $user->id) {
                return redirect()
                    ->back()
                    ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
            }

            $user->delete();

            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil dihapus');

        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
