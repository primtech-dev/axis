@extends('layouts.vertical', ['title' => 'Detail Biaya'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Biaya',
        'subTitle' => 'Informasi biaya pembelian',
    ])

    {{-- HEADER INFO --}}
    <div class="card mb-3">
        <div class="card-body row g-3">

            <div class="col-md-4">
                <label class="form-label">No Biaya</label>
                <div class="fw-bold">{{ $expense->expense_number }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <div>{{ $expense->date->format('d-m-Y') }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Supplier</label>
                <div>{{ $expense->supplier->name }}</div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Terkait Pembelian</label>
                <div class="fw-bold">
                    {{ $expense->purchase->invoice_number ?? '-' }}
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipe Pembayaran</label>
                <div>
                    <span class="badge {{ $expense->payment_type === 'CASH' ? 'bg-success' : 'bg-warning' }}">
                        {{ $expense->payment_type }}
                    </span>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status</label>
                <div>
                    <span class="badge bg-secondary">{{ $expense->status }}</span>
                </div>
            </div>

            @if ($expense->payment_type === 'CASH')
                <div class="col-md-4">
                    <label class="form-label">Akun Pembayaran</label>
                    <div class="fw-bold">
                        {{ optional($expense->payments->first()?->cashAccount)->code
                            ? optional($expense->payments->first()->cashAccount)->code .
                                ' - ' .
                                optional($expense->payments->first()->cashAccount)->name
                            : '-' }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ITEMS --}}
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Detail Biaya</h6>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
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
                            <td class="text-end">{{ number_format($item->qty, 4, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($item->price, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- HISTORY PEMBAYARAN HUTANG --}}
    @if ($expense->payment_type === 'CREDIT')
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">History Pembayaran Hutang</h6>
            </div>

            <div class="card-body table-responsive">
                @if ($expense->payments->count() > 0)
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Akun Pembayaran</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expense->payments as $pay)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pay->date)->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        {{ optional($pay->cashAccount)->code }}
                                        {{ optional($pay->cashAccount)->name ? ' - ' . $pay->cashAccount->name : '' }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($pay->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('expenses.payments.print', $pay->id) }}" target="_blank"
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
                        Belum ada pembayaran hutang.
                    </div>
                @endif
            </div>
        </div>
    @endif


    {{-- RINGKASAN HUTANG --}}
    @if ($expense->payment_type === 'CREDIT' && $expense->payable)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Ringkasan Hutang</h6>
            </div>

            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Total Hutang</label>
                    <div class="fw-bold">
                        {{ number_format($expense->payable->total, 2, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Total Terbayar</label>
                    <div class="fw-bold text-success">
                        {{ number_format($expense->payable->paid, 2, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sisa Hutang</label>
                    <div class="fw-bold text-danger">
                        {{ number_format($expense->payable->balance, 2, ',', '.') }}
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
                <div class="fw-bold fs-5 text-primary">
                    {{ number_format($expense->grand_total, 2, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left"></i> Kembali
            </a>

            <a href="{{ route('expenses.print', $expense->id) }}" class="btn btn-outline-secondary" target="_blank">
                <i data-lucide="printer"></i> Print
            </a>

            @if ($expense->payment_type === 'CREDIT' && $expense->status !== 'PAID')
                <a href="{{ route('expenses.pay.create', $expense->id) }}" class="btn btn-success" data-bs-toggle="tooltip"
                    title="Bayar Hutang">
                    <i class="ti ti-cash"></i> Bayar Hutang
                </a>
            @endif
        </div>
    </div>
@endsection
