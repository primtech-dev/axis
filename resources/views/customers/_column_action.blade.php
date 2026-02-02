<div class="d-flex gap-1 justify-content-center">
    @can('customers.view')
        <a href="{{ route('customers.show', $c->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Lihat">
            <i class="ti ti-eye"></i>
        </a>
    @endcan
</div>
