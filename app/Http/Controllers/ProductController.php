<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $query = Product::query()
                ->select([
                    'products.id',
                    'products.sku',
                    'products.name',
                    'products.price_buy_default',
                    'products.price_sell_default',
                    'products.is_active',
                    'products.created_at',
                    'categories.name as category_name',
                    'units.name as unit_name',
                ])
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->join('units', 'units.id', '=', 'products.unit_id');

            $search = $request->input('search.value');
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('products.sku', 'like', "%{$search}%")
                        ->orWhere('products.name', 'like', "%{$search}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()

                ->addColumn('price_buy_default', fn ($p) =>
                number_format($p->price_buy_default, 2)
                )

                ->addColumn('price_sell_default', fn ($p) =>
                number_format($p->price_sell_default, 2)
                )

                ->addColumn('is_active', function ($p) {
                    return $p->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-secondary">Non-aktif</span>';
                })

                ->addColumn('created_at', function ($p) {
                    return $p->created_at
                        ? $p->created_at->format('Y-m-d H:i:s')
                        : '-';
                })

                ->addColumn('action', function ($p) {
                    return view('products._column_action', ['p' => $p])->render();
                })

                ->rawColumns(['is_active', 'action'])
                ->toJson();
        }

        return view('products.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('products.form', [
            'product'    => new Product(),
            'categories' => Category::orderBy('name')->get(),
            'units'      => Unit::orderBy('name')->get(),
        ]);
    }

    /* =========================
     * STORE  ✅ (INI YANG HILANG)
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => [
                'required',
                'string',
                'max:100',
                'unique:products,sku',
            ],
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            'price_buy_default'  => 'nullable|numeric|min:0',
            'price_sell_default' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        Product::create([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'unit_id' => $validated['unit_id'],
            'price_buy_default' => $validated['price_buy_default'] ?? 0,
            'price_sell_default' => $validated['price_sell_default'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        return view('products.form', [
            'product'    => Product::findOrFail($id),
            'categories' => Category::orderBy('name')->get(),
            'units'      => Unit::orderBy('name')->get(),
        ]);
    }

    /* =========================
     * UPDATE  ✅
     * ========================= */
    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'unit_id'     => 'required|exists:units,id',
            'price_buy_default'  => 'nullable|numeric|min:0',
            'price_sell_default' => 'nullable|numeric|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $product->update([
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'unit_id' => $validated['unit_id'],
            'price_buy_default' => $validated['price_buy_default'] ?? 0,
            'price_sell_default' => $validated['price_sell_default'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /* =========================
     * DESTROY (SOFT DELETE) ✅
     * ========================= */
    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    /* =========================
     * TOGGLE ACTIVE
     * ========================= */
    public function toggleActive(int $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => ! $product->is_active]);

        return response()->json([
            'success' => true,
            'message' => $product->is_active
                ? 'Produk berhasil diaktifkan'
                : 'Produk berhasil dinonaktifkan',
        ]);
    }

    public function show(int $id)
    {
        $product = Product::with(['category', 'unit'])
            ->findOrFail($id);

        return view('products.show', compact('product'));
    }
}
