@extends('layouts.vertical', ['title' => 'Tambah Role'])

@section('styles')
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
        .permissions-list { max-height: 360px; overflow:auto; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Role',
        'subTitle' => 'Buat role baru dan atur permission',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('roles.index')],
            ['name' => 'Role', 'url' => route('roles.index')],
            ['name' => 'Tambah']
        ]
    ])

    <form action="{{ route('roles.store') }}" method="POST" id="roleForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Detail Role</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Role <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Contoh: admin, manager, editor.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions <small class="text-muted">(pilih minimal 1)</small></label>
                            <div class="mb-2">
                                <button id="btnToggleAllPerms" type="button" class="btn btn-sm btn-outline-secondary">Pilih / Batal Semua</button>
                            </div>

                            <div class="permissions-list border rounded p-2">
                                @foreach($permissionsGrouped ?? [] as $group => $perms)
                                    <div class="mb-2">
                                        <strong class="d-block mb-1">{{ $group }}</strong>
                                        <div class="row g-2">
                                            @foreach($perms as $perm)
                                                <div class="col-6 col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                                               name="permissions[]" value="{{ $perm->name }}" id="perm_{{ $perm->id }}"
                                                            {{ (is_array(old('permissions')) && in_array($perm->name, old('permissions'))) ? 'checked' : '' }}>
                                                        <label class="form-check-label small" for="perm_{{ $perm->id }}">
                                                            {{ $perm->display_name ?? $perm->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card mb-3 card-help">
                    <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
                    <div class="card-body">
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
                            <li>Role mengelompokkan permissions agar mudah assign ke user.</li>
                            <li>Beri nama role yang jelas dan singkat.</li>
                            <li>Jangan berikan permission berlebih pada role untuk keamanan.</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="plus" class="me-1"></i> Buat Role
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
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
    @vite(['resources/js/pages/settings/roles-form.js'])

    @if($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
