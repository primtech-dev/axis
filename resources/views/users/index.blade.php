@extends('layouts.vertical', ['title' => 'Manajemen User'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen User',
        'subTitle' => 'Kelola akun pengguna',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => '#'],
            ['name' => 'User']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar User</h5>
                    @can('users.create')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="me-1"></i> Tambah User
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="users-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="10%">Status</th>
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
        id="deleteUserModal"
        formId="deleteUserForm"
        :route="route('users.destroy', ':id')"
        itemNameId="delete_user_title"
        title="Konfirmasi Hapus User"
        message="Apakah Anda yakin ingin menghapus user ini?"
        itemType="user"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/users/users.js'])

    <script>
        window.userRoutes = {
            index: '{{ route('users.index') }}',
            edit: '{{ route('users.edit', ':id') }}',
            destroy: '{{ route('users.destroy', ':id') }}'
        };
    </script>
@endsection
