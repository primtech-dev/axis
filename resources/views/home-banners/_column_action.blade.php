<div class="d-flex justify-content-center gap-1">

    @can('home_banners.manage')
        <a href="{{ route('admin.home-banners.edit', $b->id) }}"
           class="btn btn-sm btn-outline-primary"
           data-bs-toggle="tooltip"
           title="Edit Banner">
            <i class="ti ti-edit"></i>
        </a>

        <button type="button"
                class="btn btn-sm btn-outline-danger js-delete-banner"
                data-id="{{ $b->id }}"
                data-name="{{ $b->title ?? $b->code }}"
                data-bs-toggle="tooltip"
                title="Hapus Banner">
            <i class="ti ti-trash"></i>
        </button>
    @endcan

</div>
