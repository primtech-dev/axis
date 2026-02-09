@extends('layouts.vertical', ['title' => 'Bayar Piutang Penjualan'])

{{-- @section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css'])
@endsection --}}

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Bayar Piutang',
        'subTitle' => 'Penjualan ' . $sale->invoice_number,
    ])

    <form method="POST" action="{{ route('sales.pay.store', $sale->id) }}">
        @csrf

        {{-- INFO SALE --}}
        <div class="card mb-3">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">Invoice</label>
                    <div class="fw-bold">{{ $sale->invoice_number }}</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Customer</label>
                    <div>{{ $sale->customer->name }}</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sisa Piutang</label>
                    <div class="fw-bold text-danger">
                        {{ number_format($receivable->balance, 2, ',', '.') }}
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

                    {{-- DISPLAY (FORMAT RUPIAH) --}}
                    <input type="text" id="amount_display" class="form-control"
                        value="{{ number_format($receivable->balance, 0, ',', '.') }}" autocomplete="off" required>

                    {{-- REAL VALUE (DIKIRIM KE SERVER) --}}
                    <input type="hidden" name="amount" id="amount" value="{{ $receivable->balance }}">

                    <small class="text-muted">
                        Maksimal {{ number_format($receivable->balance, 0, ',', '.') }}
                    </small>
                </div>

            </div>

            <div class="card-footer d-flex gap-2">
                <button class="btn btn-success">
                    <i data-lucide="save"></i> Simpan Pembayaran
                </button>
                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left"></i> Batal
                </a>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/pages/sales/sale-pay.js'])
@endsection
