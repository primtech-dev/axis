@extends('layouts.vertical', ['title' => $product->exists ? 'Edit Produk' : 'Tambah Produk'])

@section('styles')
    <style>
        .variant-row { border:1px dashed #e9ecef; padding:10px; margin-bottom:8px; border-radius:6px;}
        .image-thumb { width:100px; height:100px; object-fit:cover; border-radius:4px; }
        .wizard-tabs .nav-link { padding: .75rem 1rem; }
        .wizard-tabs .nav-link .fs-32 { font-size: 1.6rem; opacity: .85; }
    </style>

    <!-- Select2 CSS (keep) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <script>
        console.log('products-form.js loaded @', new Date().toISOString());
    </script>
    @include('layouts.shared.page-title', [
        'title' => $product->exists ? 'Edit Produk' : 'Tambah Produk',
        'subTitle' => 'Produk: detail → kategori → varian → gambar → publish',
        'breadcrumbs' => [
            ['name' => 'Produk', 'url' => route('products.index')],
            ['name' => $product->exists ? 'Edit' : 'Tambah']
        ]
    ])

    <form action="{{ $product->exists ? route('products.update', $product->id) : route('products.store') }}"
          method="POST"
          id="productForm"
          enctype="multipart/form-data">
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="card">
            <div class="card-header justify-content-between d-flex align-items-center">
                <h5 class="card-title mb-0">Create Product</h5>
                <span class="badge badge-soft-success badge-label fs-xxs py-1">Product</span>
            </div>

            <div class="card-body">
                <div class="ins-wizard" data-wizard>
                    <!-- Navigation Tabs (wizard look) -->
                    <ul class="nav nav-tabs wizard-tabs" data-wizard-nav role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tabDetail">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-package fs-32"></i>
                                    <span class="flex-grow-1 ms-2 text-truncate">
                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Detail</span>
                                        <span class="mb-0 fw-normal">Nama & deskripsi</span>
                                    </span>
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tabCategories">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-list-details fs-32"></i>
                                    <span class="flex-grow-1 ms-2 text-truncate">
                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Kategori</span>
                                        <span class="mb-0 fw-normal">Pilih kategori</span>
                                    </span>
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tabVariants">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-stack fs-32"></i>
                                    <span class="flex-grow-1 ms-2 text-truncate">
                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Varian</span>
                                        <span class="mb-0 fw-normal">SKU, harga, dimensi</span>
                                    </span>
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tabImages">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-photo fs-32"></i>
                                    <span class="flex-grow-1 ms-2 text-truncate">
                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Gambar</span>
                                        <span class="mb-0 fw-normal">Upload gambar produk & varian</span>
                                    </span>
                                </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tabPublish">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-send fs-32"></i>
                                    <span class="flex-grow-1 ms-2 text-truncate">
                                        <span class="mb-0 lh-base d-block fw-semibold text-body fs-base">Publish</span>
                                        <span class="mb-0 fw-normal">Simpan produk</span>
                                    </span>
                                </span>
                            </a>
                        </li>
                    </ul>

                    <!-- Content -->
                    <div class="tab-content pt-3" data-wizard-content>

                        <!-- Step 1: Detail -->
                        <div class="tab-pane fade show active" id="tabDetail">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">SKU <small class="text-muted">(opsional)</small></label>
                                        <input type="text" name="sku" class="form-control select2" value="{{ old('sku', $product->sku) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Satuan / Unit <small class="text-muted">(opsional)</small></label>
                                        <select name="unit_id" id="unitSelect" class="form-select">
                                            <option value="">— Pilih Satuan —</option>
                                            @foreach($units ?? [] as $u)
                                                <option value="{{ $u->id }}" {{ (string) old('unit_id', $product->unit_id ?? '') === (string) $u->id ? 'selected' : '' }}>
                                                    {{ $u->code ? $u->code . ' • ' : '' }}{{ $u->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih satuan produk (mis. Pcs, Kg). Varian akan mengikuti satuan produk.</small>
                                    </div>


                                    {{--                                    <div class="mb-3">--}}
{{--                                        <label class="form-label">Deskripsi Singkat</label>--}}
{{--                                        <textarea name="short_description" rows="3" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>--}}
{{--                                    </div>--}}

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">
                                            Deskripsi Singkat <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('short_description') is-invalid @enderror"
                                                  id="short_description"
                                                  name="short_description"
                                                  required>{{ old('short_description', $product->short_description) }}</textarea>
                                        @error('short_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">
                                            Deskripsi Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description"
                                                  name="description"
                                                  required>{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

{{--                                    <div class="mb-3">--}}
{{--                                        <label class="form-label">Deskripsi Lengkap</label>--}}
{{--                                        <textarea name="description" rows="6" class="form-control">{{ old('description', $product->description) }}</textarea>--}}
{{--                                    </div>--}}

                                    <div class="mb-3">
                                        <label class="form-label">Atribut (JSON)</label>
                                        <textarea name="attributes_json" rows="3" class="form-control">{{ old('attributes_json', json_encode($product->attributes ?? [])) }}</textarea>
                                        <small class="text-muted">Contoh: {"material":"kain","warna":"putih"}</small>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="card card-help mb-3">
                                        <div class="card-body">
                                            <div class="mb-3 form-check">
                                                <input type="hidden" name="is_active" value="0">
                                                <input id="isActive" type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="isActive">Aktif</label>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Berat (gram)</label>
                                                <input type="number" name="weight_gram" class="form-control" value="{{ old('weight_gram', $product->weight_gram) }}">
                                            </div>

                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" class="btn btn-primary" data-wizard-next>Next: Kategori →</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Categories -->
                        <div class="tab-pane fade" id="tabCategories">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori (multi)</label>
                                        <select name="categories[]" id="categoriesSelect" class="form-select" multiple>
                                            @foreach($categories as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ in_array($c->id, old('categories', $product->categories->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                                    {{ $c->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih 1 atau lebih kategori.</small>
                                    </div>
                                </div>

                                <div class="col-lg-4 d-flex flex-column justify-content-between">
                                    <div></div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-secondary" data-wizard-prev>← Back</button>
                                        <button type="button" class="btn btn-primary" data-wizard-next>Next: Varian →</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Variants -->
                        <div class="tab-pane fade" id="tabVariants">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <h6>Variants</h6>
                                <button type="button" id="btn-add-variant" class="btn btn-sm btn-primary">Tambah Varian</button>
                            </div>

                            <div id="variantsContainer">
                                @php
                                    // Prefer old input (when validation failed), else use model variants
                                    $rawVariants = old('variants');
                                    if (is_null($rawVariants)) {
                                        // Convert Eloquent collection -> array of arrays
                                        $rawVariants = $product->variants ? $product->variants->toArray() : [];
                                    }
                                @endphp

                                @foreach($rawVariants as $i => $v)
                                    {{-- ensure $v is always an object for uniform access in partial --}}
                                    @include('products._variant_row', ['index' => $i, 'variant' => is_object($v) ? $v : (object)$v])
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" data-wizard-prev>← Back</button>
                                <button type="button" class="btn btn-primary" data-wizard-next>Next: Gambar →</button>
                            </div>
                        </div>

                        <!-- Step 4: Images -->
                        <div class="tab-pane fade" id="tabImages">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Gambar Produk (multiple)</label>
                                        <input type="file" id="productImagesInput" name="product_images[]" class="form-control" multiple accept="image/*" />
                                        <small class="text-muted">Selain gambar global, setiap varian juga punya field gambar di varian.</small>
                                        <small class="text-muted">Maksimal ukuran gambar 3MB (JPG / PNG / WebP)</small>
                                    </div>

                                    <div id="imagePreview" class="d-flex flex-wrap gap-2">
                                        @foreach($product->images ?? [] as $img)
                                            <div class="position-relative">
                                                <img src="{{ $img->url ? asset('storage/'.$img->url) : '' }}" class="image-thumb" alt="">
                                                <button type="button" class="btn btn-sm btn-danger js-delete-image" data-id="{{ $img->id }}" style="position:absolute; right:5px; top:5px;"><i class="ti ti-trash"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-lg-4 d-flex flex-column justify-content-between">
                                    <div></div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-secondary" data-wizard-prev>← Back</button>
                                        <button type="button" class="btn btn-primary" data-wizard-next>Next: Publish →</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Publish -->
                        <!-- Step 5: Publish (Review yang rapi & dinamis) -->
                        <div class="tab-pane fade" id="tabPublish">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h6>Review & Publish</h6>
                                    <p class="small text-muted">Periksa kembali data sebelum menyimpan. Informasi di bawah ini berasal dari form — pastikan sudah benar.</p>

                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="mb-2">Informasi Utama</h5>
                                            <dl class="row mb-0">
                                                <dt class="col-sm-3">Nama</dt>
                                                <dd class="col-sm-9" id="review_name">-</dd>

                                                <dt class="col-sm-3">SKU</dt>
                                                <dd class="col-sm-9" id="review_sku">-</dd>

                                                <dt class="col-sm-3">Satuan</dt>
                                                <dd class="col-sm-9" id="review_unit">-</dd>

                                                <dt class="col-sm-3">Kategori</dt>
                                                <dd class="col-sm-9" id="review_categories">-</dd>

                                                <dt class="col-sm-3">Berat (gram)</dt>
                                                <dd class="col-sm-9" id="review_weight">-</dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="mb-2">Deskripsi Singkat</h5>
                                            <div id="review_short_description" class="small text-muted">-</div>
                                            <hr>
                                            <h5 class="mb-2">Deskripsi Lengkap</h5>
                                            <div id="review_description" class="small text-muted">-</div>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="mb-2">Varian</h5>
                                            <div id="review_variants">
                                                <p class="text-muted small mb-0">Belum ada varian.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="mb-2">Preview Gambar</h5>
                                            <div id="review_images" class="d-flex flex-wrap gap-2">
                                                <p class="text-muted small mb-0">Belum ada gambar.</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-lg-4 d-flex flex-column justify-content-between">
                                    <div></div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-secondary" data-wizard-prev>← Back</button>
                                        <button type="submit" class="btn btn-success">Simpan Produk</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div> <!-- tab-content -->
                </div> <!-- ins-wizard -->
            </div> <!-- card-body -->
        </div> <!-- card -->
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/products/products-form.js'])

    @if($errors->any())
        <script>window.serverValidationErrors = {!! json_encode($errors->all()) !!};</script>
    @endif
    {{-- NOTE: do NOT include select2 script tag here — JS will load it dynamically. --}}
@endsection
