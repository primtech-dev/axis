import $ from 'jquery';

$(function () {

    const $form = $('#accountForm');
    if (!$form.length) return;

    const $code = $('input[name="code"]');
    const $name = $('input[name="name"]');
    const $type = $('select[name="type"]');

    function toastError(message) {
        if (window.toast?.error) {
            window.toast.error(message);
        } else {
            alert(message);
        }
    }

    $form.on('submit', function (e) {

        // =========================
        // VALIDASI CLIENT
        // =========================
        if (!$code.val().trim()) {
            e.preventDefault();
            toastError('Kode akun wajib diisi.');
            $code.focus();
            return;
        }

        if (!$name.val().trim()) {
            e.preventDefault();
            toastError('Nama akun wajib diisi.');
            $name.focus();
            return;
        }

        if (!$type.val()) {
            e.preventDefault();
            toastError('Tipe akun wajib dipilih.');
            $type.focus();
            return;
        }

        // =========================
        // DISABLE BUTTON
        // =========================
        const $btn = $(this).find('button[type="submit"]');
        $btn
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
    });

    // =========================
    // SERVER VALIDATION ERROR
    // =========================
    if (Array.isArray(window.serverValidationErrors)) {
        window.serverValidationErrors.forEach(msg => toastError(msg));
    }
});
