@extends('layouts.vertical', ['title' => 'Bayar Hutang Pembelian'])

{{-- @section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css'])
@endsection --}}


@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Bayar Hutang',
        'subTitle' => 'Pembelian ' . $purchase->invoice_number,
    ])

    <form method="POST" action="{{ route('purchases.pay.store', $purchase->id) }}">
        @csrf

        {{-- INFO PURCHASE --}}
        <div class="card mb-3">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Invoice</label>
                    <div class="fw-bold">{{ $purchase->invoice_number }}</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier</label>
                    <div>{{ $purchase->supplier->name }}</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sisa Hutang</label>
                    <div class="fw-bold text-danger">
                        {{ number_format($payable->balance, 2, ',', '.') }}
                    </div>
                </div>

            </div>
        </div>

        {{-- FORM --}}
        <div class="card">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" required>
                </div>

                {{-- <div class="col-md-4">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_method_id" class="form-select" required>
                        <option value="">— Pilih —</option>
                        @foreach ($methods as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="col-md-4">
                    <label class="form-label">
                        Sumber Dana <span class="text-danger">*</span>
                    </label>
                    <select name="cash_account_id" id="cashAccountSelect" class="form-select" required>
                        <option value="">— Pilih Akun —</option>
                        @foreach ($cashAccounts as $acc)
                            <option value="{{ $acc->id }}">
                                {{ $acc->code }} - {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                    <input type="number" name="amount" class="form-control" min="1" max="{{ $payable->balance }}"
                        step="0.01" value="{{ $payable->balance }}" required>
                    <small class="text-muted">
                        Maksimal {{ number_format($payable->balance, 2, ',', '.') }}
                    </small>
                </div>

            </div>

            <div class="card-footer d-flex gap-2">
                <button class="btn btn-success">
                    <i data-lucide="save"></i> Simpan Pembayaran
                </button>
                <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left"></i> Batal
                </a>
            </div>
        </div>

    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/purchases/purchase-pay.js'])
@endsection
