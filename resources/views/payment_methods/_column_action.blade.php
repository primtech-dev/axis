<div class="d-flex gap-1 justify-content-center">

    @can('payment_methods.view')
        <a href="{{ route('payment-methods.show', $m->id) }}"
           class="btn btn-sm btn-outline-info"
           data-bs-toggle="tooltip"
           title="Detail">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    @can('payment_methods.update')
        <a href="{{ route('payment-methods.edit', $m->id) }}"
           class="btn btn-sm btn-outline-primary"
           data-bs-toggle="tooltip"
           title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('payment_methods.update')
        <button type="button"
                class="btn btn-sm {{ $m->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} js-toggle-active"
                data-id="{{ $m->id }}"
                data-bs-toggle="tooltip"
                title="{{ $m->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
            <i class="ti ti-power"></i>
        </button>
    @endcan

    @can('payment_methods.delete')
        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-payment-method"
                data-id="{{ $m->id }}"
                data-name="{{ e($m->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan

</div>
