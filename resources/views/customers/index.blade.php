@extends('layouts.vertical', ['title' => 'Manajemen Pelanggan'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Pelanggan',
        'subTitle' => 'Daftar pelanggan',
        'breadcrumbs' => [
            ['name' => 'Pelanggan']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Pelanggan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="customers-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th width="10%">Status</th>
                            <th width="12%">Created At</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/customers/customers.js'])

    <script>
        window.customerRoutes = {
            index: '{{ route('customers.index') }}',
            show: '{{ route('customers.show', ':id') }}',
            toggleActive: '{{ route('customers.toggleActive', ':id') }}'
        };
    </script>
@endsection
