@extends('layouts.vertical', ['title' => (isset($unit) && $unit->id) ? 'Edit Unit' : 'Tambah Unit'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => (isset($unit) && $unit->id) ? 'Edit Unit' : 'Tambah Unit',
        'subTitle' => 'Form unit',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('units.index')],
            ['name' => 'Unit', 'url' => route('units.index')],
            ['name' => (isset($unit) && $unit->id) ? 'Edit' : 'Tambah']
        ]
    ])

    <form action="{{ isset($unit) && $unit->id ? route('units.update', $unit->id) : route('units.store') }}" method="POST" id="unitForm">
        @csrf
        @if(isset($unit) && $unit->id)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Detail Unit</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kode <small class="text-muted">(opsional)</small></label>
                            <input id="code" type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $unit->code ?? '') }}">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Contoh: Pcs, Box, Kg — harus unik jika diisi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Unit <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $unit->name ?? '') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Contoh: Buah, Pcs, Liter.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3 card-help">
                    <div class="card-header"><h5 class="card-title mb-0">Info</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tanggal dibuat</label>
                            <input type="text" class="form-control" value="{{ isset($unit->created_at) ? $unit->created_at->format('d M Y H:i') : now()->format('d M Y H:i') }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="me-1"></i> Simpan
                            </button>
                            <a href="{{ route('units.index') }}" class="btn btn-outline-secondary">
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
    @vite(['resources/js/pages/units/units-form.js'])

    @if($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
