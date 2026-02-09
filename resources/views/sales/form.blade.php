@extends('layouts.vertical', ['title' => 'Tambah Penjualan'])

{{-- @section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css'])
@endsection --}}

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Penjualan',
        'subTitle' => 'Transaksi penjualan dari multiple pembelian',
    ])

    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
        @csrf

        {{-- ================= HEADER ================= --}}
        <div class="card mb-3">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">No Invoice</label>
                    <input class="form-control" value="{{ $invoice_preview }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" id="customerSelect" class="form-select">
                        <option value="">— Umum —</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipe Pembayaran</label>
                    <select name="payment_type" id="paymentType" class="form-select">
                        <option value="CASH">Cash</option>
                        <option value="CREDIT">Piutang</option>
                    </select>
                </div>

                <div class="col-md-4" id="cashAccountWrapper">
                    <label class="form-label">Sumber Dana</label>
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
                    <label class="form-label">Jatuh Tempo</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

            </div>
        </div>

        {{-- ================= ITEM PENJUALAN ================= --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Item Penjualan</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addPurchase">
                    + Tambah Pembelian
                </button>
            </div>

            <div class="card-body" id="purchaseContainer">
                {{-- BLOCK PEMBELIAN MASUK KE SINI --}}
            </div>
        </div>

        {{-- ================= TOTAL ================= --}}
        <div class="card">
            <div class="card-body row g-3">

                <div class="col-md-3 ms-auto">
                    <label class="form-label">Subtotal</label>
                    <input class="form-control" id="subtotal_display" readonly>
                    <input type="hidden" name="subtotal" id="subtotal">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Diskon</label>
                    <input class="form-control" id="discount_display" value="0">
                    <input type="hidden" name="discount" id="discount">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Pajak</label>
                    <input class="form-control" id="tax_display" value="0">
                    <input type="hidden" name="tax" id="tax">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Grand Total</label>
                    <input class="form-control" id="grand_total_display" readonly>
                    <input type="hidden" name="grand_total" id="grand_total">
                </div>

            </div>

            <div class="card-footer d-flex gap-2">
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>

    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/sales/sale-form.js'])

    <script>
        window.availablePurchases = @json($purchases);
    </script>
@endsection
