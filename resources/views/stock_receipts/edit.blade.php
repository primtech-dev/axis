@extends('layouts.vertical', ['title' => 'Edit Stok Masuk — #' . $receipt->id])

@section('styles')
    <style>
        .item-row .form-label { font-size: .9rem; }
        .item-row .variant-select { width: 100% !important; }
        #items-wrapper .item-row { border: 1px solid #eef2f6; border-radius: .375rem; padding: .5rem; background: #fff; }
        @media (max-width: 576px) {
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
        .meta-row small { color: #6c757d; }
    </style>
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Edit Stok Masuk',
        'subTitle' => 'Ubah penerimaan barang #' . $receipt->id,
        'breadcrumbs' => [
            ['name'=>'Persediaan','url'=>route('stock_receipts.index')],
            ['name'=>'Edit']
        ]
    ])

    <form action="{{ route('stock_receipts.update', $receipt->id) }}" method="POST" id="stockReceiptForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- main -->
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Informasi Penerimaan</h5>
                            <div class="small text-muted">ID: {{ $receipt->id }} — Public ID: {{ $receipt->public_id ?? '-' }}</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('stock_receipts.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-arrow-left me-1"></i> Kembali
                            </a>

                            <button type="button" class="btn btn-outline-danger btn-sm" id="btnShowDeleteModal">
                                <i class="ti ti-trash me-1"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <label class="form-label">Cabang <span class="text-danger">*</span></label>
                                <select name="branch_id" id="branch_id" class="form-select select2" data-no-placeholder="1" data-allow-clear="false" required>
                                    <option value="">— Pilih cabang —</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}" {{ $receipt->branch_id == $b->id ? 'selected' : '' }}>
                                            {{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="d-block mt-1">Tanggal dibuat: <strong>{{ $receipt->created_at ? $receipt->created_at->format('d M Y H:i') : '-' }}</strong></small>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="received" {{ $receipt->status === 'received' ? 'selected' : '' }}>Received</option>
                                    <option value="draft" {{ $receipt->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="cancelled" {{ $receipt->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <small class="text-muted d-block">Ubah status jika diperlukan. Perubahan dari/ke "received" akan menambah/kurangi stok.</small>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Supplier</label>
                                <input type="text" name="supplier_name" class="form-control" value="{{ old('supplier_name', $receipt->supplier_name) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Reference No (PO/Delivery)</label>
                                <input type="text" name="reference_no" class="form-control" value="{{ old('reference_no', $receipt->reference_no) }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Catatan (opsional)</label>
                                <textarea name="notes" rows="3" class="form-control">{{ old('notes', $receipt->notes) }}</textarea>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Items</h6>
                            <div class="d-flex gap-2">
                                <button type="button" id="btnAddItem" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-plus me-1"></i> Tambah Item
                                </button>
                                <button type="button" id="btnAddItemCopyLast" class="btn btn-sm btn-outline-secondary" title="Duplikat item terakhir">
                                    <i class="ti ti-copy me-1"></i> Duplikat Item Terakhir
                                </button>
                            </div>
                        </div>

                        <div id="items-wrapper" class="mb-2">
                            {{-- prefill existing items --}}
                            @foreach($receipt->items as $it)
                                <div class="item-row card mb-2">
                                    <div class="card-body">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-12 col-md-8 col-lg-8">
                                                <label class="form-label">Produk / Variant <span class="text-danger">*</span></label>
                                                <select name="items[][variant_id]" class="form-select variant-select" required>
                                                    {{-- create a selected option so Select2 displays it --}}
                                                    @php
                                                        $variant = $it->variant;
                                                        $product = $variant && $variant->product ? $variant->product : null;
                                                        $label = ($variant->sku ?? '') . ($variant->variant_name ? ' — '.$variant->variant_name : '') . ($product ? ' ('.$product->name.')' : '');
                                                    @endphp
                                                    <option value="{{ $it->variant_id }}" selected>{{ $label }}</option>
                                                </select>
                                            </div>

                                            <div class="col-6 col-md-3 col-lg-3">
                                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                                <input type="number" name="items[][quantity_received]" class="form-control qty-input" min="1" value="{{ $it->quantity_received }}" required>
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-1 text-end">
                                                <button type="button" class="btn btn-outline-danger btnRemoveItem mt-1" title="Hapus"><i class="ti ti-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- small note + created by --}}
                        <div class="card mt-3">
                            <div class="card-body small text-muted">
                                <div class="meta-row">
                                    <div>Created by: <strong>{{ $receipt->created_by ? optional(\App\Models\User::find($receipt->created_by))->name : 'System' }}</strong></div>
                                    <div>Received at: <strong>{{ $receipt->received_at ? $receipt->received_at->format('d M Y H:i') : '-' }}</strong></div>
                                </div>
                            </div>
                        </div>

                    </div> {{-- card-body --}}
                </div> {{-- card --}}
            </div> {{-- col-12 --}}
        </div> {{-- row --}}
    </form>

    {{-- DELETE modal (simple inline if you don't have component) --}}
    <div class="modal fade" id="deleteReceiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteReceiptForm" method="POST" action="{{ route('stock_receipts.destroy', $receipt->id) }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Penerimaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus penerimaan ini? <br>
                            @if($receipt->status === 'received')
                                <strong>Catatan:</strong> Karena status "received", penghapusan akan mengurangi stok sesuai qty. Jika stok tidak cukup, proses akan dibatalkan.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/stock-receipts/create.js'])

    <script>
        // ensure route for variant search exists for JS
        window.stockReceiptVariantSearchUrl = '{{ route("stock_receipts.variant.search") }}';

        // expose delete route for other parts if needed
        window.stockReceiptRoutes = window.stockReceiptRoutes || {};
        window.stockReceiptRoutes.destroy = '{{ route("stock_receipts.destroy", ":id") }}';

        document.addEventListener('DOMContentLoaded', function () {
            // initialize global branch select (create.js ensureSelect2/initGlobalSelect2 will handle too)
            if (window.$ && $.fn && $.fn.select2) {
                // ensure branch select shows options (no placeholder)
                $('#branch_id').each(function(){
                    if (!$(this).data('select2-initialized')) {
                        $(this).select2({ width: '100%', allowClear: false, placeholder: null });
                        $(this).data('select2-initialized', true);
                    }
                });

                // For prefilled variant-select elements (we have <option selected>), init select2 on them
                $('.variant-select').each(function(){
                    // if not initialized by create.js, initialize via AJAX config
                    if (!$(this).data('select2-initialized')) {
                        // create.js provides initVariantSelect — call it if available
                        if (typeof initVariantSelect === 'function') {
                            initVariantSelect($(this));
                        } else {
                            // fallback basic select2 for display
                            $(this).select2({ width: '100%' });
                            $(this).data('select2-initialized', true);
                        }
                    }
                });
            }

            // wire delete modal button
            document.querySelector('#btnShowDeleteModal')?.addEventListener('click', function () {
                const modalEl = document.getElementById('deleteReceiptModal');
                if (!modalEl) return;
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });

            // ensure we reindex before submit (create.js already has reindex but ensure here too for safety)
            document.getElementById('stockReceiptForm').addEventListener('submit', function (e) {
                if (typeof reindexItemRows === 'function') {
                    reindexItemRows();
                }
                // create.js also performs validation; we don't duplicate heavy validation here.
            });
        });
    </script>
@endsection
