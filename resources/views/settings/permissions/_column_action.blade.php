<div class="d-flex gap-1 justify-content-center">
    @can('permissions.update')
        <a href="{{ route('permissions.edit', $p->id) }}"
           class="btn btn-sm btn-outline-primary"
           data-bs-toggle="tooltip"
           title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('permissions.delete')
        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-permission"
                data-id="{{ $p->id }}"
                data-name="{{ e($p->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
