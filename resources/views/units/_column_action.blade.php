<div class="d-flex gap-1 justify-content-center">
    @can('units.update')
        <a href="{{ route('units.edit', $u->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('units.delete')
        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-unit"
                data-id="{{ $u->id }}"
                data-name="{{ e($u->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
