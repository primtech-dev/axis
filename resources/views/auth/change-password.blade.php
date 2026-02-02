@extends('layouts.base', ['title' => 'Ubah Password'])

@section('content')
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row g-0 align-items-center min-vh-100">
                <div class="col-12">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="text-center mb-4">
                                        <h4 class="text-uppercase mt-0">Ubah Password</h4>
                                        <p class="text-muted">
                                            <i class="ti ti-alert-circle me-1"></i>
                                            Anda menggunakan password default. Harap ubah password untuk keamanan akun Anda.
                                        </p>
                                    </div>

                                    @if(session('warning'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <i class="ti ti-alert-triangle me-2"></i>
                                            {{ session('warning') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="ti ti-alert-circle me-2"></i>
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form action="{{ route('password.change.update') }}" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">
                                                Password Saat Ini <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                       class="form-control @error('current_password') is-invalid @enderror"
                                                       id="current_password"
                                                       name="current_password"
                                                       placeholder="Masukkan password saat ini"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                    <i class="ti ti-eye" id="current_password_icon"></i>
                                                </button>
                                                @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                Password Baru <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       id="password"
                                                       name="password"
                                                       placeholder="Masukkan password baru (min. 8 karakter)"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                    <i class="ti ti-eye" id="password_icon"></i>
                                                </button>
                                                @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="text-muted">Password minimal 8 karakter</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">
                                                Konfirmasi Password Baru <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password"
                                                       class="form-control"
                                                       id="password_confirmation"
                                                       name="password_confirmation"
                                                       placeholder="Masukkan ulang password baru"
                                                       required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                    <i class="ti ti-eye" id="password_confirmation_icon"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="alert alert-info">
                                                <h6 class="alert-heading"><i class="ti ti-info-circle me-1"></i> Ketentuan Password:</h6>
                                                <ul class="mb-0 ps-3">
                                                    <li>Minimal 8 karakter</li>
                                                    <li>Tidak boleh sama dengan password default</li>
                                                    <li>Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ti ti-lock me-1"></i> Ubah Password
                                            </button>
                                        </div>
                                    </form>

                                    <div class="text-center mt-3">
                                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-muted">
                                                <i class="ti ti-logout me-1"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                field.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }
    </script>
@endsection
