@extends('layouts.vertical', ['title' => 'Manajemen Supplier'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Supplier',
        'subTitle' => 'Kelola data supplier'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Supplier</h5>
            @can('suppliers.create')
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="suppliers-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-delete-modal
        id="deleteSupplierModal"
        formId="deleteSupplierForm"
        :route="route('suppliers.destroy', ':id')"
        title="Hapus Supplier"
        message="Yakin ingin menghapus supplier ini?"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/suppliers/suppliers.js'])

    <script>
        window.supplierRoutes = {
            index: '{{ route('suppliers.index') }}',
            edit: '{{ route('suppliers.edit', ':id') }}',
            destroy: '{{ route('suppliers.destroy', ':id') }}',
            toggleActive: '{{ route('suppliers.toggleActive', ':id') }}'
        };
    </script>
@endsection
