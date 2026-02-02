@extends('layouts.vertical', ['title' => 'Detail Stok Masuk'])

@section('content')
    @include('layouts.shared.page-title', [
        'title' => 'Detail Stok Masuk',
        'subTitle' => 'Public ID: '.$receipt->public_id,
        'breadcrumbs' => [['name'=>'Persediaan','url'=>route('stock_receipts.index')], ['name'=>'Detail']]
    ])

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Public ID</dt>
                <dd class="col-sm-9">{{ $receipt->public_id }}</dd>
                <dt class="col-sm-3">Cabang</dt>
                <dd class="col-sm-9">{{ $receipt->branch->name ?? '-' }}</dd>
                <dt class="col-sm-3">Supplier</dt>
                <dd class="col-sm-9">{{ $receipt->supplier_name ?? '-' }}</dd>
                <dt class="col-sm-3">Reference No</dt>
                <dd class="col-sm-9">{{ $receipt->reference_no ?? '-' }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($receipt->status) }}</dd>
                <dt class="col-sm-3">Received At</dt>
                <dd class="col-sm-9">{{ $receipt->received_at?->format('d M Y H:i') ?? '-' }}</dd>
                <dt class="col-sm-3">Notes</dt>
                <dd class="col-sm-9">{{ $receipt->notes ?? '-' }}</dd>
            </dl>

            <hr>
            <h6>Items</h6>
            <table class="table table-sm">
                <thead>
                <tr>
                    <th>No</th>
                    <th>SKU / Variant</th>
                    <th>Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach($receipt->items as $idx => $it)
                    <tr>
                        <td>{{ $idx+1 }}</td>
                        <td>{{ $it->variant->sku ?? '—' }}
                            — {{ $it->variant->name ?? ($it->variant->product->name ?? '-') }}</td>
                        <td>{{ $it->quantity_received }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
