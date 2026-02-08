@extends('layouts.vertical', ['title' => 'Manajemen Produk'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Produk',
        'subTitle' => 'Kelola data produk'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Produk</h5>
            @can('products.create')
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah Produk
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="products-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Unit</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-delete-modal
        id="deleteProductModal"
        formId="deleteProductForm"
        :route="route('products.destroy', ':id')"
        title="Hapus Produk"
        message="Yakin ingin menghapus produk ini?"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/products/products.js'])

    <script>
        window.productRoutes = {
            index: '{{ route('products.index') }}',
            edit: '{{ route('products.edit', ':id') }}',
            destroy: '{{ route('products.destroy', ':id') }}',
            toggleActive: '{{ route('products.toggleActive', ':id') }}'
        };
    </script>
@endsection
