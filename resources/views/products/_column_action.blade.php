<div class="d-flex gap-1 justify-content-center">
    @can('products.update')
        <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
            <i class="ti ti-edit"></i>
        </a>
    @endcan

    @can('products.view')
        <a href="{{ route('products.show', $p->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Lihat Detail">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    @can('products.delete')
        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-product"
                data-id="{{ $p->id }}"
                data-name="{{ e($p->name) }}"
                data-bs-toggle="tooltip"
                title="Hapus">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
