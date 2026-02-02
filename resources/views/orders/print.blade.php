<!DOCTYPE html>
<html>
<head>
    <title>Print Order {{ $order->order_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #000; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .header { margin-bottom: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Order {{ $order->order_no }}</h2>
    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
    <p><strong>Customer:</strong> {{ $order->customer->full_name ?? '-' }}</p>

    @if($order->address)
        <p><strong>Alamat:</strong><br>
            {{ $order->address->street }}<br>
            {{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}<br>
            {{ $order->address->country }}
        </p>
    @endif
</div>

<h4>Items</h4>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Produk</th>
        <th>Qty</th>
        <th>Harga</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->line_quantity }}</td>
            <td>{{ number_format($item->unit_price) }}</td>
            <td>{{ number_format($item->line_total) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4>Total</h4>
<p><strong>Total Amount:</strong> Rp {{ number_format($order->total_amount) }}</p>

<button class="no-print" onclick="window.print()">Print</button>

</body>
</html>
