@extends('layouts.vertical', ['title' => 'Manajemen Kategori'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Kategori',
        'subTitle' => 'Kelola kategori produk',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => '#'],
            ['name' => 'Kategori']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Kategori</h5>
                    @can('categories.create')
                        <a href="{{ route('categories.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="me-1"></i> Tambah Kategori
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="categories-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th width="8%">Thumbnail</th>
                            <th>Slug</th>
                            <th>Parent</th>
                            <th width="8%">Posisi</th>
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
        id="deleteCategoryModal"
        formId="deleteCategoryForm"
        :route="route('categories.destroy', ':id')"
        itemNameId="delete_category_title"
        title="Konfirmasi Hapus Kategori"
        message="Apakah Anda yakin ingin menghapus kategori ini?"
        itemType="kategori"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/categories/categories.js'])

    <script>
        window.categoryRoutes = {
            index: '{{ route('categories.index') }}',
            edit: '{{ route('categories.edit', ':id') }}',
            destroy: '{{ route('categories.destroy', ':id') }}'
        };
    </script>
@endsection
