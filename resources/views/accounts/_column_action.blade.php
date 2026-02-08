<div class="d-flex gap-1 justify-content-center">

    @can('accounts.update')
        <a href="{{ route('accounts.edit', $a->id) }}"
           class="btn btn-sm btn-outline-primary"
           data-bs-toggle="tooltip"
           title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('accounts.delete')
        @if(! $a->is_system)
            <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-account"
                data-id="{{ $a->id }}"
                data-name="{{ e($a->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
                <i class="ti ti-trash"></i>
            </button>
        @endif
    @endcan

</div>
