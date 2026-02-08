<div class="d-flex gap-1 justify-content-center">

    @can('purchases.view')
        <a href="{{ route('purchases.show', $p->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
            title="Detail">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    @can('purchases.view')
        <a href="{{ route('purchases.print', $p->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank"
            data-bs-toggle="tooltip" title="Print">
            <i class="ti ti-printer"></i>
        </a>
    @endcan

    {{-- OPTIONAL: kalau nanti kamu izinkan edit saat OPEN --}}
    {{-- @can('purchases.update')
        @if ($p->status === 'OPEN')
            <a href="{{ route('purchases.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                title="Edit">
                <i class="ti ti-edit"></i>
            </a>
        @endif
    @endcan --}}

    {{-- OPTIONAL: tombol bayar hutang (khusus CREDIT & belum PAID) --}}
    @can('supplier_payments.create')
        @if ($p->payment_type === 'CREDIT' && $p->status !== 'PAID')
            <a href="{{ route('purchases.pay.create', $p->id) }}" class="btn btn-sm btn-outline-success"
                data-bs-toggle="tooltip" title="Bayar Hutang">
                <i class="ti ti-cash"></i>
            </a>
        @endif
    @endcan

</div>
