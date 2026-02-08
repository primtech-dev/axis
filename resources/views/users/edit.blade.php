@extends('layouts.vertical', ['title' => 'Edit User'])

@section('styles')
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit User',
        'subTitle' => 'Perbarui data akun pengguna',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('users.index')],
            ['name' => 'User', 'url' => route('users.index')],
            ['name' => 'Edit']
        ]
    ])

    <form action="{{ route('users.update', $user->id) }}" method="POST" id="userForm">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- MAIN --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail User</h5>
                    </div>
                    <div class="card-body">

                        {{-- NAME --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Nama <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password
                                    <small class="text-muted">(kosongkan jika tidak ingin mengganti)</small>
                                </label>
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       autocomplete="new-password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Konfirmasi Password
                                </label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       autocomplete="new-password">
                            </div>
                        </div>

                        {{-- ROLE (SPATIE RELATION) --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>

                            <select
                                id="roleSelect"
                                name="roles[]"
                                class="form-select"
                                required
                            >
                                <option value="">— Pilih Role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('roles.0', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">
                                Setiap user hanya memiliki satu role.
                            </small>

                            @error('roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

                {{-- INFO (READ ONLY) --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Sistem</h5>
                    </div>
                    <div class="card-body small text-muted">
                        <ul class="mb-0">
                            <li>Dibuat: {{ $user->created_at?->format('Y-m-d H:i:s') ?? '-' }}</li>
                            <li>Terakhir diupdate: {{ $user->updated_at?->format('Y-m-d H:i:s') ?? '-' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <div class="card mb-3 card-help">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="form-check-input"
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="me-1"></i> Simpan Perubahan
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
