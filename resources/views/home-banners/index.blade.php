@extends('layouts.vertical', ['title' => 'Home Banner'])
@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Home Banner',
        'subTitle' => 'Kelola banner halaman utama'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Banner</h5>
            <a href="{{ route('admin.home-banners.create') }}" class="btn btn-primary">
                <i data-lucide="plus"></i> Tambah Banner
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped dt-responsive align-middle w-100" id="homeBannersTable">
                <thead class="thead-sm text-uppercase fs-xxs">
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Banner</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th width="8%">Urutan</th>
                    <th width="10%">Status</th>
                    <th width="12%" class="text-center">Aksi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/home-banners/index.js'])

    <script>
        window.bannerRoutes = {
            index: '{{ route('admin.home-banners.index') }}',
            edit: '{{ route('admin.home-banners.edit', ':id') }}',
            destroy: '{{ route('admin.home-banners.destroy', ':id') }}'
        };
    </script>
@endsection
