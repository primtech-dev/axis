import $ from 'jquery';

$(function () {

    const form = document.getElementById('categoryForm');
    if (!form) return;

    const $name = $('input[name="name"]');

    function toastError(msg) {
        if (window.toast?.error) {
            window.toast.error(msg);
        } else {
            alert(msg);
        }
    }

    $('#categoryForm').on('submit', function (e) {

        if (!$name.val().trim()) {
            e.preventDefault();
            toastError('Nama kategori wajib diisi.');
            $name.focus();
            return;
        }

        const $btn = $(this).find('button[type="submit"]').first();
        $btn.prop('disabled', true).html(
            `<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...`
        );
    });

    if (Array.isArray(window.serverValidationErrors)) {
        window.serverValidationErrors.forEach(msg => toastError(msg));
    }
});
