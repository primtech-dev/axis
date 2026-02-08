@extends('layouts.vertical', ['title' => 'Laba Rugi per Customer'])

@section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Laba Rugi',
        'subTitle' => 'Per Customer & Periode',
    ])

    @php
        function formatQty($qty)
        {
            if (floor($qty) == $qty) {
                return number_format($qty, 0, ',', '.');
            }
            return rtrim(rtrim(number_format($qty, 4, ',', '.'), '0'), ',');
        }
    @endphp
    {{-- FILTER --}}
    <form method="GET" action="{{ route('profit-loss.customer') }}" class="card mb-3">
        <div class="card-body row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label">Customer</label>
                <select name="customer_id" id="customerSelect" class="form-select" required>
                    <option value="">— Pilih Customer —</option>
                    @foreach ($customers as $c)
                        <option value="{{ $c->id }}" {{ $selectedCustomer == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="from_date" value="{{ $from }}" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="to_date" value="{{ $to }}" class="form-control" required>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="ti ti-search"></i> Tampilkan
                </button>
            </div>

        </div>
    </form>

    @if ($result)
        {{-- =========================
        | RINGKASAN LABA RUGI
        ========================= --}}
        <div class="row g-3 mb-3">

            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="text-muted">Penjualan</div>
                        <h5 class="mb-0">
                            {{ number_format($result['totalPenjualan'], 2, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="text-muted">Pembelian</div>
                        <h5 class="mb-0">
                            {{ number_format($result['totalPembelian'], 2, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="text-muted">Expense</div>
                        <h5 class="mb-0">
                            {{ number_format($result['totalExpense'], 2, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="text-muted">Laba Bersih</div>
                        <h5 class="mb-0 {{ $result['laba'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($result['laba'], 2, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

        </div>

        {{-- =========================
        | DETAIL PENJUALAN
        ========================= --}}

        @if ($sales->count())
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Detail Penjualan</h6>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Produk</th>
                                <th>No Pembelian</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-end">Subtotal Jual</th>
                                <th class="text-end">Subtotal Beli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                @foreach ($sale->items as $item)
                                    <tr>
                                        <td>{{ $sale->invoice_number }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>
                                            {{ $item->purchaseItem?->purchase?->invoice_number ?? '-' }}
                                        </td>
                                        <td class="text-end">{{ formatQty($item->qty) }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->price, 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->subtotal, 2, ',', '.') }}
                                        </td>
                                        <td class="text-end text-muted">
                                            {{ number_format($item->purchaseItem?->subtotal ?? 0, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- PURCHASE --}}
        @if ($purchases->count())
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Detail Pembelian</h6>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No Pembelian</th>
                                <th>Tanggal</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $p)
                                <tr class="table-secondary">
                                    <td class="fw-semibold">{{ $p->invoice_number }}</td>
                                    <td>{{ $p->date->format('Y-m-d') }}</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($p->grand_total, 2, ',', '.') }}
                                    </td>
                                </tr>

                                @foreach ($p->items as $item)
                                    <tr>
                                        <td>↳ {{ $item->product->name }}</td>
                                        <td class="text-end">
                                            {{ formatQty($item->qty) }}
                                            × {{ number_format($item->price, 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->subtotal, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- EXPENSES --}}
        @if ($expenses->count())
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Detail Biaya Pembelian (Expense)</h6>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No Expense</th>
                                <th>No Pembelian</th>
                                <th>Tanggal</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $e)
                                <tr class="table-secondary">
                                    <td class="fw-semibold">{{ $e->expense_number }}</td>
                                    <td>{{ $e->purchase?->invoice_number ?? '-' }}</td>
                                    <td>{{ $e->date->format('Y-m-d') }}</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($e->grand_total, 2, ',', '.') }}
                                    </td>
                                </tr>

                                @foreach ($e->items as $item)
                                    <tr>
                                        <td colspan="2">↳ {{ $item->name }}</td>
                                        <td class="text-end">
                                            {{ formatQty($item->qty) }}
                                            × {{ number_format($item->price, 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->subtotal, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


    @endif
@endsection

@section('scripts')
    @vite(['resources/js/pages/profit-loss/per-customer.js'])
@endsection
