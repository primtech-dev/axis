@extends('layouts.vertical', ['title' => 'Tambah Biaya'])

@section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Tambah Biaya',
        'subTitle' => 'Biaya tambahan pembelian',
    ])

    <form action="{{ route('expenses.store') }}" method="POST" id="expenseForm">
        @csrf

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Informasi Biaya</h5>
            </div>

            <div class="card-body row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Pembelian</label>
                    <select name="purchase_id" class="form-select tom-select" required>
                        <option value="">- Pilih -</option>
                        @foreach ($purchases as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->invoice_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Supplier Biaya</label>
                    <select name="supplier_id" class="form-select tom-select" required>
                        <option value="">- Pilih -</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipe Pembayaran</label>
                    <select name="payment_type" class="form-select tom-select" id="paymentType">
                        <option value="CASH">Cash</option>
                        <option value="CREDIT">Hutang</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3 d-none" id="cashAccountWrapper">
                    <label class="form-label">Akun Pembayaran</label>
                    <select name="cash_account_id" class="form-select tom-select">
                        <option value="">- Pilih Akun -</option>
                        @foreach ($cashAccounts as $acc)
                            <option value="{{ $acc->id }}">
                                {{ $acc->code }} - {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3 d-none" id="dueDateWrapper">
                    <label class="form-label">Jatuh Tempo</label>
                    <input type="date" name="due_date" class="form-control">
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Detail Biaya</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addRow">
                    + Tambah Baris
                </button>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Nama Biaya</th>
                            <th width="120">Qty</th>
                            <th width="160">Harga</th>
                            <th width="160">Subtotal</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="card-footer text-end">
                <input type="hidden" name="subtotal" id="subtotal">
                <input type="hidden" name="grand_total" id="grandTotal">

                <h5>Total: <span id="totalText">0</span></h5>

                <button class="btn btn-primary">
                    <i data-lucide="save"></i> Simpan
                </button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/expenses/expenses-form.js'])
@endsection
