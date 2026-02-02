<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InventoryService
{
    /**
     * Unreserve stock for an order (called when order cancelled)
     * - expect $order->items with variant_id and line_quantity
     * - create stock_movements reason = 'order_unreserved'
     */
    public function unreserveOrder(Order $order, int $performedBy = null): bool
    {
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                if (!$item->variant_id) continue;

                // find inventory record for variant + branch
                $inv = Inventory::where('variant_id', $item->variant_id)
                    ->where('branch_id', $order->branch_id)
                    ->lockForUpdate()
                    ->first();

                $change = (int) $item->line_quantity * -1 * 0; // default 0 if logic changes

                // decrease reserved by qty, increase available by qty
                if ($inv) {
                    $inv->reserved = max(0, $inv->reserved - $item->line_quantity);
                    $inv->available = max(0, $inv->on_hand - $inv->reserved);
                    $inv->save();

                    StockMovement::create([
                        'variant_id' => $item->variant_id,
                        'branch_id' => $order->branch_id,
                        'change' => $item->line_quantity * 1, // unreserve returns to available (no on_hand change)
                        'resulting_on_hand' => $inv->on_hand,
                        'reason' => 'order_unreserved',
                        'reference_type' => 'order',
                        'reference_id' => $order->public_id,
                        'performed_by' => $performedBy,
                        'metadata' => [
                            'order_id' => $order->id,
                            'order_item_id' => $item->id,
                        ],
                    ]);
                } else {
                    // inventory record not found -> log and skip
                    Log::warning("Inventory record not found for variant {$item->variant_id} branch {$order->branch_id} while unreserving order {$order->id}");
                }
            }

            DB::commit();
            return true;
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Failed to unreserve order stock: '.$th->getMessage(), ['order_id' => $order->id]);
            return false;
        }
    }

    /**
     * Confirm shipment: reduce on_hand and reserved -> reason sale_shipment
     * - this should be called when order moves to 'shipped' (or confirmed)
     */
    public function confirmShipment(Order $order, int $performedBy = null): bool
    {
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                if (!$item->variant_id) continue;

                $inv = Inventory::where('variant_id', $item->variant_id)
                    ->where('branch_id', $order->branch_id)
                    ->lockForUpdate()
                    ->first();

                if ($inv) {
                    // decrement on_hand and reserved accordingly
                    $qty = (int) $item->line_quantity;
                    $inv->on_hand = max(0, $inv->on_hand - $qty);
                    $inv->reserved = max(0, $inv->reserved - $qty);
                    $inv->available = max(0, $inv->on_hand - $inv->reserved);
                    $inv->save();

                    StockMovement::create([
                        'variant_id' => $item->variant_id,
                        'branch_id' => $order->branch_id,
                        'change' => -1 * $qty,
                        'resulting_on_hand' => $inv->on_hand,
                        'reason' => 'sale_shipment',
                        'reference_type' => 'order',
                        'reference_id' => $order->public_id,
                        'performed_by' => $performedBy,
                        'metadata' => [
                            'order_id' => $order->id,
                            'order_item_id' => $item->id,
                        ],
                    ]);
                } else {
                    Log::warning("Inventory record not found for variant {$item->variant_id} branch {$order->branch_id} while confirming shipment for order {$order->id}");
                }
            }

            DB::commit();
            return true;
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Failed to confirm shipment stock: '.$th->getMessage(), ['order_id' => $order->id]);
            return false;
        }
    }
}
