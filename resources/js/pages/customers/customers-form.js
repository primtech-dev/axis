import $ from 'jquery';

$(function () {

    const form = document.getElementById('customerForm');
    if (!form) return;

    const $code  = $('input[name="code"]');
    const $name  = $('input[name="name"]');
    const $phone = $('input[name="phone"]');

    function toastError(msg) {
        if (window.toast?.error) {
            window.toast.error(msg);
        } else {
            alert(msg);
        }
    }

    $('#customerForm').on('submit', function (e) {

        if (!$code.val().trim()) {
            e.preventDefault();
            toastError('Kode customer wajib diisi.');
            $code.focus();
            return;
        }

        if (!$name.val().trim()) {
            e.preventDefault();
            toastError('Nama customer wajib diisi.');
            $name.focus();
            return;
        }

        if ($phone.val() && $phone.val().length < 6) {
            e.preventDefault();
            toastError('Nomor telepon terlalu pendek.');
            $phone.focus();
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
