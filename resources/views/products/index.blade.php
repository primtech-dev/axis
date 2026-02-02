@extends('layouts.vertical', ['title' => 'Manajemen Produk'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Produk',
        'subTitle' => 'Kelola produk, varian, kategori, dan gambar',
        'breadcrumbs' => [
            ['name' => 'Produk']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">Daftar Produk</h5>

                    @can('products.create')
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i data-lucide="plus" class="me-1"></i> Tambah Produk
                            </a>

                            <a href="{{ route('products.import.form') }}" class="btn btn-outline-primary">
                                <i data-lucide="upload" class="me-1"></i> Import Excel
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="products-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>SKU</th>
                            <th>Jumlah Varian</th>
                            <th width="10%">Status</th>
                            <th width="12%">Created At</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-delete-modal
        id="deleteProductModal"
        formId="deleteProductForm"
        :route="route('products.destroy', ':id')"
        itemNameId="delete_product_title"
        title="Konfirmasi Hapus Produk"
        message="Apakah Anda yakin ingin menghapus produk ini?"
        itemType="produk"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/products/products.js'])

    <script>
        window.productRoutes = {
            index: '{{ route('products.index') }}',
            edit: '{{ route('products.edit', ':id') }}',
            destroy: '{{ route('products.destroy', ':id') }}'
        };
    </script>
@endsection
