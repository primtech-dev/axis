@props([
    'id' => 'deleteModal',
    'formId' => 'deleteForm',
    'route' => '',
    'itemNameId' => 'delete_item_name',
    'title' => 'Confirm Delete',
    'message' => 'Are you sure you want to delete',
    'itemType' => 'item'
])

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $route }}" method="POST" id="{{ $formId }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="ti ti-alert-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">{{ $message }} {{ $itemType }}: <strong id="{{ $itemNameId }}"></strong>?</p>
                    <p class="text-muted text-center mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i> Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
