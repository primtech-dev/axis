@extends('layouts.vertical', [
    'title' => isset($account) && $account->id ? 'Edit Akun' : 'Tambah Akun',
])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => isset($account) && $account->id ? 'Edit Akun' : 'Tambah Akun',
        'subTitle' => 'Form master akun',
    ])

    <form
        action="{{ isset($account) && $account->id ? route('accounts.update', $account->id) : route('accounts.store') }}"
        method="POST" id="accountForm">
        @csrf
        @if (isset($account) && $account->id)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detail Akun</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    {{-- KODE --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Kode Akun <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $account->code ?? '') }}" placeholder="1101" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NAMA --}}
                    <div class="col-md-5 mb-3">
                        <label class="form-label">
                            Nama Akun <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $account->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TIPE --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">
                            Tipe Akun <span class="text-danger">*</span>
                        </label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach (['ASSET', 'LIABILITY', 'EQUITY', 'INCOME', 'EXPENSE'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('type', $account->type ?? '') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6 mt-2">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                {{ old('is_active', $account->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                Akun Aktif
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="me-1"></i>
                    Simpan
                </button>

                <a href="{{ route('accounts.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i> Kembali
                </a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/accounts/accounts-form.js'])
@endsection
