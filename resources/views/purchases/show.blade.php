@extends('layouts.vertical', ['title' => 'Detail Pembelian'])

@section('content')

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

@include('layouts.shared.page-title', [
    'title' => 'Detail Pembelian',
    'subTitle' => 'Informasi transaksi pembelian',
])

{{-- HEADER INFO --}}
<div class="card mb-3">
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">No Invoice</label>
            <div class="fw-bold">{{ $purchase->invoice_number }}</div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tanggal</label>
            <div>{{ $purchase->date->format('d-m-Y') }}</div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Supplier</label>
            <div>{{ $purchase->supplier->name }}</div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipe Pembayaran</label>
            <div>
                <span class="badge {{ $purchase->payment_type === 'CASH' ? 'bg-success' : 'bg-warning' }}">
                    {{ $purchase->payment_type }}
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Status</label>
            <div>
                <span class="badge bg-secondary">{{ $purchase->status }}</span>
            </div>
        </div>

        @if ($purchase->payment_type === 'CASH')
            <div class="col-md-4">
                <label class="form-label">Akun Pembayaran</label>
                <div class="fw-bold">
                    {{ optional($purchase->payments->first()?->cashAccount)->code
                        ? optional($purchase->payments->first()->cashAccount)->code .
                            ' - ' .
                            optional($purchase->payments->first()->cashAccount)->name
                        : '-' }}
                </div>
            </div>
        @endif

    </div>
</div>

{{-- ITEMS --}}
<div class="card mb-3">
    <div class="card-header">
        <h6 class="mb-0">Item Pembelian</h6>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-end">{{ formatQty($item->qty) }}</td>
                        <td class="text-end">{{ formatRupiah($item->price) }}</td>
                        <td class="text-end">{{ formatRupiah($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- HISTORY PEMBAYARAN HUTANG --}}
@if ($purchase->payment_type === 'CREDIT')
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">History Pembayaran Hutang</h6>
        </div>

        <div class="card-body table-responsive">
            @if ($purchase->payments->count() > 0)
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Akun</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->payments as $pay)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($pay->date)->format('Y-m-d') }}</td>
                                <td>{{ optional($pay->cashAccount)->code }} - {{ optional($pay->cashAccount)->name ?? '-' }}</td>
                                <td class="text-end">
                                    {{ formatRupiah($pay->amount) }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('purchases.payments.print', $pay->id) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="tooltip"
                                        title="Print Bukti">
                                        <i class="ti ti-printer"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-muted">
                    Belum ada pembayaran hutang.
                </div>
            @endif
        </div>
    </div>
@endif

{{-- RINGKASAN HUTANG --}}
@if ($purchase->payment_type === 'CREDIT' && $purchase->payable)
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Ringkasan Hutang</h6>
        </div>

        <div class="card-body row g-3">

            <div class="col-md-4">
                <label class="form-label">Total Hutang</label>
                <div class="fw-bold">
                    {{ formatRupiah($purchase->payable->total) }}
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Total Terbayar</label>
                <div class="fw-bold text-success">
                    {{ formatRupiah($purchase->payable->paid) }}
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Sisa Hutang</label>
                <div class="fw-bold text-danger">
                    {{ formatRupiah($purchase->payable->balance) }}
                </div>
            </div>

        </div>
    </div>
@endif

{{-- TOTAL --}}
<div class="card">
    <div class="card-body row g-3">

        <div class="col-md-3 ms-auto">
            <label class="form-label">Subtotal</label>
            <div class="fw-bold">
                {{ formatRupiah($purchase->subtotal) }}
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">Diskon</label>
            <div>
                {{ formatRupiah($purchase->discount) }}
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">Pajak</label>
            <div>
                {{ formatRupiah($purchase->tax) }}
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">Grand Total</label>
            <div class="fw-bold fs-5 text-primary">
                {{ formatRupiah($purchase->grand_total) }}
            </div>
        </div>

    </div>

    <div class="card-footer d-flex gap-2">
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
            <i data-lucide="arrow-left"></i> Kembali
        </a>

        <a href="{{ route('purchases.print', $purchase->id) }}"
            class="btn btn-outline-secondary"
            target="_blank">
            <i data-lucide="printer"></i> Print
        </a>

        @if ($purchase->payment_type === 'CREDIT' && $purchase->status !== 'PAID')
            <a href="{{ route('purchases.pay.create', $purchase->id) }}"
                class="btn btn-success"
                data-bs-toggle="tooltip"
                title="Bayar Hutang">
                <i class="ti ti-cash"></i> Bayar Hutang
            </a>
        @endif
    </div>
</div>

@endsection
