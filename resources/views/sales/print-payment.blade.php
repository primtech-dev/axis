<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Piutang</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }
        .container {
            width: 100%;
        }
        .header {
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .table th {
            background: #f2f2f2;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .mt-3 {
            margin-top: 20px;
        }
        .signature {
            width: 30%;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <h2>Bukti Pembayaran Piutang</h2>
        <div>Tanggal Cetak: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    {{-- INFO SALE --}}
    <table class="table">
        <tr>
            <td width="20%">No Invoice</td>
            <td width="30%">{{ $sale->invoice_number }}</td>
            <td width="20%">Tanggal Penjualan</td>
            <td width="30%">{{ $sale->date->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>{{ $sale->customer->name }}</td>
            <td>Status Penjualan</td>
            <td>{{ $sale->status }}</td>
        </tr>
    </table>

    {{-- INFO PAYMENT --}}
    <h4 class="mt-3">Detail Pembayaran</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Bayar</th>
                <th>Akun Pembayaran</th>
                <th class="text-end">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ \Carbon\Carbon::parse($payment->date)->format('Y-m-d') }}</td>
                <td>
                    {{ optional($payment->cashAccount)->code }} -
                    {{ optional($payment->cashAccount)->name }}
                </td>
                <td class="text-end">
                    {{ number_format($payment->amount, 2, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- INFO PIUTANG --}}
    <h4 class="mt-3">Ringkasan Piutang</h4>
    <table class="table">
        <tr>
            <td width="40%">Total Piutang</td>
            <td width="60%" class="text-end">
                {{ number_format($sale->receivable->total, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Total Terbayar</td>
            <td class="text-end">
                {{ number_format($sale->receivable->paid, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Sisa Piutang</td>
            <td class="text-end">
                {{ number_format($sale->receivable->balance, 2, ',', '.') }}
            </td>
        </tr>
    </table>

    {{-- TANDA TANGAN --}}
    <div class="signature">
        <div>Dibayar Oleh,</div>
        <br><br><br>
        <div>( {{ auth()->user()->name }} )</div>
    </div>

</div>

</body>
</html>
