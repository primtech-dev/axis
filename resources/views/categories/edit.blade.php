@extends('layouts.vertical', ['title' => 'Edit Kategori'])

@section('styles')
    <style>
        .card-help { background:#fbfbfc; border:1px solid #eef2f6; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit Kategori',
        'subTitle' => 'Perbarui data kategori',
        'breadcrumbs' => [
            ['name' => 'Pengaturan', 'url' => route('categories.index')],
            ['name' => 'Kategori', 'url' => route('categories.index')],
            ['name' => 'Edit']
        ]
    ])

    <form action="{{ route('categories.update', $category->id) }}" method="POST" id="categoryForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Detail Kategori</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug <small class="text-muted">(opsional)</small></label>
                            <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $category->slug) }}">
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Parent Kategori</label>
                            <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">— Tidak ada —</option>
                                @foreach($parents ?? [] as $p)
                                    <option value="{{ $p->id }}" {{ (old('parent_id', $category->parent_id) == $p->id) ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Posisi (Urutan tampil)</label>
                            <input id="position" type="number" name="position" class="form-control @error('position') is-invalid @enderror"
                                   value="{{ old('position', $category->position) }}" min="0">
                            @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Kategori</label>
                            <input type="file" name="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                            @error('banner')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Rekomendasi ukuran: 1200×400 px (JPG / PNG)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alt Text Banner</label>
                            <input type="text" name="banner_alt" class="form-control"
                                   value="{{ old('banner_alt', $category->banner_alt ?? '') }}">
                            <small class="text-muted">Untuk SEO & accessibility</small>
                        </div>

                    </div>
                </div>

{{--                <div class="card mt-3">--}}
{{--                    <div class="card-header"><h5 class="card-title mb-0">Opsional: SEO / Deskripsi</h5></div>--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label class="form-label">Deskripsi Singkat</label>--}}
{{--                            <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-3 card-help">
                    <div class="card-header"><h5 class="card-title mb-0">Pengaturan</h5></div>
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input id="isActive" type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Aktif</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dibuat</label>
                            <input type="text" class="form-control" value="{{ $category->created_at ? $category->created_at->format('d M Y H:i') : '-' }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Terakhir diupdate</label>
                            <input type="text" class="form-control" value="{{ $category->updated_at ? $category->updated_at->format('d M Y H:i') : '-' }}" disabled>
                        </div>
                    </div>
                </div>

                <!-- Tips & Actions -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="card-title mb-0">Tips</h5></div>
                    <div class="card-body small text-muted">
                        <ul>
                            <li>Pastikan slug unik jika ingin URL rapi.</li>
                            <li>Jangan jadikan anak kategori sebagai parent dirinya sendiri.</li>
                            <li>Gunakan posisi untuk mengatur prioritas tampilan di storefront.</li>
                        </ul>
                    </div>
                </div>

                @if(!empty($category->banner_url))
                    <div class="mb-3">
                        <label class="form-label">Banner Saat Ini</label>
                        <div class="border rounded p-2">
                            <img src="{{ $category->banner_url }}"
                                 alt="{{ $category->banner_alt }}"
                                 class="img-fluid rounded">
                        </div>
                    </div>
                @endif

                @if($category->thumbnail_url)
                    <div class="mb-3">
                        <label class="form-label">Thumbnail Saat Ini</label>
                        <div class="border rounded p-2 d-inline-block">
                            <img src="{{ $category->thumbnail_url }}"
                                 alt="{{ $category->name }}"
                                 style="width:120px;height:auto"
                                 class="img-fluid rounded">
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
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
    @vite(['resources/js/pages/categories/categories-form.js'])

    @if($errors->any())
        <script>
            window.serverValidationErrors = {!! json_encode($errors->all()) !!};
        </script>
    @endif
@endsection
