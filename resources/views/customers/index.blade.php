@extends('layouts.vertical', ['title' => 'Manajemen Customer'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Customer',
        'subTitle' => 'Kelola data pelanggan'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Customer</h5>
            @can('customers.create')
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="customers-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-delete-modal
        id="deleteCustomerModal"
        formId="deleteCustomerForm"
        :route="route('customers.destroy', ':id')"
        title="Hapus Customer"
        message="Yakin ingin menghapus customer ini?"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/customers/customers.js'])

    <script>
        window.customerRoutes = {
            index: '{{ route('customers.index') }}',
            edit: '{{ route('customers.edit', ':id') }}',
            destroy: '{{ route('customers.destroy', ':id') }}',
            toggleActive: '{{ route('customers.toggleActive', ':id') }}'
        };
    </script>
@endsection
