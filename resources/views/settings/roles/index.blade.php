@extends('layouts.vertical', ['title' => 'Manajemen Role'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Role',
        'subTitle' => 'Kelola role dan hak akses',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => '#'],
            ['name' => 'Role & Permission']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Role</h5>
                    @can('roles.create')
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="me-1"></i> Tambah Role
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="roles-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Role</th>
                            <th>Permissions</th>
                            <th width="12%">Dibuat</th>
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
        id="deleteRoleModal"
        formId="deleteRoleForm"
        :route="route('roles.destroy', ':id')"
        itemNameId="delete_role_title"
        title="Konfirmasi Hapus Role"
        message="Apakah Anda yakin ingin menghapus role ini?"
        itemType="role"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/settings/roles.js'])

    <script>
        window.roleRoutes = {
            index: '{{ route('roles.index') }}',
            edit: '{{ route('roles.edit', ':id') }}',
            destroy: '{{ route('roles.destroy', ':id') }}'
        };
    </script>
@endsection
