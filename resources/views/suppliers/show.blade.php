@extends('layouts.vertical', ['title' => 'Detail Supplier'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Supplier',
        'subTitle' => 'Informasi supplier dan hutang pembelian',
    ])

    {{-- =========================
| HEADER SUPPLIER
========================= --}}
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h4 class="mb-1">{{ $supplier->name }}</h4>
                <div class="text-muted">
                    {{ $supplier->phone ?? '-' }}
                    @if ($supplier->email)
                        · {{ $supplier->email }}
                    @endif
                </div>
            </div>

            <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>

        </div>
    </div>

    <div class="d-flex gap-2 mb-3">

        <a href="{{ route('reports.supplier-payables.pdf', ['supplier_id' => $supplier->id]) }}"
            class="btn btn-outline-secondary" target="_blank">
            <i class="ti ti-printer"></i> Export PDF
        </a>

        <a href="{{ route('reports.supplier-payables.excel', ['supplier_id' => $supplier->id]) }}"
            class="btn btn-outline-success">
            <i class="ti ti-file-spreadsheet"></i> Export Excel
        </a>

    </div>

    {{-- =========================
| RINGKASAN HUTANG
========================= --}}
    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="text-muted">Total Hutang</div>
                    <h4 class="text-danger mb-0">
                        {{ number_format($supplier->payables->sum('total'), 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="text-muted">Total Terbayar</div>
                    <h4 class="text-success mb-0">
                        {{ number_format($supplier->payables->sum('paid'), 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="text-muted">Sisa Hutang</div>
                    <h4 class="text-warning mb-0">
                        {{ number_format($totalHutang, 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    {{-- =========================
| AGING HUTANG
========================= --}}
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Aging Hutang</h6>
        </div>

        <div class="card-body row g-3">

            <div class="col-md-4">
                <div class="p-3 border rounded">
                    <div class="text-muted">0 – 30 Hari</div>
                    <h5 class="mb-0 text-success">
                        {{ number_format($aging['0_30'], 2, ',', '.') }}
                    </h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 border rounded">
                    <div class="text-muted">31 – 60 Hari</div>
                    <h5 class="mb-0 text-warning">
                        {{ number_format($aging['31_60'], 2, ',', '.') }}
                    </h5>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 border rounded">
                    <div class="text-muted">&gt; 60 Hari</div>
                    <h5 class="mb-0 text-danger">
                        {{ number_format($aging['60_up'], 2, ',', '.') }}
                    </h5>
                </div>
            </div>

        </div>
    </div>


    {{-- =========================
| DETAIL HUTANG PER PEMBELIAN
========================= --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Detail Hutang Pembelian</h6>
            <span class="text-muted small">
                {{ $supplier->payables->count() }} transaksi
            </span>
        </div>

        <div class="card-body table-responsive">

            @if ($supplier->payables->count() === 0)
                <div class="text-muted">
                    Belum ada transaksi pembelian.
                </div>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Terbayar</th>
                            <th class="text-end">Sisa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supplier->payables as $payable)
                            <tr>

                                <td class="fw-semibold">
                                    {{ $payable->purchase->invoice_number }}
                                </td>

                                <td>
                                    {{ $payable->purchase->date->format('Y-m-d') }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($payable->total, 2, ',', '.') }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format($payable->paid, 2, ',', '.') }}
                                </td>

                                <td class="text-end {{ $payable->balance > 0 ? 'text-danger' : 'text-muted' }}">
                                    {{ number_format($payable->balance, 2, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    <span class="badge {{ $payable->balance > 0 ? 'bg-warning' : 'bg-success' }}">
                                        {{ $payable->balance > 0 ? 'Belum Lunas' : 'Lunas' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('purchases.show', $payable->purchase->id) }}"
                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                        title="Lihat Pembelian">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    @if ($payable->balance > 0)
                                        <a href="{{ route('purchases.pay.create', $payable->purchase->id) }}"
                                            class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip"
                                            title="Bayar Hutang">
                                            <i class="ti ti-cash"></i>
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>

        <div class="card-footer d-flex gap-2">
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
