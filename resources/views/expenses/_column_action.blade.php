<div class="d-flex gap-1 justify-content-center">

    {{-- DETAIL --}}
    @can('expenses.view')
        <a href="{{ route('expenses.show', $e->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
            title="Detail">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    {{-- PRINT --}}
    @can('expenses.view')
        <a href="{{ route('expenses.print', $e->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank"
            data-bs-toggle="tooltip" title="Print">
            <i class="ti ti-printer"></i>
        </a>
    @endcan

    {{-- OPTIONAL: EDIT (jika masih OPEN & belum dibayar) --}}
    {{-- @can('expenses.update')
        @if ($e->status === 'OPEN')
            <a href="{{ route('expenses.edit', $e->id) }}"
               class="btn btn-sm btn-outline-primary"
               data-bs-toggle="tooltip"
               title="Edit">
                <i class="ti ti-edit"></i>
            </a>
        @endif
    @endcan --}}

    {{-- BAYAR HUTANG (khusus CREDIT & belum PAID) --}}
    @can('supplier_payments.create')
        @if ($e->payment_type === 'CREDIT' && $e->status !== 'PAID')
            <a href="{{ route('expenses.pay.create', $e->id) }}" class="btn btn-sm btn-outline-success"
                data-bs-toggle="tooltip" title="Bayar Hutang">
                <i class="ti ti-cash"></i>
            </a>
        @endif
    @endcan

</div>
