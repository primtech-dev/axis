<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama kategori tidak boleh kosong',
    ];

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $query = Category::query()
                ->select(['id', 'name', 'created_at']);

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()

                ->addColumn('created_at', function (Category $c) {
                    return $c->created_at
                        ? $c->created_at->format('Y-m-d H:i:s')
                        : '-';
                })

                ->addColumn('action', function (Category $c) {
                    return view('categories._column_action', ['c' => $c])->render();
                })

                ->rawColumns(['action'])
                ->toJson();
        }

        return view('categories.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('categories.create', [
            'category' => new Category(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ], self::VALIDATION_MESSAGES);

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        return view('categories.edit', [
            'category' => Category::findOrFail($id),
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ], self::VALIDATION_MESSAGES);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /* =========================
     * DESTROY (SOFT DELETE)
     * ========================= */
    public function destroy(int $id)
    {
        Category::findOrFail($id)->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
