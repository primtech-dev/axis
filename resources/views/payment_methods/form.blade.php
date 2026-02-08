@extends('layouts.vertical', ['title' => $mode === 'edit' ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => $mode === 'edit' ? 'Edit Metode Pembayaran' : 'Tambah Metode Pembayaran',
        'subTitle' => 'Form metode pembayaran',
    ])

    <form action="{{ $mode === 'edit'
        ? route('payment-methods.update', $method->id)
        : route('payment-methods.store') }}"
          method="POST" id="paymentMethodForm">

        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $method->code) }}"
                           placeholder="cash / qris / transfer_bca" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $method->name) }}"
                           placeholder="Cash / QRIS / Transfer BCA" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">— Pilih —</option>
                        @foreach(['cash','card','bank','digital'] as $cat)
                            <option value="{{ $cat }}"
                                {{ old('category', $method->category) === $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order"
                           class="form-control"
                           value="{{ old('sort_order', $method->sort_order) }}">
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox"
                               name="is_active" value="1"
                            {{ old('is_active', $method->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex gap-2">
                <button class="btn btn-primary" type="submit">
                    <i data-lucide="save"></i> Simpan
                </button>
                <a href="{{ route('payment-methods.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </form>
@endsection
