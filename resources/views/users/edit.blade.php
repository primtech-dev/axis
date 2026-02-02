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
            <!-- main -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Detail User</h5></div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak ingin mengganti)</small></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                <option value="">— Tidak ada —</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ (old('branch_id', $user->branch_id) == $b->id) ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Roles</label>
                            <select name="roles[]" class="form-select" multiple>
                                @foreach($roles as $role)
                                    @php
                                        $selected = false;
                                        if (is_array(old('roles'))) {
                                            $selected = in_array($role->name, old('roles'));
                                        } else {
                                            $selected = $user->hasRole($role->name);
                                        }
                                    @endphp
                                    <option value="{{ $role->name }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih satu atau beberapa role untuk user ini.</small>
                            @error('roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Optional: custom meta editor (json) -->
                        <div class="mb-3">
                            <label class="form-label">Meta (JSON) <small class="text-muted">(opsional)</small></label>
                            <textarea name="meta" rows="3" class="form-control">@if(old('meta')){{ old('meta') }}@else{{ json_encode($user->meta ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}@endif</textarea>
                            <small class="text-muted">Simpan data tambahan sebagai JSON. Kosongkan jika tidak perlu.</small>
                        </div>

                    </div>
                </div>

                <!-- Optional: Additional info card -->
                <div class="card mt-3">
                    <div class="card-header"><h5 class="card-title mb-0">Informasi Akun</h5></div>
                    <div class="card-body small text-muted">
                        <ul>
                            <li>Dibuat: {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}</li>
                            <li>Terakhir login: {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '-' }}</li>
                            <li>Terakhir diupdate: {{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- sidebar -->
            <div class="col-lg-4">
                <div class="card mb-3 card-help">
                    <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive" type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Aktif</label>
                        </div>

                        @can('users.update')
                            <div class="mb-3 form-check">
                                <input type="hidden" name="is_superadmin" value="0">
                                <input id="isSuperadmin" type="checkbox" name="is_superadmin" value="1" class="form-check-input" {{ old('is_superadmin', $user->is_superadmin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isSuperadmin">Superadmin</label>
                                <small class="d-block text-muted mt-1">Hati-hati: memberikan status superadmin memberikan akses penuh.</small>
                            </div>
                        @endcan

                        <div class="mb-3">
                            <label class="form-label">Tanggal dibuat</label>
                            <input type="text" class="form-control" value="{{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terakhir diupdate</label>
                            <input type="text" class="form-control" value="{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}" disabled>
                        </div>
                    </div>
                </div>

                <!-- tips -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="card-title mb-0">Tips</h5></div>
                    <div class="card-body small text-muted">
                        <ul>
                            <li>Jika tidak ingin mengganti password, biarkan kosong.</li>
                            <li>Berikan role paling sedikit (least privilege).</li>
                            <li>Jangan berikan status superadmin kecuali diperlukan.</li>
                        </ul>
                    </div>
                </div>

                <!-- actions -->
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
