import $ from 'jquery';

$(function () {

    const $form = $('#supplierForm');
    if (!$form.length) return;

    const $code = $('input[name="code"]');
    const $name = $('input[name="name"]');
    const $type = $('select[name="type"]');

    function toastError(msg) {
        if (window.toast?.error) window.toast.error(msg);
        else alert(msg);
    }

    $form.on('submit', function (e) {

        if (!$code.val().trim()) {
            e.preventDefault();
            toastError('Kode supplier wajib diisi.');
            $code.focus();
            return;
        }

        if (!$name.val().trim()) {
            e.preventDefault();
            toastError('Nama supplier wajib diisi.');
            $name.focus();
            return;
        }

        if (!$type.val()) {
            e.preventDefault();
            toastError('Tipe supplier wajib dipilih.');
            $type.focus();
            return;
        }

        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
    });

    if (Array.isArray(window.serverValidationErrors)) {
        window.serverValidationErrors.forEach(msg => toastError(msg));
    }
});
