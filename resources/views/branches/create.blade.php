@extends('layouts.vertical', ['title' => 'Tambah Cabang'])

@section('styles')
    <style>
        /* Simple placeholder style (optional) */
        .map-placeholder {
            width: 100%;
            height: 180px;
            border: 2px dashed #dee2e6;
            border-radius: .375rem;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f8f9fa;
            color:#6c757d;
        }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Cabang',
        'subTitle' => 'Tambahkan cabang atau gudang baru.',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('branches.index')],
            ['name' => 'Cabang', 'url' => route('branches.index')],
            ['name' => 'Tambah']
        ]
    ])

    <form action="{{ route('branches.store') }}" method="POST" id="branchForm">
        @csrf

        <div class="row">
            <!-- Main -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Informasi Cabang</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kode Cabang <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $branch->code ?? '') }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Contoh: KNC-JKT1</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $branch->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $branch->address ?? '') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kota</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city', $branch->city ?? '') }}">
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Provinsi</label>
                                <input type="text" name="province" class="form-control @error('province') is-invalid @enderror"
                                       value="{{ old('province', $branch->province ?? '') }}">
                                @error('province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $branch->phone ?? '') }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude', $branch->latitude ?? '') }}">
                                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude', $branch->longitude ?? '') }}">
                                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header"><h5 class="card-title mb-0">Manager Cabang</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih Manager (Opsional)</label>
                            <select name="manager_admin_user_id" class="form-select @error('manager_admin_user_id') is-invalid @enderror">
                                <option value="">— Tidak ada —</option>
                                @foreach($admins ?? [] as $admin)
                                    <option value="{{ $admin->id }}" {{ old('manager_admin_user_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }} ({{ $admin->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_admin_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Tips & Actions -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="card-title mb-0">Tips</h5></div>
                    <div class="card-body small text-muted">
                        <ul>
                            <li>Gunakan kode unik (contoh: KNC-JKT1).</li>
                            <li>Isi alamat lengkap untuk memudahkan pengiriman.</li>
                            <li>Manager dapat diisi setelah membuat user admin.</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="plus" class="me-1"></i> Buat Cabang
                            </button>
                            <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">
                                <i data-lucide="arrow-left" class="me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/branches/branches-form.js'])

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.toast) {
                    @foreach($errors->all() as $error)
                    window.toast.error('{{ addslashes($error) }}');
                    @endforeach
                }
            });
        </script>
    @endif
@endsection
