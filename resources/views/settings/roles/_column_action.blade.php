<div class="d-flex gap-1 justify-content-center">
    @can('roles.update')
        <a href="{{ route('roles.edit', $r->id) }}"
           class="btn btn-sm btn-outline-primary"
           data-bs-toggle="tooltip"
           title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('roles.delete')
        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-role"
                data-id="{{ $r->id }}"
                data-name="{{ e($r->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
