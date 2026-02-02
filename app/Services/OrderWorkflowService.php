<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class OrderWorkflowService
{
    public function confirmOrder(Order $order, int $performedBy = null, ?string $note = null)
    {
        if ($order->status !== 'pending' && $order->status !== 'paid') {
            throw new \Exception("Order tidak bisa dikonfirmasi dari status {$order->status}");
        }

        $order->status = 'processing';
        $order->payment_status = $order->payment_status ?? 'paid';
        $order->notes = $this->appendNote($order->notes, 'Konfirmasi oleh admin', $note);
        $order->save();

        StockMovement::create([
            'variant_id' => null,
            'branch_id' => $order->branch_id,
            'change' => 0,
            'reason' => 'order_confirmed',
            'reference_type' => 'order',
            'reference_id' => $order->public_id,
            'performed_by' => $performedBy,
            'metadata' => ['order_id' => $order->id]
        ]);
    }

    public function shipOrder(Order $order, int $performedBy = null, array $payload = [])
    {
        if (!in_array($order->status, ['processing','paid'])) {
            throw new \Exception("Order tidak bisa di-ship dari status {$order->status}");
        }

        foreach ($order->items as $item) {
            $inv = Inventory::where('variant_id', $item->variant_id)
                ->where('branch_id', $order->branch_id)
                ->lockForUpdate()
                ->first();

            if (!$inv) throw new \Exception("Inventory untuk variant {$item->variant_id} di branch {$order->branch_id} tidak ditemukan");

            $qty = (int)$item->line_quantity;
            $inv->on_hand = max(0, $inv->on_hand - $qty);
            $inv->reserved = max(0, $inv->reserved - $qty);
            $inv->available = $inv->on_hand - $inv->reserved;
            $inv->save();

            StockMovement::create([
                'variant_id' => $item->variant_id,
                'branch_id' => $order->branch_id,
                'change' => -$qty,
                'resulting_on_hand' => $inv->on_hand,
                'reason' => 'sale_shipment',
                'reference_type' => 'order',
                'reference_id' => $order->public_id,
                'performed_by' => $performedBy,
                'metadata' => ['order_item_id' => $item->id]
            ]);
        }

        $order->status = 'shipped';
        $order->notes = $this->appendNote($order->notes, 'Dikirim', $payload['shipping_note'] ?? null);
        $order->save();
    }

    public function cancelOrder(Order $order, int $performedBy = null, ?string $note = null)
    {
        if (in_array($order->status, ['cancelled','complete'])) {
            throw new \Exception("Order sudah dibatalkan atau selesai");
        }

        foreach ($order->items as $item) {
            $inv = Inventory::where('variant_id', $item->variant_id)
                ->where('branch_id', $order->branch_id)
                ->lockForUpdate()
                ->first();
            if (!$inv) continue;
            $qty = (int)$item->line_quantity;
            $inv->reserved = max(0, $inv->reserved - $qty);
            $inv->available = $inv->on_hand - $inv->reserved;
            $inv->save();

            StockMovement::create([
                'variant_id' => $item->variant_id,
                'branch_id' => $order->branch_id,
                'change' => 0,
                'resulting_on_hand' => $inv->on_hand,
                'reason' => 'order_unreserved',
                'reference_type' => 'order',
                'reference_id' => $order->public_id,
                'performed_by' => $performedBy,
                'metadata' => ['order_item_id' => $item->id]
            ]);
        }

        $order->status = 'cancelled';
        $order->cancelled_at = now();
        $order->notes = $this->appendNote($order->notes, 'Dibatalkan', $note);
        $order->save();
    }

    public function refundOrder(Order $order, int $performedBy = null, ?string $note = null)
    {
        $order->is_refunded = true;
        $order->notes = $this->appendNote($order->notes, 'Refund', $note);
        $order->save();
    }

    public function setPaymentStatus(Order $order, array $payload)
    {
        if (!isset($payload['payment_status'])) throw new \Exception('payment_status diperlukan');
        $order->payment_status = $payload['payment_status'];
        $order->save();
    }

    private function appendNote($existing, $title, $note = null)
    {
        $s = "[".now()->format('d M Y H:i')."] {$title}";
        if ($note) $s .= "\n".$note;
        return trim(($existing ? $existing."\n\n" : "").$s);
    }
}
