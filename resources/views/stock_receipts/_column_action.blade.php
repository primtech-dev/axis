<div class="d-flex gap-1 justify-content-center">
    @can('stock_receipts.view')
        <a href="{{ route('stock_receipts.show', $r->id) }}" class="btn btn-sm btn-outline-info" title="Lihat"><i class="ti ti-eye"></i></a>
    @endcan
{{--    @can('stock_receipts.update')--}}
{{--        <a href="{{ route('stock_receipts.edit', $r->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="ti ti-edit"></i></a>--}}
{{--    @endcan--}}
{{--    @can('stock_receipts.delete')--}}
{{--        <button class="btn btn-sm btn-outline-danger js-delete-stock-receipt" data-id="{{ $r->id }}" data-name="{{ $r->public_id }}"><i class="ti ti-trash"></i></button>--}}
{{--    @endcan--}}
</div>
