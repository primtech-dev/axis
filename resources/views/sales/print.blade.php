<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Penjualan {{ $sale->invoice_number }}</title>
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

        .company-info h2 {
            margin: 0;
        }

        hr {
            margin: 10px 0 15px 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .summary {
            width: 40%;
            float: right;
            margin-top: 15px;
        }

        .summary td,
        .summary th {
            padding: 5px;
        }

        .signature {
            width: 30%;
            text-align: center;
            margin-top: 70px;
            float: right;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body onload="window.print()">

@php
    function formatQty($value) {
        return fmod($value, 1) == 0
            ? number_format($value, 0, ',', '.')
            : rtrim(rtrim(number_format($value, 4, ',', '.'), '0'), ',');
    }

    function formatRupiah($value) {
        return 'Rp ' . number_format($value, 2, ',', '.');
    }
@endphp

<div class="container">

    {{-- KOP SURAT --}}
    <div class="kop">
        <img src="{{ asset('images/logo-pt.png') }}" class="logo">

        <div class="company-info">
            <h2>PT PANCA PRIMA BAHARI</h2>
            <div>Nganjuk, Jawa Timur</div>
            <div>Telp: 08xxxxx</div>
        </div>
    </div>

    <hr>

    {{-- HEADER INVOICE --}}
    <div class="header">
        <div>
            <h3 style="margin:0;">INVOICE PENJUALAN</h3>
            <div>No Invoice: <strong>{{ $sale->invoice_number }}</strong></div>
        </div>
        <div class="text-end">
            <div>Tanggal: {{ $sale->date->format('d-m-Y') }}</div>
            <div>Status: {{ $sale->status }}</div>
        </div>
    </div>

    {{-- INFO CUSTOMER --}}
    <table class="table">
        <tr>
            <td width="20%">Customer</td>
            <td width="30%">{{ $sale->customer?->name ?? '-' }}</td>
            <td width="20%">Tipe Pembayaran</td>
            <td width="30%">{{ $sale->payment_type }}</td>
        </tr>
        @if ($sale->payment_type === 'CREDIT')
            <tr>
                <td>Jatuh Tempo</td>
                <td colspan="3">{{ $sale->due_date?->format('d-m-Y') }}</td>
            </tr>
        @endif
    </table>

    {{-- DETAIL ITEM (TANPA GROUPING) --}}
    <h4 class="mt-3">Detail Item</h4>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-end">{{ formatQty($item->qty) }}</td>
                    <td class="text-end">{{ formatRupiah($item->price) }}</td>
                    <td class="text-end">{{ formatRupiah($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- RINGKASAN --}}
    <table class="summary table">
        <tr>
            <td>Subtotal</td>
            <td class="text-end">
                {{ formatRupiah($sale->subtotal) }}
            </td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="text-end">
                {{ formatRupiah($sale->discount) }}
            </td>
        </tr>
        <tr>
            <td>Pajak</td>
            <td class="text-end">
                {{ formatRupiah($sale->tax) }}
            </td>
        </tr>
        <tr>
            <th>Grand Total</th>
            <th class="text-end">
                {{ formatRupiah($sale->grand_total) }}
            </th>
        </tr>
    </table>

    <div class="clearfix"></div>

    {{-- TANDA TANGAN --}}
    <div class="signature">
        <div>Hormat Kami,</div>
        <br><br><br>
        <div>( {{ auth()->user()->name }} )</div>
    </div>

</div>

</body>
</html>
