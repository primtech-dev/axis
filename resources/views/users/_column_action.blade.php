<div class="d-flex gap-1 justify-content-center">
    @can('users.update')
        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('users.delete')
        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-user"
                data-id="{{ $u->id }}"
                data-name="{{ e($u->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
