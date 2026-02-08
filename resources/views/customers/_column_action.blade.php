<div class="d-flex gap-1 justify-content-center">

    {{-- DETAIL --}}
    @can('customers.view')
        <a href="{{ route('customers.show', $c->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
            title="Detail">
            <i class="ti ti-eye"></i>
        </a>
    @endcan

    {{--    @can('customers.update') --}}
    <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
        title="Edit">
        <i class="ti ti-edit"></i>
    </a>
    {{--    @endcan --}}

    {{--        @can('customers.update') --}}
    @if ($c->is_active)
        {{-- AKTIF → tombol NONAKTIFKAN --}}
        <button type="button" class="btn btn-sm btn-outline-danger js-toggle-active" data-id="{{ $c->id }}"
            data-bs-toggle="tooltip" title="Nonaktifkan Customer">
            <i class="ti ti-power"></i>
        </button>
    @else
        {{-- NON-AKTIF → tombol AKTIFKAN --}}
        <button type="button" class="btn btn-sm btn-outline-success js-toggle-active" data-id="{{ $c->id }}"
            data-bs-toggle="tooltip" title="Aktifkan Customer">
            <i class="ti ti-power"></i>
        </button>
    @endif
    {{--        @endcan --}}

    {{--    @can('customers.delete') --}}
    <button type="button" class="btn btn-sm btn-outline-danger js-delete-customer" data-id="{{ $c->id }}"
        data-name="{{ e($c->name) }}" data-bs-toggle="tooltip" title="Hapus">
        <i class="ti ti-trash"></i>
    </button>
    {{--    @endcan --}}

</div>
