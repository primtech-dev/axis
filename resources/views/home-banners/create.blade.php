@extends('layouts.vertical', ['title' => 'Tambah Home Banner'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Home Banner',
        'subTitle' => 'Banner utama di halaman homepage',
        'breadcrumbs' => [
            ['name' => 'Konten & Tampilan', 'url' => route('admin.home-banners.index')],
            ['name' => 'Home Banner', 'url' => route('admin.home-banners.index')],
            ['name' => 'Tambah']
        ]
    ])

    <form action="{{ route('admin.home-banners.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="homeBannerForm">

        @csrf

        <div class="row">

            <!-- MAIN -->
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Banner</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Kode Banner <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="HOME-01"
                                   required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul Banner</label>
                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   value="{{ old('title') }}"
                                   placeholder="Promo Akhir Tahun">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link Tujuan</label>
                            <input type="url"
                                   name="link_url"
                                   class="form-control"
                                   value="{{ old('link_url') }}"
                                   placeholder="https://example.com/promo">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Desktop <span class="text-danger">*</span></label>
                            <input type="file"
                                   name="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   accept="image/*"
                                   required>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Rekomendasi: 1920×600 px (WebP / JPG)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Mobile</label>
                            <input type="file"
                                   name="image_mobile"
                                   class="form-control"
                                   accept="image/*">
                            <small class="text-muted">Rekomendasi: 900×1200 px</small>
                        </div>

                    </div>
                </div>

            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Pengaturan</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Urutan Tampil</label>
                            <input type="number"
                                   name="sort_order"
                                   class="form-control"
                                   value="{{ old('sort_order', 0) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mulai Tampil</label>
                            <input type="datetime-local"
                                   name="start_at"
                                   class="form-control"
                                   value="{{ old('start_at') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Akhir Tampil</label>
                            <input type="datetime-local"
                                   name="end_at"
                                   class="form-control"
                                   value="{{ old('end_at') }}">
                        </div>

                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="form-check-input"
                                   checked>
                            <label class="form-check-label">Aktif</label>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body d-grid gap-2">
                        <button class="btn btn-primary">
                            <i data-lucide="save"></i> Simpan Banner
                        </button>
                        <a href="{{ route('admin.home-banners.index') }}"
                           class="btn btn-outline-secondary">
                            <i data-lucide="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>
@endsection
