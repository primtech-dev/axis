@extends('layouts.vertical', ['title' => 'Transaksi Pembelian'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Pembelian',
        'subTitle' => 'Transaksi pembelian barang'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Pembelian</h5>

            @can('purchases.create')
                <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="purchases-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
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
    @vite(['resources/js/pages/purchases/purchases.js'])

    <script>
        window.purchaseRoutes = {
            index: '{{ route('purchases.index') }}',
            show: '{{ route('purchases.show', ':id') }}',
        };
    </script>
@endsection
