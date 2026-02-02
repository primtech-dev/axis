@extends('layouts.vertical', ['title' => 'Manajemen Cabang'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Cabang',
        'subTitle' => 'Kelola data cabang',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => '#'],
            ['name' => 'Cabang']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Cabang</h5>
                    @can('branches.create')
                        <a href="{{ route('branches.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="me-1"></i> Tambah Cabang
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="branches-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode</th>
                            <th>Nama</th>
                            <th width="15%">Kota</th>
                            <th width="15%">Telepon</th>
                            <th width="10%">Status</th>
                            <th width="10%">Created At</th>
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
        id="deleteBranchModal"
        formId="deleteBranchForm"
        :route="route('branches.destroy', ':id')"
        itemNameId="delete_branch_title"
        title="Konfirmasi Hapus Cabang"
        message="Apakah Anda yakin ingin menghapus cabang ini?"
        itemType="cabang"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/branches/branches.js'])

    <script>
        window.branchRoutes = {
            index: '{{ route('branches.index') }}',
            edit: '{{ route('branches.edit', ':id') }}',
            destroy: '{{ route('branches.destroy', ':id') }}'
        };
    </script>
@endsection
