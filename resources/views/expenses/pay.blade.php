@extends('layouts.vertical', ['title' => 'Bayar Hutang Biaya'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Bayar Hutang Biaya',
        'subTitle' => 'Biaya ' . $expense->expense_number,
    ])

    <form method="POST" action="{{ route('expenses.pay.store', $expense->id) }}">
        @csrf

        {{-- INFO EXPENSE --}}
        <div class="card mb-3">
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">No Biaya</label>
                    <div class="fw-bold">{{ $expense->expense_number }}</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Supplier</label>
                    <div>{{ $expense->supplier->name }}</div>
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
                    <input type="date" name="date" class="form-control"
                           value="{{ now()->toDateString() }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sumber Dana <span class="text-danger">*</span></label>
                    <select name="cash_account_id" class="form-select js-tom-select" required>
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
                    <input type="number"
                           name="amount"
                           class="form-control"
                           min="1"
                           max="{{ $payable->balance }}"
                           step="0.01"
                           value="{{ $payable->balance }}"
                           required>
                    <small class="text-muted">
                        Maksimal {{ number_format($payable->balance, 2, ',', '.') }}
                    </small>
                </div>

            </div>

            <div class="card-footer d-flex gap-2">
                <button class="btn btn-success">
                    <i data-lucide="save"></i> Simpan Pembayaran
                </button>
                <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-outline-secondary">
                    <i data-lucide="arrow-left"></i> Batal
                </a>
            </div>
        </div>
    </form>
@endsection
