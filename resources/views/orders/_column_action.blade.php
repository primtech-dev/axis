<div class="btn-group" role="group">
    @can('orders.view')
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
            <i class="ti ti-eye"></i>
        </a>

        <a href="{{ route('admin.orders.print', $order->id) }}"
           class="btn btn-sm btn-outline-warning"
           data-bs-toggle="tooltip"
           title="Print Order"
           target="_blank">
            <i class="ti ti-printer"></i>
        </a>
    @endcan

    @can('orders.manage')
        <button class="btn btn-sm btn-outline-success" onclick="openChangeStatusModal({{ $order->id }}, '{{ e($order->order_no) }}')" data-bs-toggle="tooltip" title="Ubah Status">
            <i class="ti ti-edit"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger js-delete-order" data-id="{{ $order->id }}" data-name="{{ e($order->order_no) }}" data-bs-toggle="tooltip" title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
