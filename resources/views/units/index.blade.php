@extends('layouts.vertical', ['title' => 'Manajemen Unit'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Unit',
        'subTitle' => 'Kelola satuan produk',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => '#'],
            ['name' => 'Unit']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Unit</h5>
                    @can('units.create')
                        <a href="{{ route('units.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="me-1"></i> Tambah Unit
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="units-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th width="20%">Created At</th>
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
        id="deleteUnitModal"
        formId="deleteUnitForm"
        :route="route('units.destroy', ':id')"
        itemNameId="delete_unit_title"
        title="Konfirmasi Hapus Unit"
        message="Apakah Anda yakin ingin menghapus unit ini?"
        itemType="unit"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/units/units.js'])

    <script>
        window.unitRoutes = {
            index: '{{ route('units.index') }}',
            edit: '{{ route('units.edit', ':id') }}',
            destroy: '{{ route('units.destroy', ':id') }}'
        };
    </script>
@endsection
