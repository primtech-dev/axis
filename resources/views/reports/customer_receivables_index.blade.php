@extends('layouts.vertical', ['title' => 'Laporan Piutang Customer'])

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Laporan Piutang Customer',
        'subTitle' => 'Daftar transaksi piutang customer',
    ])

    <div class="card mb-4">
        <div class="card-body">

            <form method="GET">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label>Customer</label>
                        <select name="customer_ids[]" id="customerSelect" multiple class="form-select">
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}" @if (request()->customer_ids && in_array($c->id, request()->customer_ids)) selected @endif>
                                    {{ $c->name }}
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
                Daftar Piutang
            </h5>

            <div class="d-flex gap-2">
                <a href="{{ route('reports.customer-receivables.pdf', request()->query()) }}" class="btn btn-danger btn-sm"
                    target="_blank">
                    <i class="ti ti-file-type-pdf me-1"></i>
                    Export PDF
                </a>

                <a href="{{ route('reports.customer-receivables.excel', request()->query()) }}"
                    class="btn btn-success btn-sm">
                    <i class="ti ti-file-spreadsheet me-1"></i>
                    Export Excel
                </a>
            </div>
        </div>

        <div class="card-body table-responsive">

            @php
                $grandTotal = $receivables->sum('total');
                $grandPaid = $receivables->sum('paid');
                $grandBalance = $receivables->sum('balance');
            @endphp

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Customer</th>
                        <th>Nomor Invoice</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Terbayar</th>
                        <th class="text-end">Sisa</th>
                        <th>Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receivables as $r)
                        <tr>
                            <td>{{ $r->customer->name ?? '-' }}</td>
                            <td>{{ $r->sale->invoice_number ?? '-' }}</td>
                            <td class="text-end">{{ number_format($r->total, 2, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($r->paid, 2, ',', '.') }}</td>
                            <td class="text-end text-danger fw-bold">{{ number_format($r->balance, 2, ',', '.') }}</td>
                            <td>
                                {{ optional($r->sale)->due_date ? \Carbon\Carbon::parse($r->sale->due_date)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end">TOTAL</td>
                        <td class="text-end">{{ number_format($grandTotal, 2, ',', '.') }}</td>
                        <td class="text-end">{{ number_format($grandPaid, 2, ',', '.') }}</td>
                        <td class="text-end text-danger">{{ number_format($grandBalance, 2, ',', '.') }}</td>
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
        new TomSelect("#customerSelect", {
            plugins: ['remove_button'],
        });
    </script>
@endsection
