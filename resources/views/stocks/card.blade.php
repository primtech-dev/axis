@extends('layouts.vertical', ['title' => 'Kartu Stok'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Kartu Stok',
        'subTitle' => $product->name . ' (' . $product->sku . ')',
    ])

    <div class="card mb-3">
        <div class="card-body">
            <div><strong>SKU:</strong> {{ $product->sku }}</div>
            <div><strong>Nama:</strong> {{ $product->name }}</div>
            <div><strong>Satuan:</strong> {{ $product->unit->name }}</div>
        </div>
    </div>


    <div class="card mb-3 py-2 px-2">
        <form method="GET" class="row g-2 mb-3">

            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control">
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button class="btn btn-primary" type="submit">
                    <i class="ti ti-filter"></i> Filter
                </button>

                <a href="{{ route('stocks.card', $product->id) }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Referensi</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                        <th class="text-end">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movements as $m)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($m->created_at)->format('Y-m-d H:i') }}</td>
                            <td>{{ $m->type }}</td>
                            <td>{{ $m->note }}</td>
                            <td class="text-end">
                                {{ $m->qty > 0 ? number_format($m->qty, 2, ',', '.') : '-' }}
                            </td>
                            <td class="text-end">
                                {{ $m->qty < 0 ? number_format(abs($m->qty), 2, ',', '.') : '-' }}
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($m->saldo, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer d-flex gap-2">
            <a href="{{ route('stocks.card.export.excel', $product->id) . '?from=' . $from . '&to=' . $to }}"
                class="btn btn-outline-success">
                <i class="ti ti-file-spreadsheet"></i> Export Excel
            </a>

            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
