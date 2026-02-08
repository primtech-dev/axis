@extends('layouts.vertical', ['title' => 'Detail Customer'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Customer',
        'subTitle' => 'Informasi customer dan piutang penjualan',
    ])

    {{-- HEADER CUSTOMER --}}
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">{{ $customer->name }}</h4>
                <div class="text-muted">
                    {{ $customer->phone ?? '-' }}
                    @if ($customer->email)
                        · {{ $customer->email }}
                    @endif
                </div>
            </div>

            <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                {{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
    </div>

    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="text-muted">Total Piutang</div>
                    <h4 class="text-danger mb-0">
                        {{ number_format($customer->receivables->sum('total'), 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="text-muted">Total Terbayar</div>
                    <h4 class="text-success mb-0">
                        {{ number_format($customer->receivables->sum('paid'), 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="text-muted">Sisa Piutang</div>
                    <h4 class="text-warning mb-0">
                        {{ number_format($totalPiutang, 2, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Aging Piutang</h6>
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Detail Piutang Penjualan</h6>
            <span class="text-muted small">
                {{ $customer->receivables->count() }} transaksi
            </span>
        </div>

        <div class="card-body table-responsive">

            @if ($customer->receivables->count() === 0)
                <div class="text-muted">
                    Belum ada transaksi penjualan.
                </div>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Tanggal</th>
                            <th>Jatuh Tempo</th> {{-- 🔥 TAMBAHAN --}}
                            <th class="text-end">Total</th>
                            <th class="text-end">Terbayar</th>
                            <th class="text-end">Sisa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer->receivables as $receivable)
                            <tr>

                                <td class="fw-semibold">
                                    {{ $receivable->sale->invoice_number }}
                                </td>

                                <td>
                                    {{ $receivable->sale->date->format('Y-m-d') }}
                                </td>

                                <td>
                                    {{ $receivable->sale->due_date ? $receivable->sale->due_date->format('Y-m-d') : '-' }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($receivable->total, 2, ',', '.') }}
                                </td>

                                <td class="text-end text-success">
                                    {{ number_format($receivable->paid, 2, ',', '.') }}
                                </td>

                                <td class="text-end {{ $receivable->balance > 0 ? 'text-danger' : 'text-muted' }}">
                                    {{ number_format($receivable->balance, 2, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    <span class="badge {{ $receivable->balance > 0 ? 'bg-warning' : 'bg-success' }}">
                                        {{ $receivable->balance > 0 ? 'Belum Lunas' : 'Lunas' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('sales.show', $receivable->sale->id) }}"
                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                        title="Lihat Penjualan">
                                        <i class="ti ti-eye"></i>
                                    </a>

                                    @if ($receivable->balance > 0)
                                        <a href="{{ route('sales.pay.create', $receivable->sale->id) }}"
                                            class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip"
                                            title="Bayar Piutang">
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
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
