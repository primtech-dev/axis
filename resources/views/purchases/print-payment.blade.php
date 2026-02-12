<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Hutang</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .kop {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 90px;
        }

        .company-info {
            margin-left: 15px;
        }

        .company-info h3 {
            margin: 0;
        }

        hr {
            margin: 10px 0 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        td, th {
            padding: 6px;
            border: 1px solid #000;
        }

        th {
            background: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .no-border td {
            border: none !important;
            padding: 4px 0;
        }

        .signature {
            width: 30%;
            text-align: center;
            margin-top: 60px;
        }
    </style>
</head>
<body onload="window.print()">

@php
    function formatRupiah($value) {
        return 'Rp ' . number_format($value, 2, ',', '.');
    }
@endphp

{{-- KOP SURAT --}}
<div class="kop">
    <img src="{{ asset('images/logo-pt.png') }}" class="logo">

    <div class="company-info">
        <h3>PT PANCA PRIMA BAHARI</h3>
        <div>Nganjuk, Jawa Timur</div>
        <div>Telp: 08xxxxx</div>
    </div>
</div>

<hr>

<h3 style="margin-bottom:5px;">BUKTI PEMBAYARAN HUTANG</h3>

{{-- INFO HEADER --}}
<table class="no-border">
    <tr>
        <td width="150">Invoice</td>
        <td>: {{ $purchase->invoice_number }}</td>
    </tr>
    <tr>
        <td>Supplier</td>
        <td>: {{ $purchase->supplier->name }}</td>
    </tr>
    <tr>
        <td>Tanggal Bayar</td>
        <td>: {{ \Carbon\Carbon::parse($payment->date)->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td>Akun Pembayaran</td>
        <td>:
            @if ($payment->cashAccount)
                {{ $payment->cashAccount->code }} - {{ $payment->cashAccount->name }}
            @else
                -
            @endif
        </td>
    </tr>
</table>

{{-- JUMLAH DIBAYAR --}}
<table>
    <tr>
        <th>Jumlah Dibayar</th>
        <th class="text-right">
            {{ formatRupiah($payment->amount) }}
        </th>
    </tr>
</table>

<p style="margin-top: 25px;">
    Dicetak pada {{ now()->format('d-m-Y H:i') }}
</p>

{{-- TANDA TANGAN --}}
<div class="signature">
    <div>Dibayar Oleh,</div>
    <br><br><br>
    <div>( {{ auth()->user()->name }} )</div>
</div>

</body>
</html>
