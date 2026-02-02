<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Customer::select(['id','public_id','email','full_name','phone','is_active','created_at']);

            $searchValue = $request->input('search.value');
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('full_name', 'ilike', "%{$searchValue}%")
                        ->orWhere('email', 'ilike', "%{$searchValue}%")
                        ->orWhere('phone', 'ilike', "%{$searchValue}%");
                });
            }

            return datatables()->eloquent($query)
                ->addIndexColumn()
                ->addColumn('is_active', function (Customer $c) {
                    return $c->is_active ? 'Aktif' : 'Non-aktif';
                })
                ->addColumn('created_at', function ($c) {
                    return $c->created_at ? $c->created_at->format('d M Y H:i') : '-';
                })
                ->addColumn('action', function ($c) {
                    return view('customers._column_action', ['c' => $c])->render();
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('customers.index');
    }

    public function show(int $id)
    {
        $customer = Customer::with(['addresses' => function($q){
            $q->orderBy('is_default', 'desc')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('customers.show', compact('customer'));
    }

    public function toggleActive(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        DB::beginTransaction();
        try {
            $customer->is_active = !$customer->is_active;
            $customer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'is_active' => $customer->is_active,
                'message' => $customer->is_active ? 'Customer diaktifkan' : 'Customer dinonaktifkan'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $th->getMessage()
            ], 500);
        }
    }
}
