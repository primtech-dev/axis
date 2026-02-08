<div class="d-flex gap-1 justify-content-center">

    <div class="d-flex gap-1 justify-content-center">

        {{-- DETAIL --}}
        @can('suppliers.view')
            <a href="{{ route('suppliers.show', $s->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                title="Detail">
                <i class="ti ti-eye"></i>
            </a>
        @endcan

        @can('suppliers.update')
            <a href="{{ route('suppliers.edit', $s->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                title="Edit">
                <i class="ti ti-edit"></i>
            </a>
        @endcan

        @can('suppliers.update')
            <button type="button"
                class="btn btn-sm {{ $s->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} js-toggle-active"
                data-id="{{ $s->id }}" data-bs-toggle="tooltip"
                title="{{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                <i class="ti ti-power"></i>
            </button>
        @endcan

        @can('suppliers.delete')
            <button type="button" class="btn btn-sm btn-outline-danger js-delete-supplier" data-id="{{ $s->id }}"
                data-name="{{ e($s->name) }}" data-bs-toggle="tooltip" title="Hapus">
                <i class="ti ti-trash"></i>
            </button>
        @endcan

    </div>

</div>
