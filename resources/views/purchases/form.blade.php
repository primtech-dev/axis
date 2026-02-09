@extends('layouts.vertical', ['title' => 'Tambah Pembelian'])

{{-- @section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css'])
@endsection --}}

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Pembelian',
        'subTitle' => 'Form transaksi pembelian barang',
    ])

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        {{-- HEADER --}}
        <div class="card mb-3">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">No Invoice</label>
                    <input type="text" class="form-control" value="{{ $invoice_preview }}" readonly>
                    <small class="text-muted">
                        Nomor dibuat otomatis saat disimpan
                    </small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" id="supplierSelect" class="form-select" required>
                        <option value="">— Pilih Supplier —</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipe Pembayaran</label>
                    <select name="payment_type" id="paymentType" class="form-select">
                        <option value="CASH">Cash</option>
                        <option value="CREDIT">Hutang</option>
                    </select>
                </div>

                <div class="col-md-4" id="cashAccountWrapper">
                    <label class="form-label">
                        Sumber Dana <span class="text-danger">*</span>
                    </label>
                    <select name="cash_account_id" id="cashAccountSelect" class="form-select">
                        <option value="">— Pilih Akun —</option>
                        @foreach ($cashAccounts as $acc)
                            <option value="{{ $acc->id }}">
                                {{ $acc->code }} - {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-none" id="dueDateWrapper">
                    <label class="form-label">
                        Jatuh Tempo <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="due_date" id="dueDate" class="form-control">
                </div>

            </div>
        </div>

        {{-- ITEMS --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Item Pembelian</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addRow">
                    <i class="ti ti-plus"></i> Tambah Item
                </button>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th width="120">Qty</th>
                            <th width="180">Harga</th>
                            <th width="180">Subtotal</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- TOTAL --}}
        <div class="card">
            <div class="card-body row g-3">

                <div class="col-md-3 ms-auto">
                    <label class="form-label">Subtotal</label>
                    <input type="text" id="subtotal_display" class="form-control" readonly>
                    <input type="hidden" name="subtotal" id="subtotal" value="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Diskon</label>
                    <input type="text" id="discount_display" class="form-control" value="0">
                    <input type="hidden" name="discount" id="discount" value="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Pajak</label>
                    <input type="text" id="tax_display" class="form-control" value="0">
                    <input type="hidden" name="tax" id="tax" value="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Grand Total</label>
                    <input type="text" id="grand_total_display" class="form-control" readonly>
                    <input type="hidden" name="grand_total" id="grand_total" value="0">
                </div>

            </div>

            <div class="card-footer d-flex gap-2">
                <button class="btn btn-primary">
                    <i data-lucide="save"></i> Simpan
                </button>
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left"></i> Kembali
                </a>
            </div>
        </div>

    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/purchases/purchase-form.js'])

    <script>
        window.products = @json($products);
    </script>
@endsection
