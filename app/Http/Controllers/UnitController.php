<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    private const VALIDATION_MESSAGES = [
        'name.required' => 'Nama unit tidak boleh kosong',
        'code.unique'   => 'Kode unit sudah digunakan',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Unit::select(['id','code','name','created_at'])
                ->orderBy('created_at', 'desc');

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('code', 'ilike', "%{$searchValue}%")
                        ->orWhere('name', 'ilike', "%{$searchValue}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('created_at', function ($u) {
                    return $u->created_at ? $u->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function (Unit $u) {
                    return view('units._column_action', ['u' => $u])->render();
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('units.index');
    }

    public function create()
    {
        return view('units.create', ['unit' => new Unit()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:100|unique:units,code',
            'name' => 'required|string|max:255',
        ], self::VALIDATION_MESSAGES);

        try {
            Unit::create($validated);
            return redirect()->route('units.index')->with('success', 'Unit berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $unit = Unit::find($id);
        if (!$unit) return abort(404);
        return view('units.create', compact('unit')); // reuse create view for edit
    }

    public function update(Request $request, int $id)
    {
        $unit = Unit::findOrFail($id);

        $validated = $request->validate([
            'code' => ['nullable','string','max:100', Rule::unique('units','code')->ignore($unit->id)],
            'name' => 'required|string|max:255',
        ], self::VALIDATION_MESSAGES);

        try {
            $unit->update($validated);
            return redirect()->route('units.index')->with('success', 'Unit berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy(int $id)
    {
        $unit = Unit::findOrFail($id);
        try {
            $unit->delete();
            return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(int $id)
    {
        $unit = Unit::findOrFail($id);
        return view('units.show', compact('unit'));
    }
}
