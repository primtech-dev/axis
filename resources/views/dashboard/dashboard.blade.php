@extends('layouts.vertical', ['title' => 'Dashboard'])

@section('content')

    {{-- DATA UNTUK JS --}}
    <script>
        window.dashboard = {
            ordersPerDay: @json($ordersPerDay),
            orderStats: {
                complete: {{ $orderStats->complete ?? 0 }},
                cancelled: {{ $orderStats->cancelled ?? 0 }},
                returned: {{ $orderStats->returned ?? 0 }},
            }
        };
    </script>

    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 mt-4">

        {{-- ORDERS TODAY --}}
        <div class="col">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="text-uppercase">Orders Hari Ini</h5>
                    <div class="mb-3">
                        <canvas height="60" id="ordersChart"></canvas>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="text-muted">Hari Ini</span>
                            <div class="fw-semibold">{{ number_format($ordersToday) }} orders</div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted">Kemarin</span>
                            <div class="fw-semibold">
                                {{ number_format($ordersYesterday) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    Aktivitas order 7 hari terakhir
                </div>
            </div>
        </div>

        {{-- ACTIVE CUSTOMERS --}}
        <div class="col">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="text-uppercase mb-3">Customer Aktif</h5>
                    <h3 class="mb-0 fw-normal">{{ $activeCustomers }}</h3>
                    <p class="text-muted mb-2">24 jam terakhir</p>
                    <div class="progress progress-lg">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    Berdasarkan transaksi order
                </div>
            </div>
        </div>

        {{-- ORDER STATUS --}}
        <div class="col">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="text-uppercase mb-3">Status Order</h5>
                    <div class="d-flex justify-content-center">
                        <canvas id="orderStatusChart" height="120" width="120"></canvas>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    30 hari terakhir
                </div>
            </div>
        </div>

        {{-- REVENUE --}}
        <div class="col">
            <div class="card card-h-100">
                <div class="card-body">
                    <h5 class="text-uppercase">Revenue Hari Ini</h5>
                    <h3 class="fw-semibold">
                        Rp {{ number_format($revenueToday, 0, ',', '.') }}
                    </h3>
                    <p class="text-muted mb-0">Order paid</p>
                </div>
                <div class="card-footer text-muted text-center">
                    Tidak termasuk pending / gagal
                </div>
            </div>
        </div>

    </div>

    {{-- ORDERS TREND --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Trend Order (7 Hari)</h4>
                    <div style="height:260px">
                        <canvas id="ordersTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ORDERS --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Order Terbaru</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-centered mb-0">
                            <thead class="bg-light-subtle">
                            <tr>
                                <th>Order No</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ $order->full_name }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'complete' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Belum ada order
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @vite(['resources/js/pages/dashboard.js'])
@endsection
