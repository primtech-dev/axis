<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseItemApiController extends Controller
{
    public function items(Purchase $purchase)
    {
        // Safety check
        if ($purchase->is_used) {
            return response()->json([]);
        }

        $items = $purchase->items()
            ->with([
                'product:id,name,unit_id',
                'product.unit:id,name'
            ])
            ->get([
                'id',
                'product_id',
                'qty',
            ]);

        return response()->json($items);
    }
}
