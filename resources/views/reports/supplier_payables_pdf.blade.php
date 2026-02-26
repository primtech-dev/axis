<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Hutang Supplier</title>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }
    </style>
</head>

<body>

    <h3>Laporan Hutang Supplier</h3>

    <table>
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Nomor Invoice</th>
                <th>Total</th>
                <th>Terbayar</th>
                <th>Sisa Hutang</th>
                <th>Jatuh Tempo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payables as $p)
                <tr>
                    <td>{{ $p->supplier->name ?? '-' }}</td>
                    <td>{{ $p->purchase->invoice_number ?? '-' }}</td>
                    <td class="text-end">{{ number_format($p->total, 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($p->paid, 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($p->balance, 2, ',', '.') }}</td>
                    <td>
                        {{ optional($p->purchase)->due_date ? \Carbon\Carbon::parse($p->purchase->due_date)->format('d M Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight:bold;">
                <td colspan="2" style="text-align:right;">TOTAL</td>
                <td class="text-end">{{ number_format($grandTotal, 2, ',', '.') }}</td>
                <td class="text-end">{{ number_format($grandPaid, 2, ',', '.') }}</td>
                <td class="text-end">{{ number_format($grandBalance, 2, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
