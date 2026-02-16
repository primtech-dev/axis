@extends('layouts.vertical', ['title' => 'Laba Rugi per Penjualan'])

{{-- @section('styles')
    @vite(['node_modules/tom-select/dist/css/tom-select.bootstrap5.min.css'])
@endsection --}}

@section('content')

    @php
        function formatQty($qty)
        {
            if (floor($qty) == $qty) {
                return number_format($qty, 0, ',', '.');
            }
            return rtrim(rtrim(number_format($qty, 4, ',', '.'), '0'), ',');
        }
    @endphp

    @include('layouts.shared.page-title', [
        'title' => 'Laba Rugi',
        'subTitle' => 'Per Nomor Penjualan',
    ])

    {{-- FILTER --}}
    <form method="GET" action="{{ route('profit-loss.sales') }}" class="card mb-3">
        <div class="card-body row g-3 align-items-end">

            <div class="col-md-6">
                <label class="form-label">Nomor Penjualan</label>
                <select name="sale_id" id="saleSelect" class="form-select" required>
                    <option value="">— Pilih Penjualan —</option>
                    @foreach ($sales as $s)
                        <option value="{{ $s->id }}" {{ request('sale_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->invoice_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary">
                    <i class="ti ti-search"></i> Tampilkan
                </button>
            </div>

        </div>
    </form>

    @if ($sale)
        {{-- RINGKASAN --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="text-muted">Penjualan</div>
                        <h5>{{ number_format($result['totalPenjualan'], 2, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="text-muted">Pembelian</div>
                        <h5>{{ number_format($result['totalPembelian'], 2, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="text-muted">Expense</div>
                        <h5>{{ number_format($result['totalExpense'], 2, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="text-muted">Laba</div>
                        <h5 class="{{ $result['laba'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($result['laba'], 2, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- DETAIL ITEM --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Detail Item Penjualan</h6>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>No Pembelian</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Subtotal Jual</th>
                            <th class="text-end">Subtotal Beli</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    {{ $item->purchaseItem?->purchase?->invoice_number ?? '-' }}
                                </td>
                                <td class="text-end">
                                    {{ formatQty($item->qty) }}
                                </td>
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
                    </tbody>
                </table>
            </div>
        </div>

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
                                <th class="text-end">Total Pembelian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $p)
                                {{-- HEADER PURCHASE --}}
                                <tr class="table-secondary">
                                    <td class="fw-semibold">{{ $p->invoice_number }}</td>
                                    <td>{{ $p->date->format('Y-m-d') }}</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($p->grand_total, 2, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- DETAIL ITEM PURCHASE --}}
                                @foreach ($p->items as $item)
                                    <tr>
                                        <td colspan="1">↳ {{ $item->product->name }}</td>
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
                                <th class="text-end">Total Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $e)
                                {{-- HEADER EXPENSE --}}
                                <tr class="table-secondary">
                                    <td class="fw-semibold">{{ $e->expense_number }}</td>
                                    <td>{{ $e->purchase?->invoice_number ?? '-' }}</td>
                                    <td>{{ $e->date->format('Y-m-d') }}</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($e->grand_total, 2, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- DETAIL ITEM EXPENSE --}}
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
    @vite(['resources/js/pages/profit-loss/per-sale.js'])
@endsection
