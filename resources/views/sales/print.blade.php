<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Penjualan</title>
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
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
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
        .summary td {
            padding: 5px;
        }
        .signature {
            width: 30%;
            text-align: center;
            margin-top: 60px;
            float: right;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body onload="window.print()">

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <div>
            <h2>Invoice Penjualan</h2>
            <div>No Invoice: <strong>{{ $sale->invoice_number }}</strong></div>
        </div>
        <div class="text-end">
            <div>Tanggal: {{ $sale->date->format('Y-m-d') }}</div>
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
                <td colspan="3">{{ $sale->due_date?->format('Y-m-d') }}</td>
            </tr>
        @endif
    </table>

    {{-- ITEM PENJUALAN --}}
    <h4 class="mt-3">Detail Item</h4>

    @php
        $groupedItems = $sale->items->groupBy(
            fn ($item) => optional($item->purchaseItem?->purchase)->invoice_number ?? 'UNKNOWN'
        );
    @endphp

    @foreach ($groupedItems as $purchaseInvoice => $items)
        <div class="mt-3">
            <strong>Sumber Pembelian: {{ $purchaseInvoice }}</strong>
        </div>

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
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-end">
                            {{ number_format($item->qty, 4, ',', '.') }}
                        </td>
                        <td class="text-end">
                            {{ number_format($item->price, 2, ',', '.') }}
                        </td>
                        <td class="text-end">
                            {{ number_format($item->subtotal, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    {{-- RINGKASAN --}}
    <table class="summary table">
        <tr>
            <td>Subtotal</td>
            <td class="text-end">
                {{ number_format($sale->subtotal, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="text-end">
                {{ number_format($sale->discount, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Pajak</td>
            <td class="text-end">
                {{ number_format($sale->tax, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <th>Grand Total</th>
            <th class="text-end">
                {{ number_format($sale->grand_total, 2, ',', '.') }}
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
