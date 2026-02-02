/**
 * roles-form.js
 * - Toggle select all permissions
 * - Client validation
 * - Disable submit button + spinner
 */

import $ from 'jquery';

function toastError(msg) {
    if (window.toast && typeof window.toast.error === 'function') {
        window.toast.error(msg);
    } else {
        console.warn('Toast not available:', msg);
    }
}

$(function() {
    const $form = $('#roleForm');
    const $name = $('#name');
    const $permCheckboxes = $('.permission-checkbox');
    const $btnToggleAll = $('#btnToggleAllPerms');

    $btnToggleAll.on('click', function() {
        const allChecked = $permCheckboxes.length === $permCheckboxes.filter(':checked').length;
        $permCheckboxes.prop('checked', !allChecked);
    });

    $form.on('submit', function(e) {
        const nameVal = $name.val().trim();
        const checkedCount = $permCheckboxes.filter(':checked').length;

        if (!nameVal) {
            e.preventDefault();
            toastError('Nama role harus diisi.');
            $name.focus();
            return false;
        }

        if (checkedCount === 0) {
            // optional: allow zero permissions but warn user
            e.preventDefault();
            toastError('Pilih minimal satu permission untuk role ini.');
            return false;
        }

        const $btn = $(this).find('button[type="submit"]').first();
        $btn.prop('disabled', true);
        $btn.data('orig-html', $btn.html());
        $btn.html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...`);
        return true;
    });

    // Show server validation errors as toast (if any)
    if (typeof window.serverValidationErrors !== 'undefined' && Array.isArray(window.serverValidationErrors)) {
        window.serverValidationErrors.forEach(err => toastError(err));
    }
});
