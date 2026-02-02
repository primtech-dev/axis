@extends('layouts.vertical', ['title' => 'Manajemen Order'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Manajemen Order',
        'subTitle' => 'Kelola pesanan pelanggan',
        'breadcrumbs' => [
            ['name' => 'Penjualan', 'url' => '#'],
            ['name' => 'Order']
        ]
    ])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Order</h5>
                </div>

                <div class="card-body">
                    <table class="table table-striped dt-responsive align-middle w-100" id="orders-table">
                        <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th width="5%">No</th>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th width="12%">Total</th>
                            <th width="10%">Status</th>
                            <th width="12%">Placed At</th>
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
        id="deleteOrderModal"
        formId="deleteOrderForm"
        :route="route('admin.orders.destroy', ':id')"
        itemNameId="delete_order_title"
        title="Konfirmasi Hapus Order"
        message="Apakah Anda yakin ingin menghapus order ini?"
        itemType="order"
    />

    @include('orders._change_status_modal')
@endsection

@section('scripts')
    @vite(['resources/js/pages/orders/orders.js'])

    <script>
        window.orderRoutes = {
            index: '{{ route('admin.orders.index') }}',
            show: '{{ route('admin.orders.show', ':id') }}',
            destroy: '{{ route('admin.orders.destroy', ':id') }}'
        };
    </script>
@endsection
