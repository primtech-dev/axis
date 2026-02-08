@extends('layouts.vertical', [
    'title' => isset($supplier) && $supplier->id ? 'Edit Supplier' : 'Tambah Supplier',
])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => isset($supplier) && $supplier->id ? 'Edit Supplier' : 'Tambah Supplier',
        'subTitle' => 'Form data supplier',
        'breadcrumbs' => [
            ['name' => 'Master Data', 'url' => route('suppliers.index')],
            ['name' => 'Supplier', 'url' => route('suppliers.index')],
            ['name' => isset($supplier) && $supplier->id ? 'Edit' : 'Tambah'],
        ],
    ])

    <form
        action="{{ isset($supplier) && $supplier->id ? route('suppliers.update', $supplier->id) : route('suppliers.store') }}"
        method="POST" id="supplierForm">
        @csrf
        @if (isset($supplier) && $supplier->id)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Supplier</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    {{-- KODE --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Kode Supplier <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $supplier->code ?? '') }}" placeholder="SUP-001" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nama Supplier <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $supplier->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Tipe Supplier <span class="text-danger">*</span>
                        </label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="PURCHASE"
                                {{ old('type', $supplier->type ?? '') === 'PURCHASE' ? 'selected' : '' }}>
                                Pembelian
                            </option>
                            <option value="EXPENSE"
                                {{ old('type', $supplier->type ?? '') === 'EXPENSE' ? 'selected' : '' }}>
                                Biaya
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PHONE --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $supplier->phone ?? '') }}" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6 mb-3 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive" type="checkbox" name="is_active" value="1" class="form-check-input"
                                {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Supplier Aktif
                            </label>
                        </div>
                    </div>

                    {{-- ALAMAT --}}
                    <div class="col-12 mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- INFO (EDIT ONLY) --}}
                    @if (isset($supplier) && $supplier->id)
                        <div class="col-12 mt-2">
                            <small class="text-muted">
                                Dibuat:
                                {{ $supplier->created_at?->format('Y-m-d H:i:s') ?? '-' }}
                                &nbsp;|&nbsp;
                                Terakhir diubah:
                                {{ $supplier->updated_at?->format('Y-m-d H:i:s') ?? '-' }}
                            </small>
                        </div>
                    @endif

                </div>
            </div>

            {{-- BUTTON KIRI --}}
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="me-1"></i>
                    {{ isset($supplier) && $supplier->id ? 'Simpan Perubahan' : 'Simpan Supplier' }}
                </button>

                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i> Kembali
                </a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/suppliers/suppliers-form.js'])

    @if ($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
