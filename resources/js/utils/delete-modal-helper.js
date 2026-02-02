import bootstrap from 'bootstrap/dist/js/bootstrap';

/**
 * Delete Modal Helper
 * Reusable function to show delete confirmation modal
 */
export function showDeleteModal(config) {
    const {
        modalId = 'deleteModal',
        formId = 'deleteForm',
        itemNameId = 'delete_item_name',
        id,
        name,
        route
    } = config;

    // Get form and update action
    const form = document.getElementById(formId);
    if (form) {
        form.action = route.replace(':id', id);
    }

    // Set item name
    const itemNameElement = document.getElementById(itemNameId);
    if (itemNameElement) {
        itemNameElement.textContent = name;
    }

    // Show modal
    const modalElement = document.getElementById(modalId);
    if (modalElement) {
        const deleteModal = new bootstrap.Modal(modalElement);
        deleteModal.show();
    }
}
