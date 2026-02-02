<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'code.required' => 'Kode cabang tidak boleh kosong',
        'code.unique'   => 'Kode cabang sudah digunakan',
        'name.required' => 'Nama cabang tidak boleh kosong',
        'phone.phone'   => 'Format nomor telepon tidak sesuai',
    ];

    public function index(Request $request)
    {
        // jika request AJAX (DataTables)
        if ($request->ajax()) {
            // gunakan query builder / eloquent (jangan ->get())
            $query = Branch::select(['id','code','name','city','province','phone','is_active','created_at']);

            // Optional: global search handling (DataTables server-side sends search.value)
            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'ilike', "%{$searchValue}%")
                        ->orWhere('code', 'ilike', "%{$searchValue}%")
                        ->orWhere('city', 'ilike', "%{$searchValue}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('is_active', function ($b) {
                    return $b->is_active ? 'Aktif' : 'Non-aktif';
                })
                ->addColumn('created_at', function ($b) {
                    return $b->created_at ? $b->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function ($b) {
                    return view('branches._column_action', ['b' => $b])->render();
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        // non-AJAX: render view
        return view('branches.index');
    }

    public function create()
    {
        return view('branches.create', ['branch' => new Branch()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:branches,code',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'manager_admin_user_id' => 'nullable|integer|exists:admin_users,id',
            'is_active' => 'sometimes|boolean',
        ], self::VALIDATION_MESSAGES);

        try {
            Branch::create($validated);
            return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $branch = Branch::find($id);
        if (!$branch) {
            return abort(404);
        }
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, int $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required','string','max:255', Rule::unique('branches','code')->ignore($branch->id)],
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'manager_admin_user_id' => 'nullable|integer|exists:admin_users,id',
            'is_active' => 'sometimes|boolean',
        ], self::VALIDATION_MESSAGES);

        try {
            $branch->update($validated);
            return redirect()->route('branches.index')->with('success', 'Cabang berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $branch = Branch::findOrFail($id);
        try {
            $branch->delete(); // soft delete
            return redirect()->route('branches.index')->with('success', 'Cabang berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(int $id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.show', compact('branch'));
    }
}
