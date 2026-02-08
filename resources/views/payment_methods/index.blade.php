@extends('layouts.vertical', ['title' => 'Manajemen Metode Pembayaran'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Metode Pembayaran',
        'subTitle' => 'Kelola metode pembayaran'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Metode Pembayaran</h5>
            @can('payment_methods.create')
                <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="payment-methods-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-delete-modal
        id="deletePaymentMethodModal"
        formId="deletePaymentMethodForm"
        :route="route('payment-methods.destroy', ':id')"
        title="Hapus Metode Pembayaran"
        message="Yakin ingin menghapus metode pembayaran ini?"
    />
@endsection

@section('scripts')
    @vite(['resources/js/pages/payment_methods/payment-methods.js'])

    <script>
        window.paymentMethodRoutes = {
            index: '{{ route('payment-methods.index') }}',
            edit: '{{ route('payment-methods.edit', ':id') }}',
            destroy: '{{ route('payment-methods.destroy', ':id') }}',
            toggleActive: '{{ route('payment-methods.toggleActive', ':id') }}'
        };
    </script>
@endsection
