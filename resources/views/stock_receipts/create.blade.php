@extends('layouts.vertical', ['title' => 'Tambah Stok Masuk'])

@section('styles')
    {{-- Pastikan Select2/TomSelect CSS sudah di-include via Vite atau CDN --}}
    <style>
        /* Make card take full available width and better spacing on small screens */
        .item-row .form-label { font-size: .9rem; }
        .item-row .variant-select { width: 100% !important; }
        /* Slight visual tweak to separate items */
        #items-wrapper .item-row { border: 1px solid #eef2f6; border-radius: .375rem; padding: .5rem; background: #fff; }
        @media (max-width: 576px) {
            /* Stacked layout on very small screens */
            .item-row .col-md-5,
            .item-row .col-md-2,
            .item-row .col-md-3,
            .item-row .col-md-4,
            .item-row .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .item-row .text-end { text-align: right; margin-top: .5rem; }
        }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Stok Masuk',
        'subTitle' => 'Masukkan penerimaan barang',
        'breadcrumbs' => [['name'=>'Persediaan','url'=>route('stock_receipts.index')], ['name'=>'Tambah']]
    ])

    <form action="{{ route('stock_receipts.store') }}" method="POST" id="stockReceiptForm">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Informasi Penerimaan</h5>
                        <div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-device-floppy me-1"></i> Simpan
                            </button>
                            <a href="{{ route('stock_receipts.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-arrow-left me-1"></i> Batal
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <label class="form-label">Cabang <span class="text-danger">*</span></label>
                                <select name="branch_id" id="branch_id" class="form-select select2" data-no-placeholder="1" data-allow-clear="false" required>
                                    <option value="">— Pilih cabang —</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}" {{ old('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="received" {{ old('status')=='received' ? 'selected' : '' }}>Received</option>
                                    <option value="draft" {{ old('status')=='draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Supplier</label>
                                <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name') }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Reference No (PO/Delivery)</label>
                                <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no') }}">
                            </div>
                        </div>

                        <hr class="my-3">

                        <h6 class="mb-2">Items</h6>
                        <div id="items-wrapper" class="mb-2">
                            {{-- JS akan menambahkan rows disini --}}
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" id="btnAddItem" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-plus me-1"></i> Tambah Item
                            </button>

                            <button type="button" id="btnAddItemCopyLast" class="btn btn-sm btn-outline-secondary" title="Copy item terakhir">
                                <i class="ti ti-copy me-1"></i> Duplikat Item Terakhir
                            </button>
                        </div>

                        {{-- Catatan dipindah ke bawah form sesuai permintaan --}}
                        <div class="card mt-4">
                            <div class="card-header"><h6 class="mb-0">Catatan</h6></div>
                            <div class="card-body small text-muted">
                                <ul class="mb-0">
                                    <li>Satu penerimaan dapat berisi beberapa item.</li>
                                    <li>Jika status "Received", stok akan langsung bertambah & rekam di Stock Movements.</li>
                                    <li>Gunakan tombol "Duplikat Item Terakhir" untuk mempercepat input bila banyak item serupa.</li>
                                </ul>
                            </div>
                        </div>

                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div> {{-- col-12 --}}
        </div> {{-- row --}}
    </form>

    {{-- Template row (hidden) --}}
    <template id="item-row-template">
        <div class="item-row card mb-2">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-8 col-lg-8">
                        <label class="form-label">Produk / Variant <span class="text-danger">*</span></label>
                        <select name="items[][variant_id]" class="form-select variant-select" required></select>
                    </div>

                    <div class="col-6 col-md-3 col-lg-3">
                        <label class="form-label">Qty <span class="text-danger">*</span></label>
                        <input type="number" name="items[][quantity_received]" class="form-control qty-input" min="1" value="1" required>
                    </div>

                    <div class="col-12 col-md-12 col-lg-1 text-end">
                        <button type="button" class="btn btn-outline-danger btnRemoveItem mt-1" title="Hapus"><i class="ti ti-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@section('scripts')
    @vite(['resources/js/pages/stock-receipts/create.js'])

    <script>
        // show server flash error / success as toast (projectmu nampaknya pakai window.toast)
        @if(session('success'))
        if (window.toast) window.toast.success(@json(session('success')));
        @endif

            @if(session('error'))
        if (window.toast) window.toast.error(@json(session('error')));
        @endif

        @if($errors->any())
        // show validation errors
        const validationErrors = {!! json_encode($errors->all()) !!};
        if (window.toast) {
            validationErrors.forEach(err => window.toast.error(err));
        } else {
            console.warn('Validation Errors:', validationErrors);
        }
        @endif
    </script>

    <script>
        // expose variant-search route to JS (blade)
        window.stockReceiptVariantSearchUrl = '{{ route("stock_receipts.variant.search") }}';

        // Ensure select2 elements are full width after initialization
        document.addEventListener('DOMContentLoaded', function() {
            // If using Select2, ensure width 100%
            if (window.$ && $.fn && $.fn.select2) {
                $('.select2').each(function() {
                    const $el = $(this);
                    if (!$el.data('select2')) {
                        $el.select2({ width: '100%' });
                    } else {
                        $el.css('width','100%');
                    }
                });
            }
        });
    </script>
@endsection
