@extends('layouts.vertical', ['title' => 'Edit Home Banner'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit Home Banner',
        'subTitle' => 'Perbarui banner homepage',
        'breadcrumbs' => [
            ['name' => 'Home Banner', 'url' => route('admin.home-banners.index')],
            ['name' => 'Edit']
        ]
    ])

    <form action="{{ route('admin.home-banners.update', $banner->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Kode Banner</label>
                            <input type="text"
                                   name="code"
                                   class="form-control"
                                   value="{{ old('code', $banner->code) }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text"
                                   name="title"
                                   class="form-control"
                                   value="{{ old('title', $banner->title) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link URL</label>
                            <input type="url"
                                   name="link_url"
                                   class="form-control"
                                   value="{{ old('link_url', $banner->link_url) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Desktop</label>
                            <input type="file" name="image" class="form-control">
                            @if($banner->image_url)
                                <img src="{{ $banner->image_url }}" class="img-fluid mt-2 rounded">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Banner Mobile</label>
                            <input type="file" name="image_mobile" class="form-control">
                            @if($banner->image_mobile_url)
                                <img src="{{ $banner->image_mobile_url }}" class="img-fluid mt-2 rounded">
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Urutan</label>
                            <input type="number"
                                   name="sort_order"
                                   class="form-control"
                                   value="{{ old('sort_order', $banner->sort_order) }}">
                        </div>

                        <div class="mb-3">
                            <label>Mulai</label>
                            <input type="datetime-local"
                                   name="start_at"
                                   class="form-control"
                                   value="{{ old('start_at', optional($banner->start_at)->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="mb-3">
                            <label>Akhir</label>
                            <input type="datetime-local"
                                   name="end_at"
                                   class="form-control"
                                   value="{{ old('end_at', optional($banner->end_at)->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="form-check">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   class="form-check-input"
                                {{ $banner->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Aktif</label>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body d-grid gap-2">
                        <button class="btn btn-primary">
                            <i data-lucide="save"></i> Update Banner
                        </button>
                        <a href="{{ route('admin.home-banners.index') }}"
                           class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>
@endsection
