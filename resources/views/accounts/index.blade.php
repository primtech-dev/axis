@extends('layouts.vertical', ['title' => 'Manajemen Akun'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Akun',
        'subTitle' => 'Kelola chart of accounts'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Akun</h5>
            @can('accounts.create')
                <a href="{{ route('accounts.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="accounts-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Akun</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-delete-modal
        id="deleteAccountModal"
        formId="deleteAccountForm"
        :route="route('accounts.destroy', ':id')"
        title="Hapus Akun"
        message="Yakin ingin menghapus akun ini?"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/accounts/accounts.js'])

    <script>
        window.accountRoutes = {
            index: '{{ route('accounts.index') }}',
            edit: '{{ route('accounts.edit', ':id') }}',
            destroy: '{{ route('accounts.destroy', ':id') }}'
        };
    </script>
@endsection
