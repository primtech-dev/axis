<div class="d-flex gap-1 justify-content-center">

    {{-- DETAIL --}}
    @can('sales.view')
        <a href="{{ route('sales.show', $s->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Detail">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    {{-- PRINT --}}
    @can('sales.view')
        <a href="{{ route('sales.print', $s->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank"
            data-bs-toggle="tooltip" title="Print">
            <i class="ti ti-printer"></i>
        </a>
    @endcan

    {{-- OPTIONAL: EDIT (misal hanya saat OPEN) --}}
    {{--
    @can('sales.update')
        @if ($s->status === 'OPEN')
            <a href="{{ route('sales.edit', $s->id) }}"
               class="btn btn-sm btn-outline-primary"
               data-bs-toggle="tooltip"
               title="Edit">
                <i class="ti ti-edit"></i>
            </a>
        @endif
    @endcan
    --}}

    {{-- BAYAR PIUTANG (CREDIT & BELUM PAID) --}}
    @can('customer_payments.create')
        @if ($s->payment_type === 'CREDIT' && $s->status !== 'PAID')
            <a href="{{ route('sales.pay.create', $s->id) }}" class="btn btn-sm btn-outline-success"
                data-bs-toggle="tooltip" title="Bayar Piutang">
                <i class="ti ti-cash"></i>
            </a>
        @endif
    @endcan

</div>
