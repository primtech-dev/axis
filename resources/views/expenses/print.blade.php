<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Biaya - {{ $expense->expense_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
        .text-end { text-align: right; }
    </style>
</head>
<body>

<h3>BUKTI BIAYA</h3>

<table>
    <tr>
        <td>No Biaya</td>
        <td>{{ $expense->expense_number }}</td>
        <td>Tanggal</td>
        <td>{{ $expense->date->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td>Supplier</td>
        <td colspan="3">{{ $expense->supplier->name }}</td>
    </tr>
    <tr>
        <td>Pembelian</td>
        <td colspan="3">{{ $expense->purchase->invoice_number ?? '-' }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Nama Biaya</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Harga</th>
            <th class="text-end">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($expense->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="text-end">{{ number_format($item->qty, 4, ',', '.') }}</td>
                <td class="text-end">{{ number_format($item->price, 2, ',', '.') }}</td>
                <td class="text-end">{{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3" class="text-end">TOTAL</th>
            <th class="text-end">
                {{ number_format($expense->grand_total, 2, ',', '.') }}
            </th>
        </tr>
    </tbody>
</table>

<script>
    window.onload = function () {
        window.print();

        // OPTIONAL: auto close tab setelah print
        window.onafterprint = function () {
            window.close();
        };
    };
</script>

</body>
</html>
