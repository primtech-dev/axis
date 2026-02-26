@extends('layouts.vertical', ['title' => 'Laporan Hutang Supplier'])

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Laporan Hutang Supplier',
        'subTitle' => 'Daftar transaksi hutang supplier',
    ])

    <div class="card mb-4">
        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_ids[]" id="supplierSelect" multiple class="form-select">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @if (request()->supplier_ids && in_array($supplier->id, request()->supplier_ids)) selected @endif>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100">
                            <i class="ti ti-search"></i> Filter
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="ti ti-report-money"></i>
                Daftar Hutang Supplier
            </h5>

            <div class="d-flex gap-2">

                <a href="{{ route('reports.supplier-payables.pdf', request()->query()) }}" class="btn btn-danger btn-sm"
                    target="_blank">
                    <i class="ti ti-file-type-pdf me-1"></i>
                    Export PDF
                </a>

                <a href="{{ route('reports.supplier-payables.excel', request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    Export Excel
                </a>

            </div>
        </div>
        <div class="card-body table-responsive">

            @php
                $grandTotal = $payables->sum('total');
                $grandPaid = $payables->sum('paid');
                $grandBalance = $payables->sum('balance');
            @endphp

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Supplier</th>
                        <th>No PO</th>
                        <th class="text-end">Total Transaksi</th>
                        <th class="text-end">Terbayar</th>
                        <th class="text-end">Sisa Hutang</th>
                        <th>Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payables as $p)
                        <tr>
                            <td>{{ $p->supplier->name ?? '-' }}</td>

                            <td>
                                {{ $p->purchase->invoice_number ?? '-' }}
                            </td>

                            <td class="text-end">
                                {{ number_format($p->total, 2, ',', '.') }}
                            </td>

                            <td class="text-end">
                                {{ number_format($p->paid, 2, ',', '.') }}
                            </td>

                            <td class="text-end text-danger fw-bold">
                                {{ number_format($p->balance, 2, ',', '.') }}
                            </td>

                            <td>
                                {{ optional($p->purchase)->due_date ? \Carbon\Carbon::parse($p->purchase->due_date)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada data hutang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-end">
                            {{ number_format($grandTotal, 2, ',', '.') }}
                        </td>
                        <td class="text-end">
                            {{ number_format($grandPaid, 2, ',', '.') }}
                        </td>
                        <td class="text-end text-danger">
                            {{ number_format($grandBalance, 2, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        new TomSelect("#supplierSelect", {
            plugins: ['remove_button'],
            placeholder: "Pilih supplier (kosong = semua)",
        });
    </script>
@endsection
