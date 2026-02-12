<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Biaya - {{ $expense->expense_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .text-end {
            text-align: right;
        }

        .no-border {
            border: none !important;
        }
    </style>
</head>
<body>

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

<h3 style="margin-bottom:5px;">BUKTI BIAYA</h3>

{{-- INFO HEADER --}}
<table class="no-border">
    <tr>
        <td class="no-border" width="120">No Biaya</td>
        <td class="no-border">: {{ $expense->expense_number }}</td>
    </tr>
    <tr>
        <td class="no-border">Tanggal</td>
        <td class="no-border">: {{ $expense->date->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td class="no-border">Supplier</td>
        <td class="no-border">: {{ $expense->supplier->name }}</td>
    </tr>
    <tr>
        <td class="no-border">Pembelian</td>
        <td class="no-border">
            : {{ $expense->purchase->invoice_number ?? '-' }}
        </td>
    </tr>
</table>

{{-- DETAIL ITEM --}}
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
                <td class="text-end">{{ formatQty($item->qty) }}</td>
                <td class="text-end">{{ formatRupiah($item->price) }}</td>
                <td class="text-end">{{ formatRupiah($item->subtotal) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3" class="text-end">TOTAL</th>
            <th class="text-end">
                {{ formatRupiah($expense->grand_total) }}
            </th>
        </tr>
    </tbody>
</table>

<br><br>

{{-- TANDA TANGAN --}}
<table class="no-border">
    <tr>
        <td class="no-border text-center" width="50%">
            Dibuat Oleh,
            <br><br><br>
            _______________________
        </td>
        <td class="no-border text-center">
            Disetujui,
            <br><br><br>
            _______________________
        </td>
    </tr>
</table>

<script>
    window.onload = function () {
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    };
</script>

</body>
</html>
