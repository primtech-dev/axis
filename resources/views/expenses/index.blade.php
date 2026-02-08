@extends('layouts.vertical', ['title' => 'Biaya Pembelian'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Biaya',
        'subTitle' => 'Biaya tambahan pembelian'
    ])

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Daftar Biaya</h5>

            @can('expenses.create')
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                    <i data-lucide="plus"></i> Tambah
                </a>
            @endcan
        </div>

        <div class="card-body">
            <table id="expenses-table" class="table table-striped w-100">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pembelian</th>
                    <th>Supplier</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/expenses/expenses.js'])

    <script>
        window.expenseRoutes = {
            index: '{{ route('expenses.index') }}',
            show: '{{ route('expenses.show', ':id') }}',
        };
    </script>
@endsection
