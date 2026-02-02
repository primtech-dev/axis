@extends('layouts.vertical', ['title' => 'Detail Order'])

@section('styles')
    @vite(['node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css'])
@endsection

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Order',
        'subTitle' => "Order: {$order->order_no}",
        'breadcrumbs' => [
            ['name' => 'Penjualan', 'url' => route('admin.orders.index')],
            ['name' => 'Detail Order']
        ]
    ])

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Order</h5>

                    <div>
                        @can('orders.manage')
                            <button class="btn btn-sm btn-outline-success me-1" onclick="openChangeStatusModal({{ $order->id }}, '{{ e($order->order_no) }}', '{{ $order->status }}')">
                                <i data-lucide="box" class="me-1"></i> Ubah Status
                            </button>
                        @endcan

                        @can('orders.view')
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i data-lucide="arrow-left-circle" class="me-1"></i> Kembali
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order No:</strong> {{ $order->order_no }}<br>
                        <strong>Public ID:</strong> {{ $order->public_id }}<br>
                        <strong>Status:</strong> <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span><br>
                        <strong>Placed At:</strong> {{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}<br>
                        <strong>Customer:</strong> {{ $order->customer ? e($order->customer->full_name) : '-' }}<br>
                        <strong>Branch:</strong> {{ $order->branch ? e($order->branch->name) : '-' }}<br>
                        <strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? '-') }}
                    </div>

                    <h6>Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>SKU</th>
                                <th>Produk</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->items as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price,0,',','.') }}</td>
                                    <td class="text-center">{{ $item->line_quantity }}</td>
                                    <td class="text-end">{{ number_format($item->line_total,0,',','.') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Alamat Pengiriman & Notes</h6>

                            @if($order->address)
                                @php
                                    $addr = $order->address;
                                    $lines = [];
                                    if (!empty($addr->label)) $lines[] = e($addr->label);
                                    if (!empty($addr->recipient_name)) $lines[] = e($addr->recipient_name); // jika kolom ada
                                    if (!empty($addr->phone)) $lines[] = 'Telp: ' . e($addr->phone);
                                    if (!empty($addr->street)) $lines[] = e($addr->street);
                                    $cityLine = trim(($addr->city ?? '') . ' ' . ($addr->postal_code ?? ''));
                                    if ($cityLine) $lines[] = e($cityLine);
                                    if (!empty($addr->province)) $lines[] = e($addr->province);
                                    if (!empty($addr->country)) $lines[] = e($addr->country);
                                @endphp

                                <div class="border rounded p-2 small mb-2">
                                    @foreach($lines as $ln)
                                        <div>{!! $ln !!}</div>
                                    @endforeach

                                    @if(empty($lines))
                                        <pre class="small mb-0">{{ json_encode($order->address->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                    @endif
                                </div>

                            @elseif(isset($order->meta['shipping_address']))
                                <div class="border rounded p-2 small mb-2">
                                    <pre class="small mb-0">{{ json_encode($order->meta['shipping_address'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @else
                                <div class="text-muted mb-2"><em>Tidak ada alamat pengiriman</em></div>
                            @endif

                            <div>
                                <p class="mb-0"><strong>Notes:</strong></p>
                                <div class="small text-wrap">{!! nl2br(e($order->notes ?? '-')) !!}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6>Ringkasan Biaya</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td>Subtotal sebelum diskon</td>
                                    <td class="text-end">{{ number_format($order->subtotal_before_discount ?? 0,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <td>Total Diskon</td>
                                    <td class="text-end">- {{ number_format($order->discount_total ?? 0,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <td>Subtotal setelah diskon</td>
                                    <td class="text-end">{{ number_format($order->subtotal_after_discount ?? 0,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Kirim</td>
                                    <td class="text-end">{{ number_format($order->shipping_cost ?? 0,0,',','.') }}</td>
                                </tr>
                                <tr>
                                    <td>Pajak</td>
                                    <td class="text-end">{{ number_format($order->tax_total ?? 0,0,',','.') }}</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">{{ number_format($order->total_amount ?? 0,0,',','.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-4">
            {{-- Meta / Audit --}}
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Metadata & Audit</h6></div>
                <div class="card-body">
                    <p><strong>Public ID:</strong> {{ $order->public_id }}</p>
                    <p><strong>Created:</strong> {{ $order->created_at }}</p>
                    <p><strong>Updated:</strong> {{ $order->updated_at }}</p>
                    <p><strong>Cancelled At:</strong> {{ $order->cancelled_at ?? '-' }}</p>
                    <hr>

                </div>
            </div>
        </div>
    </div>

    {{-- Include change status modal partial --}}
    @include('orders._change_status_modal', ['order' => $order])
@endsection

@section('scripts')
    @vite(['resources/js/pages/orders/orders.js'])
    <script>
        // routes (re-use existing pattern)
        window.orderRoutes = window.orderRoutes || {};
        window.orderRoutes.updateStatus = '{{ route('admin.orders.updateStatus', $order->id) }}';
        // function to open from action buttons: defined in orders.js
    </script>
@endsection
