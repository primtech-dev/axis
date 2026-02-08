@extends('layouts.vertical', ['title' => 'Detail Produk'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Produk',
        'subTitle' => 'Informasi lengkap produk',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('products.index')],
            ['name' => 'Produk', 'url' => route('products.index')],
            ['name' => 'Detail']
        ]
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Informasi Produk</h5>
        </div>

        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <strong>SKU</strong>
                    <div class="text-muted">{{ $product->sku }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Nama Produk</strong>
                    <div class="text-muted">{{ $product->name }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Kategori</strong>
                    <div class="text-muted">{{ $product->category->name ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Unit</strong>
                    <div class="text-muted">{{ $product->unit->name ?? '-' }}</div>
                </div>

                <div class="col-md-6">
                    <strong>Harga Beli Default</strong>
                    <div class="text-muted">
                        Rp {{ number_format($product->price_buy_default, 2) }}
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Harga Jual Default</strong>
                    <div class="text-muted">
                        Rp {{ number_format($product->price_sell_default, 2) }}
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Status</strong>
                    <div>
                        @if($product->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Non-aktif</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Dibuat</strong>
                    <div class="text-muted">
                        {{ $product->created_at?->format('Y-m-d H:i:s') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <strong>Terakhir Diubah</strong>
                    <div class="text-muted">
                        {{ $product->updated_at?->format('Y-m-d H:i:s') }}
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex gap-2">
            @can('products.update')
                <a href="{{ route('products.edit', $product->id) }}"
                   class="btn btn-primary">
                    <i data-lucide="edit" class="me-1"></i> Edit
                </a>
            @endcan

            <a href="{{ route('products.index') }}"
               class="btn btn-outline-secondary">
                <i data-lucide="arrow-left" class="me-1"></i> Kembali
            </a>
        </div>
    </div>
@endsection
