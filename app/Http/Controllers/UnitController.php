<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama unit tidak boleh kosong',
    ];

    /* =========================
     * INDEX
     * ========================= */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $query = Unit::query()
                ->select(['id', 'name', 'created_at']);

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()

                ->addColumn('created_at', function (Unit $u) {
                    return $u->created_at
                        ? $u->created_at->format('Y-m-d H:i:s')
                        : '-';
                })

                ->addColumn('action', function (Unit $u) {
                    return view('units._column_action', ['u' => $u])->render();
                })

                ->rawColumns(['action'])
                ->toJson();
        }

        return view('units.index');
    }

    /* =========================
     * CREATE
     * ========================= */
    public function create()
    {
        return view('units.create', [
            'unit' => new Unit(),
        ]);
    }

    /* =========================
     * STORE
     * ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ], self::VALIDATION_MESSAGES);

        Unit::create($validated);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit berhasil ditambahkan');
    }

    /* =========================
     * EDIT
     * ========================= */
    public function edit(int $id)
    {
        return view('units.edit', [
            'unit' => Unit::findOrFail($id),
        ]);
    }

    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, int $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ], self::VALIDATION_MESSAGES);

        $unit->update($validated);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit berhasil diperbarui');
    }

    /* =========================
     * DESTROY (SOFT DELETE)
     * ========================= */
    public function destroy(int $id)
    {
        Unit::findOrFail($id)->delete();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit berhasil dihapus');
    }
}
