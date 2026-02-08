@extends('layouts.vertical', [
    'title' => $product->exists ? 'Edit Produk' : 'Tambah Produk'
])

@section('styles')
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => $product->exists ? 'Edit Produk' : 'Tambah Produk',
        'subTitle' => 'Form data produk',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('products.index')],
            ['name' => 'Produk', 'url' => route('products.index')],
            ['name' => $product->exists ? 'Edit' : 'Tambah']
        ]
    ])

    <form
        action="{{ $product->exists
            ? route('products.update', $product->id)
            : route('products.store') }}"
        method="POST"
        id="productForm"
    >
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Produk</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    {{-- SKU --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            SKU <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="sku"
                               class="form-control @error('sku') is-invalid @enderror"
                               value="{{ old('sku', $product->sku) }}"
                               required>
                        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nama Produk <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}"
                               required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- CATEGORY --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select id="categorySelect"
                                name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- UNIT --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Unit <span class="text-danger">*</span>
                        </label>
                        <select id="unitSelect"
                                name="unit_id"
                                class="form-select @error('unit_id') is-invalid @enderror"
                                required>
                            <option value="">— Pilih Unit —</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}"
                                    {{ old('unit_id', $product->unit_id) == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- HARGA BELI --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Beli Default</label>
                        <input type="number"
                               step="0.01"
                               name="price_buy_default"
                               class="form-control"
                               value="{{ old('price_buy_default', $product->price_buy_default ?? 0) }}">
                    </div>

                    {{-- HARGA JUAL --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Jual Default</label>
                        <input type="number"
                               step="0.01"
                               name="price_sell_default"
                               class="form-control"
                               value="{{ old('price_sell_default', $product->price_sell_default ?? 0) }}">
                    </div>

                    {{-- STATUS --}}
                    <div class="col-12 mt-2">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="form-check-input"
                                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Produk Aktif
                            </label>
                        </div>
                    </div>

                    {{-- INFO --}}
                    @if($product->exists)
                        <div class="col-12 mt-3 small text-muted">
                            Dibuat:
                            {{ $product->created_at?->format('Y-m-d H:i:s') }}
                            |
                            Terakhir diubah:
                            {{ $product->updated_at?->format('Y-m-d H:i:s') }}
                        </div>
                    @endif

                </div>
            </div>

            {{-- BUTTON KIRI --}}
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="{{ $product->exists ? 'save' : 'plus' }}" class="me-1"></i>
                    {{ $product->exists ? 'Simpan Perubahan' : 'Simpan Produk' }}
                </button>

                <a href="{{ route('products.index') }}"
                   class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i>
                    Kembali
                </a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/products/products-form.js'])

    @if($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
