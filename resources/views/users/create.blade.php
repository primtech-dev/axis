@extends('layouts.vertical', ['title' => 'Tambah User'])

@section('styles')
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah User',
        'subTitle' => 'Buat akun pengguna baru',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('users.index')],
            ['name' => 'User', 'url' => route('users.index')],
            ['name' => 'Tambah']
        ]
    ])

    <form action="{{ route('users.store') }}" method="POST" id="userForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Detail User</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select">
                                <option value="">— Tidak ada —</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <select name="roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih role untuk assign (boleh lebih dari satu).</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3 card-help">
                    <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive" type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Aktif</label>
                        </div>

                        @can('users.update')
                            <div class="mb-3 form-check">
                                <input type="hidden" name="is_superadmin" value="0">
                                <input id="isSuperadmin" type="checkbox" name="is_superadmin" value="1" class="form-check-input" {{ old('is_superadmin') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isSuperadmin">Superadmin</label>
                            </div>
                        @endcan

                        <div class="mb-3">
                            <label class="form-label">Tanggal dibuat</label>
                            <input type="text" class="form-control" value="{{ now()->format('d M Y H:i') }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header"><h5 class="card-title mb-0">Tips</h5></div>
                    <div class="card-body small text-muted">
                        <ul>
                            <li>Gunakan email unik untuk login.</li>
                            <li>Berikan role minimal yang dibutuhkan (principle of least privilege).</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="plus" class="me-1"></i> Buat User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
    @vite(['resources/js/pages/users/users-form.js'])

    @if($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
