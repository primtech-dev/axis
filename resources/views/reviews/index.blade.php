@extends('layouts.vertical', ['title' => 'Reviews'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Reviews',
        'subTitle' => 'Moderasi review pelanggan'
    ])

    <div class="card">
        <div class="card-body">
            <table id="reviewsTable"
                   class="table table-striped dt-responsive w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Customer</th>
                        <th>No Order</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/reviews/index.js'])
    <script>
        window.reviewRoutes = {
            index: '{{ route('admin.reviews.index') }}',
            show: '{{ route('admin.reviews.show', ':id') }}',
            destroy: '{{ route('admin.reviews.destroy', ':id') }}'
        };
    </script>
@endsection
