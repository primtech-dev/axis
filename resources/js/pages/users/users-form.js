$(function () {

    const form = document.getElementById('userForm');
    if (!form) return;

    const roleEl = document.getElementById('roleSelect');

    // TOM SELECT (tidak peduli ada jQuery atau tidak)
    let roleSelect = null;
    if (roleEl && window.TomSelect) {
        roleSelect = new TomSelect(roleEl, {
            maxItems: 1,
            placeholder: 'Pilih role',
            allowEmptyOption: true,
            create: false,
        });
    }

    const $name = $('input[name="name"]');
    const $email = $('input[name="email"]');
    const $password = $('input[name="password"]');
    const $passwordConfirm = $('input[name="password_confirmation"]');

    function toastError(msg) {
        if (window.toast?.error) {
            window.toast.error(msg);
        } else {
            alert(msg);
        }
    }

    $('#userForm').on('submit', function (e) {

        if (!$name.val().trim()) {
            e.preventDefault();
            toastError('Nama harus diisi.');
            return;
        }

        if (!$email.val().trim()) {
            e.preventDefault();
            toastError('Email harus diisi.');
            return;
        }

        if (roleSelect && !roleSelect.getValue()) {
            e.preventDefault();
            toastError('Role wajib dipilih.');
            roleSelect.focus();
            return;
        }

        const passwordVal = $password.val().trim();
        const passwordConfirmVal = $passwordConfirm.val().trim();

        // Password hanya divalidasi kalau diisi
        if (passwordVal !== '') {

            if (passwordVal.length < 6) {
                e.preventDefault();
                toastError('Password minimal 6 karakter.');
                $password.focus();
                return false;
            }

            if (passwordVal !== passwordConfirmVal) {
                e.preventDefault();
                toastError('Password dan konfirmasi tidak cocok.');
                $passwordConfirm.focus();
                return false;
            }
        }

        if ($password.val() !== $passwordConfirm.val()) {
            e.preventDefault();
            toastError('Password dan konfirmasi tidak cocok.');
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
