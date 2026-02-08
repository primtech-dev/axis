<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $query = PaymentMethod::query()
                ->select([
                    'id',
                    'code',
                    'name',
                    'category',
                    'is_active',
                    'sort_order',
                    'created_at',
                ]);

            $search = $request->input('search.value');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()

                ->addColumn('is_active', function ($m) {
                    return $m->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Non-aktif</span>';
                })

                ->addColumn('created_at', function ($m) {
                    return $m->created_at
                        ? $m->created_at->format('Y-m-d H:i:s')
                        : '-';
                })

                ->addColumn('action', function ($m) {
                    return view('payment_methods._column_action', ['m' => $m])->render();
                })

                ->rawColumns(['is_active', 'action'])
                ->toJson();
        }

        return view('payment_methods.index');
    }

    /* =========================
     * TOGGLE ACTIVE
     * ========================= */
    public function toggleActive(int $id)
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update(['is_active' => ! $method->is_active]);

        return response()->json([
            'success' => true,
            'message' => $method->is_active
                ? 'Payment method diaktifkan'
                : 'Payment method dinonaktifkan',
        ]);
    }

    public function create()
    {
        return view('payment_methods.form', [
            'method' => new PaymentMethod(),
            'mode'   => 'create',
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'       => 'required|string|max:30|unique:payment_methods,code',
            'name'       => 'required|string|max:50',
            'category'   => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    /* =========================
     * SHOW
     * ========================= */
    public function show(int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        return view('payment_methods.show', compact('method'));
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        return view('payment_methods.form', [
            'method' => $method,
            'mode'   => 'edit',
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, int $id)
    {
        $method = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'code'       => ['required','string','max:30', Rule::unique('payment_methods','code')->ignore($method->id)],
            'name'       => 'required|string|max:50',
            'category'   => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        $method->update($validated);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui');
    }

    /* =========================
     * DESTROY (SOFT)
     * ========================= */
    public function destroy(int $id)
    {
        PaymentMethod::findOrFail($id)->delete();

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil dihapus');
    }
}
