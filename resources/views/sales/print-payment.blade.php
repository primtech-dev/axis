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

        .header {
            margin-bottom: 15px;
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
            margin-top: 60px;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>
<body onload="window.print()">

@php
    function formatRupiah($value) {
        return 'Rp ' . number_format($value, 2, ',', '.');
    }
@endphp

<div class="container">

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

    {{-- HEADER --}}
    <div class="header">
        <h3 style="margin:0;">BUKTI PEMBAYARAN PIUTANG</h3>
        <div>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</div>
    </div>

    {{-- INFO SALE --}}
    <table class="table">
        <tr>
            <td width="20%">No Invoice</td>
            <td width="30%">{{ $sale->invoice_number }}</td>
            <td width="20%">Tanggal Penjualan</td>
            <td width="30%">{{ $sale->date->format('d-m-Y') }}</td>
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
                <td>{{ \Carbon\Carbon::parse($payment->date)->format('d-m-Y') }}</td>
                <td>
                    {{ optional($payment->cashAccount)->code }} -
                    {{ optional($payment->cashAccount)->name }}
                </td>
                <td class="text-end">
                    {{ formatRupiah($payment->amount) }}
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
                {{ formatRupiah($sale->receivable->total) }}
            </td>
        </tr>
        <tr>
            <td>Total Terbayar</td>
            <td class="text-end">
                {{ formatRupiah($sale->receivable->paid) }}
            </td>
        </tr>
        <tr>
            <td>Sisa Piutang</td>
            <td class="text-end">
                {{ formatRupiah($sale->receivable->balance) }}
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
