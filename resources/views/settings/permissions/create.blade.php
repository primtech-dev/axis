@extends('layouts.vertical', ['title' => 'Tambah Permission'])

@section('styles')
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Permission',
        'subTitle' => 'Buat permission baru',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('permissions.index')],
            ['name' => 'Permission', 'url' => route('permissions.index')],
            ['name' => 'Tambah']
        ]
    ])

    <form action="{{ route('permissions.store') }}" method="POST" id="permissionForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Detail Permission</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama (slug) <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Contoh: users.create, categories.edit</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input id="display_name" type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                                   value="{{ old('display_name') }}">
                            @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Judul human-friendly, misal "Buat User".</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Group / Kategori Permission</label>
                            <input id="group" type="text" name="group" class="form-control @error('group') is-invalid @enderror"
                                   value="{{ old('group') }}">
                            @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Kelompokkan permission (User, Produk, Order).</small>
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
                            <li>Gunakan pola nama konsisten: resource.action (users.create).</li>
                            <li>Group memudahkan saat assign permission ke role.</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="plus" class="me-1"></i> Buat Permission
                            </button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
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
    @vite(['resources/js/pages/settings/permissions-form.js'])

    @if($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
