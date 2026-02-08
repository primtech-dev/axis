@extends('layouts.vertical', ['title' => 'Stok Barang'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Stok Barang',
        'subTitle' => 'Stok terkini berdasarkan mutasi',
    ])

    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Stok Barang</h5>

        <a href="{{ route('stocks.export.excel') }}" class="btn btn-outline-success">
            <i class="ti ti-file-spreadsheet"></i> Export Excel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="stocks-table" class="table table-striped w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>SKU</th>
                        <th>Nama Barang</th>
                        <th>Unit</th>
                        <th class="text-end">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/stocks/index.js'])

    <script>
        window.stockRoutes = {
            index: '{{ route('stocks.index') }}'
        };
    </script>
@endsection
