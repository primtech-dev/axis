<div class="d-flex gap-1 justify-content-center">
    <a href="{{ route('admin.reviews.show',$r->id) }}"
       class="btn btn-sm btn-outline-info"
       data-bs-toggle="tooltip" title="Detail">
        <i class="ti ti-eye"></i>
    </a>

    @can('reviews.manage')
        <button class="btn btn-sm btn-outline-danger js-delete-review"
                data-id="{{ $r->id }}">
            <i class="ti ti-trash"></i>
        </button>
    @endcan
</div>
