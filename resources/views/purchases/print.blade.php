<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Pembelian {{ $purchase->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .no-border { border: none; }
    </style>
</head>
<body onload="window.print()">

<h3>PEMBELIAN</h3>

<table class="no-border">
    <tr>
        <td class="no-border">Invoice</td>
        <td class="no-border">: {{ $purchase->invoice_number }}</td>
    </tr>
    <tr>
        <td class="no-border">Tanggal</td>
        <td class="no-border">: {{ $purchase->date->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td class="no-border">Supplier</td>
        <td class="no-border">: {{ $purchase->supplier->name }}</td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th>Produk</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchase->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td class="text-right">{{ number_format($item->qty, 4, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->price, 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="no-border" style="margin-top: 10px;">
    <tr>
        <td class="no-border text-right">Subtotal</td>
        <td class="no-border text-right">{{ number_format($purchase->subtotal, 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="no-border text-right">Diskon</td>
        <td class="no-border text-right">{{ number_format($purchase->discount, 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="no-border text-right">Pajak</td>
        <td class="no-border text-right">{{ number_format($purchase->tax, 2, ',', '.') }}</td>
    </tr>
    <tr>
        <td class="no-border text-right"><strong>Grand Total</strong></td>
        <td class="no-border text-right">
            <strong>{{ number_format($purchase->grand_total, 2, ',', '.') }}</strong>
        </td>
    </tr>
</table>

</body>
</html>
