@extends('layouts.vertical', ['title' => 'Tambah User'])

@section('styles')
{{--    @vite(['node_modules/select2/dist/css/select2.min.css'])--}}
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px 10px;
        }
        .select2-selection__rendered {
            line-height: 26px !important;
        }
        .select2-selection__arrow {
            height: 36px !important;
        }
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
            {{-- LEFT --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail User</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   required>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select
                                id="roleSelect"
                                name="roles[]"
                                class="form-select"
                                required
                            >
                                <option value="">— Pilih Role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('roles.0') === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="text-muted">
                                Setiap user hanya memiliki satu role utama.
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                <div class="card mb-3 card-help">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pengaturan</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive"
                                   type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="form-check-input"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Aktif
                            </label>
                        </div>

                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tips</h5>
                    </div>
                    <div class="card-body small text-muted">
                        <ul class="mb-0">
                            <li>Gunakan email unik untuk login.</li>
                            <li>Berikan role seminimal mungkin sesuai tugas.</li>
                            <li>Superadmin sebaiknya sangat dibatasi.</li>
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
