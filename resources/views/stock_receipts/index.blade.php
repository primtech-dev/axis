@extends('layouts.vertical', ['title' => 'Stok Masuk'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Stok Masuk',
        'subTitle' => 'Daftar penerimaan barang',
        'breadcrumbs' => [['name'=>'Persediaan'], ['name'=>'Stok Masuk']]
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Stok Masuk</h5>
            @can('stock_receipts.create')
                <a href="{{ route('stock_receipts.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" class="me-1"></i> Tambah Stok Masuk
                </a>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-striped dt-responsive w-100" id="stock-receipts-table">
                <thead><tr>
                    <th>No</th><th>Public ID</th><th>Branch</th><th>Supplier</th><th>Ref</th><th>Status</th><th>Total Items</th><th>Received At</th><th>Aksi</th>
                </tr></thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <x-delete-modal id="deleteStockReceiptModal" formId="deleteStockReceiptForm" :route="route('stock_receipts.destroy', ':id')" itemNameId="delete_stock_receipt_title" title="Konfirmasi Hapus" message="Hapus stok masuk?"/>
@endsection

@section('scripts')
    @vite(['resources/js/pages/stock-receipts/index.js'])
    <script>
        window.stockReceiptRoutes = {
            index: '{{ route('stock_receipts.index') }}',
            show: '{{ route('stock_receipts.show', ":id") }}',
            edit: '{{ route('stock_receipts.edit', ":id") }}',
            destroy: '{{ route('stock_receipts.destroy', ":id") }}'
        };
    </script>
@endsection
