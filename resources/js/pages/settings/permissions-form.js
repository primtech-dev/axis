import $ from 'jquery';

function toastError(msg) {
    if (window.toast && typeof window.toast.error === 'function') {
        window.toast.error(msg);
    } else {
        console.warn('Toast not available:', msg);
    }
}

$(function() {
    const $form = $('#permissionForm');
    const $name = $('#name');

    $form.on('submit', function(e) {
        const nameVal = $name.val().trim();

        if (!nameVal) {
            e.preventDefault();
            toastError('Nama permission harus diisi.');
            $name.focus();
            return false;
        }

        const $btn = $(this).find('button[type="submit"]').first();
        $btn.prop('disabled', true);
        $btn.data('orig-html', $btn.html());
        $btn.html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...`);
        return true;
    });

    if (typeof window.serverValidationErrors !== 'undefined' && Array.isArray(window.serverValidationErrors)) {
        window.serverValidationErrors.forEach(err => toastError(err));
    }
});
