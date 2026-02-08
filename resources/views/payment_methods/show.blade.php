@extends('layouts.vertical', ['title' => 'Detail Metode Pembayaran'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Metode Pembayaran',
        'subTitle' => 'Informasi metode pembayaran',
    ])

    <div class="card">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <strong>Kode</strong>
                    <div class="text-muted">{{ $method->code }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Nama</strong>
                    <div class="text-muted">{{ $method->name }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Kategori</strong>
                    <div class="text-muted text-capitalize">{{ $method->category }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Urutan</strong>
                    <div class="text-muted">{{ $method->sort_order }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Status</strong><br>
                    @if($method->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Non-aktif</span>
                    @endif
                </div>

                <div class="col-md-6">
                    <strong>Dibuat</strong>
                    <div class="text-muted">{{ $method->created_at?->format('Y-m-d H:i:s') }}</div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            @can('payment_methods.update')
                <a href="{{ route('payment-methods.edit', $method->id) }}" class="btn btn-primary">
                    <i data-lucide="edit"></i> Edit
                </a>
            @endcan
            <a href="{{ route('payment-methods.index') }}" class="btn btn-outline-secondary">
                <i data-lucide="arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
