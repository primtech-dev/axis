@extends('layouts.vertical', ['title' => 'Transaksi Penjualan'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Penjualan',
        'subTitle' => 'Transaksi penjualan barang',
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Penjualan</h5>

            @can('sales.create')
                <a href="{{ route('sales.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="sales-table" class="table table-striped w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Tipe</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/sales/sales.js'])

    <script>
        window.saleRoutes = {
            index: '{{ route('sales.index') }}',
            show: '{{ route('sales.show', ':id') }}',
        };
    </script>
@endsection
