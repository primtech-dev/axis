@extends('layouts.vertical', ['title' => 'Detail Produk'])

@section('styles')
    <style>
        /* layout utama */
        .product-main {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .product-main .product-image {
            flex: 0 0 260px;
            max-width: 260px;
            width: 100%;
        }

        .product-main .product-image img {
            display: block;
            width: 100%;
            height: auto;
            max-height: 340px;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-main .product-content {
            flex: 1 1 auto;
            min-width: 0; /* critical: allow text wrapping inside flex */
        }

        /* product gallery & thumbs */
        .product-gallery {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .product-gallery img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .image-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        /* badges & meta */
        .badge-cat {
            background: #f1f5f9;
            color: #111827;
            border-radius: 6px;
            padding: 0.35rem 0.6rem;
            margin-right: 6px;
            display: inline-block;
        }

        .meta {
            font-size: 0.95rem;
            color: #6b7280;
        }

        /* table tweaks */
        .variant-table th, .variant-table td {
            vertical-align: middle;
        }

        .variant-table td img {
            border-radius: 6px;
        }

        /* card visuals for variant rows, gallery items */
        .gallery-item.card {
            border: 1px solid #eef2f7;
            box-shadow: none;
        }

        .stacked-cards > .card {
            margin-bottom: 1rem;
        }

        /* controls spacing */
        .product-actions .btn {
            min-width: 120px;
        }

        /* responsive: stack product main on small screens */
        @media (max-width: 991.98px) {
            .product-main {
                flex-direction: column;
            }

            .product-main .product-image {
                flex: 0 0 auto;
                max-width: 100%;
                width: 200px;
                margin: 0 auto 1rem auto;
            }
        }

        /* small helpers */
        .muted-small {
            font-size: .92rem;
            color: #6b7280;
        }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Produk',
        'subTitle' => 'Lihat detail lengkap produk',
        'breadcrumbs' => [
            ['name' => 'Produk', 'url' => route('products.index')],
            ['name' => $product->name]
        ]
    ])

    @php
        use Illuminate\Support\Facades\Storage;

        // safe-get main image
        $mainImage = null;
        if (isset($product->images) && $product->images->count()) {
            $mainImage = $product->images->firstWhere('is_main', true) ?? $product->images->first();
        }
        $mainUrl = null;
        if ($mainImage && !empty($mainImage->url) && Storage::disk('public')->exists($mainImage->url)) {
            $mainUrl = Storage::disk('public')->url($mainImage->url);
        }

        // helper formatting price (assume price stored as integer full IDR)
        $fmtPrice = function($price) {
            if ($price === null || $price === '') return '-';
            return 'Rp ' . number_format((int)$price, 0, ',', '.');
        };
    @endphp

    <div class="row">
        <div class="col-12">
            <!-- Main product card (full width) -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="product-main">
                        <div class="product-image">
                            <img src="{{ $mainUrl ?? asset('images/placeholder-300x300.png') }}"
                                 alt="{{ $product->name }}">
                        </div>

                        <div class="product-content">
                            <h3 class="mb-1">{{ $product->name }}</h3>

                            <div class="meta mb-2">
                                SKU: <strong>{{ $product->sku ?? '-' }}</strong>
                                &nbsp;&mdash;&nbsp;
                                Status: <strong>{{ $product->is_active ? 'Aktif' : 'Non-aktif' }}</strong>
                            </div>

                            <div class="meta mb-2">
                                Satuan: <strong>{{ $product->unit ? ($product->unit->code ? $product->unit->code.' • ' : '') . $product->unit->name : '-' }}</strong>
                            </div>

                            <div class="mb-2">
                                <strong>Kategori:</strong>
                                @if($product->categories && $product->categories->count())
                                    @foreach($product->categories as $c)
                                        <span class="badge-cat">{{ $c->name }}</span>
                                    @endforeach
                                @else
                                    <span class="meta">— Tidak dikategorikan —</span>
                                @endif
                            </div>
                            <br>
                            <div class="mb-3">
                                <h4>Deskripsi singkat</h4>
                                <div class="">{!! $product->short_description ?? '-' !!}</div>
                            </div>

                            <div class="mb-3">
                                <h4>Deskripsi lengkap</h4>
                                <div class="">{!! $product->description ?? '-' !!}</div>
                            </div>

                            <div class="d-flex gap-2 product-actions">
                                @can('products.update')
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                                        <i data-lucide="edit" class="me-1"></i> Edit
                                    </a>
                                @endcan

                                @can('products.delete')
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger">Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div> <!-- .product-main -->
                </div>
            </div>

            <!-- Gallery card -->
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Galeri Gambar</h5></div>
                <div class="card-body">
                    <div class="product-gallery" id="productGallery" data-product-id="{{ $product->id }}">
                        @forelse($product->images->sortBy('position') as $img)
                            @php
                                $url = null;
                                if (!empty($img->url) && Storage::disk('public')->exists($img->url)) {
                                    $url = Storage::disk('public')->url($img->url);
                                }
                            @endphp

                            <div class="gallery-item card p-2" data-id="{{ $img->id }}"
                                 style="width:160px; position:relative;">
                                <div class="drag-handle" style="cursor:grab; position:absolute; right:6px; top:6px;">
                                    <i data-lucide="move" class="text-muted"></i>
                                </div>

                                @if($url)
                                    <div style="min-height:110px;"
                                         class="d-flex align-items-center justify-content-center">
                                        <img src="{{ $url }}" class="w-100"
                                             style="height:110px;object-fit:cover;border-radius:6px;">
                                    </div>
                                @else
                                    <div
                                        class="image-thumb d-flex align-items-center justify-content-center bg-light text-muted">
                                        Tidak ditemukan
                                    </div>
                                @endif

                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Pos: <span
                                            class="img-pos">{{ $img->position }}</span></small>

                                    @if($img->is_main)
                                        <span class="badge bg-success">Utama</span>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary js-set-main"
                                                data-id="{{ $img->id }}">Set Utama
                                        </button>
                                    @endif
                                </div>

                                <div class="mt-2 d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-danger js-delete-image"
                                            data-id="{{ $img->id }}"><i class="ti ti-trash"></i></button>
                                </div>
                            </div>
                        @empty
                            <div class="meta">Belum ada gambar.</div>
                        @endforelse
                    </div>

                    <p class="small text-muted mt-2">Tarik dan lepaskan gambar untuk mengubah urutan. Klik "Set Utama"
                        untuk menandai gambar utama.</p>
                </div>
            </div>

            <!-- Variants card -->
            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0">Variants</h5></div>
                <div class="card-body">
                    @if($product->variants && $product->variants->count())
                        <div class="table-responsive">
                            <table class="table table-sm variant-table">
                                <thead>
                                <tr>
                                    <th style="width:40px;">#</th>
                                    <th>Nama Varian</th>
                                    <th style="width:140px;">SKU</th>
                                    <th style="width:150px;">Harga</th>
                                    <th style="min-width:160px;">Dimensi (P×L×T cm)</th>
                                    <th style="width:180px;">Gambar</th>
                                    <th style="width:120px;">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($product->variants as $i => $v)
                                    @php
                                        $v_images = $v->images ?? collect();
                                    @endphp
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $v->variant_name ?? '-' }}</td>
                                        <td>{{ $v->sku ?? '-' }}</td>
                                        <td>{{ $fmtPrice($v->price) }}</td>
                                        <td>{{ $v->length ?? '-' }} × {{ $v->width ?? '-' }}
                                            × {{ $v->height ?? '-' }}</td>

                                        <td>
                                            @if($v_images && count($v_images))
                                                <div class="d-flex gap-1">
                                                    @foreach(collect($v_images)->take(3) as $vi)
                                                        @php
                                                            $vurl = null;
                                                            $path = is_array($vi) ? ($vi['url'] ?? null) : ($vi->url ?? null);
                                                            if ($path && Storage::disk('public')->exists($path)) $vurl = Storage::disk('public')->url($path);
                                                        @endphp
                                                        @if($vurl)
                                                            <img src="{{ $vurl }}" class="image-thumb"
                                                                 alt="varian image">
                                                        @else
                                                            <div
                                                                class="image-thumb d-flex align-items-center justify-content-center bg-light text-muted">
                                                                -
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="meta">—</span>
                                            @endif
                                        </td>

                                        <td>{{ $v->is_active ? 'Aktif' : 'Non-aktif' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="meta">Belum ada varian untuk produk ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stacked cards: meta & quick actions below main content -->
    <div class="row">
        <div class="col-12 stacked-cards">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Meta</h6></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Dibuat</strong></p>
                    <p class="meta">{{ $product->created_at ? $product->created_at->format('d M Y H:i') : '-' }}</p>

                    <p class="mb-1"><strong>Terakhir diupdate</strong></p>
                    <p class="meta">{{ $product->updated_at ? $product->updated_at->format('d M Y H:i') : '-' }}</p>

                    <p class="mb-1"><strong>Berat</strong></p>
                    <p class="meta">{{ $product->weight_gram ? $product->weight_gram . ' gr' : '-' }}</p>

                    <p class="mb-1"><strong>Atribut (JSON)</strong></p>
                    <pre class="small text-muted"
                         style="white-space:pre-wrap;">{!! json_encode($product->attributes ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}</pre>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex gap-2">
                    @can('products.update')
                        <a href="{{ route('products.edit', $product->id) }}"
                           class="btn btn-block btn-outline-primary w-100">Edit Produk</a>
                    @endcan
                    <a href="{{ route('products.index') }}" class="btn btn-block btn-outline-secondary w-100">Kembali ke
                        daftar</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @vite(['resources/js/pages/products/images-order.js'])

    <script>
        // small client-side helpers: bind delete and set-main if not already bound in images-order.js
        document.addEventListener('DOMContentLoaded', function () {
            // delete image (delegated)
            document.querySelectorAll('.js-delete-image').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.id;
                    if (!confirm('Hapus gambar ini?')) return;
                    fetch(`/products/images/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(resp => {
                        if (resp && resp.success) location.reload();
                        else alert('Gagal menghapus gambar.');
                    }).catch(() => alert('Gagal menghapus (server error)'));
                });
            });

            // // set main
            // document.querySelectorAll('.js-set-main').forEach(btn => {
            //     btn.addEventListener('click', function() {
            //         const id = this.dataset.id;
            //         if (!confirm('Jadikan gambar ini utama?')) return;
            //         fetch(`/products/images/${id}/set-main`, {
            //             method: 'POST',
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            //                 'Accept': 'application/json'
            //             }
            //         }).then(r => r.json()).then(resp => {
            //             if (resp && resp.success) location.reload();
            //             else alert('Gagal set utama.');
            //         }).catch(() => alert('Gagal set utama (server error)'));
            //     });
            // });
        });
    </script>
@endsection
