export function showLockModal({ modalId, formId, itemNameId, id, name, route }) {
    // Set item name
    document.getElementById(itemNameId).textContent = name;

    // Update form action
    const form = document.getElementById(formId);
    const actionUrl = route.replace(':id', id);
    form.setAttribute('action', actionUrl);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
}
