<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class SupplierController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'code.required' => 'Kode supplier wajib diisi',
        'code.unique'   => 'Kode supplier sudah digunakan',
        'name.required' => 'Nama supplier wajib diisi',
    ];

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $query = Supplier::query()
                ->select(['id', 'code', 'name', 'phone', 'type', 'is_active', 'created_at']);

            $search = $request->input('search.value');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('type', function (Supplier $s) {
                    return match ($s->type) {
                        Supplier::TYPE_PURCHASE => '<span class="badge bg-primary">Pembelian</span>',
                        Supplier::TYPE_EXPENSE  => '<span class="badge bg-warning text-dark">Biaya</span>',
                        default => '-',
                    };
                })
                ->addColumn('is_active', function (Supplier $s) {
                    return $s->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Non-aktif</span>';
                })

                ->addColumn('created_at', function (Supplier $s) {
                    return $s->created_at
                        ? $s->created_at->format('Y-m-d H:i:s')
                        : '-';
                })

                ->addColumn('action', function (Supplier $s) {
                    return view('suppliers._column_action', ['s' => $s])->render();
                })

                ->rawColumns(['is_active', 'type', 'action'])
                ->toJson();
        }

        return view('suppliers.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('suppliers.form', [
            'supplier' => new Supplier(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'      => 'required|max:50|unique:suppliers,code',
            'name'      => 'required|max:150',
            'phone'     => 'nullable|max:30',
            'address'   => 'nullable',
            'type'      => ['required', Rule::in(['PURCHASE', 'EXPENSE'])],
            'is_active' => 'sometimes|boolean',
        ], self::VALIDATION_MESSAGES);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        return view('suppliers.form', [
            'supplier' => Supplier::findOrFail($id),
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'code' => [
                'required',
                'max:50',
                Rule::unique('suppliers', 'code')->ignore($supplier->id),
            ],
            'name'      => 'required|max:150',
            'phone'     => 'nullable|max:30',
            'address'   => 'nullable',
            'type'      => ['required', Rule::in(['PURCHASE', 'EXPENSE'])],
            'is_active' => 'sometimes|boolean',
        ], self::VALIDATION_MESSAGES);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    /* =========================
     * DESTROY (SOFT DELETE)
     * ========================= */
    public function destroy(int $id)
    {
        Supplier::findOrFail($id)->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    }

    /* =========================
     * TOGGLE ACTIVE (AJAX)
     * ========================= */
    public function toggleActive(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            'is_active' => ! $supplier->is_active
        ]);

        return response()->json([
            'success' => true,
            'status'  => $supplier->is_active,
            'message' => $supplier->is_active
                ? 'Supplier berhasil diaktifkan'
                : 'Supplier berhasil dinonaktifkan'
        ]);
    }

    public function show(int $id)
    {
        $supplier = Supplier::with([
            'payables.purchase',
        ])->findOrFail($id);

        $today = Carbon::today();

        $aging = [
            '0_30'  => 0,
            '31_60' => 0,
            '60_up' => 0,
        ];

        foreach ($supplier->payables as $payable) {
            if ($payable->balance <= 0) {
                continue;
            }

            $days = $payable->purchase->date->diffInDays($today);

            if ($days <= 30) {
                $aging['0_30'] += $payable->balance;
            } elseif ($days <= 60) {
                $aging['31_60'] += $payable->balance;
            } else {
                $aging['60_up'] += $payable->balance;
            }
        }

        $totalHutang = array_sum($aging);

        return view('suppliers.show', [
            'supplier'    => $supplier,
            'totalHutang' => $totalHutang,
            'aging'       => $aging,
        ]);
    }
}
