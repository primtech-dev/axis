<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background: #f2f2f2; }
        .right { text-align: right; }
    </style>
</head>
<body>

<h3>Laporan Hutang Supplier</h3>

<table>
    <thead>
    <tr>
        <th>Supplier</th>
        <th>Invoice</th>
        <th>Tanggal</th>
        <th class="right">Total</th>
        <th class="right">Terbayar</th>
        <th class="right">Sisa</th>
    </tr>
    </thead>
    <tbody>
    @foreach($payables as $p)
        <tr>
            <td>{{ $p->supplier->name }}</td>
            <td>{{ $p->purchase->invoice_number }}</td>
            <td>{{ $p->purchase->date->format('Y-m-d') }}</td>
            <td class="right">{{ number_format($p->total, 2, ',', '.') }}</td>
            <td class="right">{{ number_format($p->paid, 2, ',', '.') }}</td>
            <td class="right">{{ number_format($p->balance, 2, ',', '.') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
