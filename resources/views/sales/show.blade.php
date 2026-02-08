@extends('layouts.vertical', ['title' => 'Detail Penjualan'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Penjualan',
        'subTitle' => 'Informasi transaksi penjualan',
    ])

    {{-- ================= HEADER INFO ================= --}}
    <div class="card mb-3">
        <div class="card-body row g-3">

            <div class="col-md-4">
                <label class="form-label">No Invoice</label>
                <div class="fw-bold">{{ $sale->invoice_number }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <div>{{ $sale->date->format('Y-m-d') }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Customer</label>
                <div>{{ $sale->customer?->name ?? '-' }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipe Pembayaran</label>
                <div>
                    <span class="badge {{ $sale->payment_type === 'CASH' ? 'bg-success' : 'bg-warning' }}">
                        {{ $sale->payment_type }}
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <div>
                    <span class="badge bg-secondary">{{ $sale->status }}</span>
                </div>
            </div>

            @if ($sale->payment_type === 'CASH')
                <div class="col-md-4">
                    <label class="form-label">Akun Pembayaran</label>
                    <div class="fw-bold">
                        {{ optional($sale->payments->first()?->cashAccount)->code
                            ? optional($sale->payments->first()->cashAccount)->code .
                                ' - ' .
                                optional($sale->payments->first()->cashAccount)->name
                            : '-' }}
                    </div>
                </div>
            @endif

            {{-- CREDIT INFO --}}
            @if ($sale->payment_type === 'CREDIT')
                <div class="col-md-4">
                    <label class="form-label">Jatuh Tempo</label>
                    <div>{{ $sale->due_date?->format('Y-m-d') ?? '-' }}</div>
                </div>
            @endif

        </div>
    </div>

    {{-- ================= ITEMS ================= --}}
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Item Penjualan</h6>
        </div>

        <div class="card-body table-responsive">

            @php
                $groupedItems = $sale->items->groupBy(
                    fn($item) => optional($item->purchaseItem?->purchase)->invoice_number ?? 'UNKNOWN',
                );
            @endphp

            @foreach ($groupedItems as $purchaseInvoice => $items)
                <div class="mb-4">

                    <div class="fw-bold mb-2">
                        Pembelian: {{ $purchaseInvoice }}
                    </div>

                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
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

                </div>
            @endforeach

        </div>
    </div>


    {{-- ================= HISTORY PEMBAYARAN PIUTANG ================= --}}
    @if ($sale->payment_type === 'CREDIT')
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">History Pembayaran Piutang</h6>
            </div>

            <div class="card-body table-responsive">
                @if ($sale->payments->count() > 0)
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
                            @foreach ($sale->payments as $pay)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pay->date)->format('Y-m-d') }}</td>
                                    <td>
                                        {{ optional($pay->cashAccount)->code }} -
                                        {{ optional($pay->cashAccount)->name ?? '-' }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($pay->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('sales.payments.print', $pay->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
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
                        Belum ada pembayaran piutang.
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ================= RINGKASAN PIUTANG ================= --}}
    @if ($sale->payment_type === 'CREDIT' && $sale->receivable)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Ringkasan Piutang</h6>
            </div>

            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Total Piutang</label>
                    <div class="fw-bold">
                        {{ number_format($sale->receivable->total, 2, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Total Terbayar</label>
                    <div class="fw-bold text-success">
                        {{ number_format($sale->receivable->paid, 2, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sisa Piutang</label>
                    <div class="fw-bold text-danger">
                        {{ number_format($sale->receivable->balance, 2, ',', '.') }}
                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- ================= TOTAL ================= --}}
    <div class="card">
        <div class="card-body row g-3">

            <div class="col-md-3 ms-auto">
                <label class="form-label">Subtotal</label>
                <div class="fw-bold">
                    {{ number_format($sale->subtotal, 2, ',', '.') }}
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Diskon</label>
                <div>
                    {{ number_format($sale->discount, 2, ',', '.') }}
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Pajak</label>
                <div>
                    {{ number_format($sale->tax, 2, ',', '.') }}
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">Grand Total</label>
                <div class="fw-bold fs-5 text-primary">
                    {{ number_format($sale->grand_total, 2, ',', '.') }}
                </div>
            </div>

        </div>

        <div class="card-footer d-flex gap-2">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left"></i> Kembali
            </a>

            <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-outline-secondary" target="_blank">
                <i data-lucide="printer"></i> Print
            </a>

            {{-- BAYAR PIUTANG --}}
            @if ($sale->payment_type === 'CREDIT' && $sale->status !== 'PAID')
                <a href="{{ route('sales.pay.create', $sale->id) }}" class="btn btn-success" data-bs-toggle="tooltip"
                    title="Bayar Piutang">
                    <i class="ti ti-cash"></i> Bayar Piutang
                </a>
            @endif
        </div>
    </div>
@endsection
