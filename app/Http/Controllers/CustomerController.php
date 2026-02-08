<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CustomerController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'code.required' => 'Kode customer wajib diisi',
        'code.unique'   => 'Kode customer sudah digunakan',
        'name.required' => 'Nama customer wajib diisi',
    ];

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            try {
                $query = Customer::query()
                    ->select(['id', 'code', 'name', 'phone', 'is_active', 'created_at']);

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

                    ->addColumn('is_active', function (Customer $c) {
                        return $c->is_active
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-secondary">Non-aktif</span>';
                    })

                    ->addColumn('action', function (Customer $c) {
                        // ⬇️ INI PENTING
                        return view('customers._column_action', ['c' => $c])->render();
                    })

                    ->addColumn('created_at', function (Customer $c) {
                        return $c->created_at
                            ? $c->created_at->format('Y-m-d H:i:s')
                            : '-';
                    })

                    // ⬇️ WAJIB ADA
                    ->rawColumns(['is_active', 'action'])
                    ->toJson();
            } catch (\Throwable $e) {
                \Log::error('Customer@index', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Server error'], 500);
            }
        }

        return view('customers.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('customers.create', [
            'customer' => new Customer(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'      => 'required|max:50|unique:customers,code',
            'name'      => 'required|max:150',
            'phone'     => 'nullable|max:30',
            'address'   => 'nullable',
            'is_active' => 'sometimes|boolean',
        ], self::VALIDATION_MESSAGES);

        Customer::create($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    /* =========================
     * SHOW
     * ========================= */
    public function show(int $id)
    {
        $customer = Customer::with([
            'receivables.sale',
        ])->findOrFail($id);

        $today = Carbon::today();

        $aging = [
            '0_30'  => 0,
            '31_60' => 0,
            '60_up' => 0,
        ];

        foreach ($customer->receivables as $receivable) {
            if ($receivable->balance <= 0) {
                continue;
            }

            $saleDate = $receivable->sale->date;
            $days = $saleDate->diffInDays($today);

            if ($days <= 30) {
                $aging['0_30'] += $receivable->balance;
            } elseif ($days <= 60) {
                $aging['31_60'] += $receivable->balance;
            } else {
                $aging['60_up'] += $receivable->balance;
            }
        }

        $totalPiutang = array_sum($aging);

        return view('customers.show', [
            'customer'      => $customer,
            'totalPiutang'  => $totalPiutang,
            'aging'         => $aging,
        ]);
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        return view('customers.edit', [
            'customer' => Customer::findOrFail($id),
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'code' => [
                'required',
                'max:50',
                Rule::unique('customers', 'code')->ignore($customer->id),
            ],
            'name'      => 'required|max:150',
            'phone'     => 'nullable|max:30',
            'address'   => 'nullable',
            'is_active' => 'sometimes|boolean',
        ], self::VALIDATION_MESSAGES);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui');
    }

    /* =========================
     * DESTROY (soft delete)
     * ========================= */
    public function destroy(int $id)
    {
        Customer::findOrFail($id)->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil dihapus');
    }

    /* =========================
     * TOGGLE ACTIVE (AJAX)
     * ========================= */
    public function toggleActive(int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(['is_active' => ! $customer->is_active]);

        return response()->json([
            'success' => true,
            'status'  => $customer->is_active
        ]);
    }
}
