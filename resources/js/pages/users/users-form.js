import $ from 'jquery';
import { initChoices } from '../../utils/choices-helper';

function toastError(msg) {
    if (window.toast && typeof window.toast.error === 'function') {
        window.toast.error(msg);
    } else {
        console.warn('Toast not available:', msg);
    }
}

$(function() {
    // enhance roles select and branch select if choices-helper available
    try {
        const roleSelect = document.querySelector('select[name="roles[]"]');
        if (roleSelect) initChoices(roleSelect, { removeItems: true, placeholderValue: 'Pilih role' });

        const branchSelect = document.querySelector('select[name="branch_id"]');
        if (branchSelect) initChoices(branchSelect, { shouldSort: false, placeholderValue: 'Pilih branch (opsional)' });
    } catch (err) {
        console.warn('Choices init failed:', err);
    }

    const $form = $('#userForm');
    const $name = $form.find('input[name="name"]');
    const $email = $form.find('input[name="email"]');
    const $password = $form.find('input[name="password"]');
    const $passwordConfirm = $form.find('input[name="password_confirmation"]');

    $form.on('submit', function(e) {
        const nameVal = $name.val().trim();
        const emailVal = $email.val().trim();

        if (!nameVal) {
            e.preventDefault();
            toastError('Nama harus diisi.');
            $name.focus();
            return false;
        }

        if (!emailVal) {
            e.preventDefault();
            toastError('Email harus diisi.');
            $email.focus();
            return false;
        }

        // If password present (on create required, on edit optional), validate confirmation
        if ($password.length && $password.val()) {
            if ($password.val().length < 6) {
                e.preventDefault();
                toastError('Password minimal 6 karakter.');
                $password.focus();
                return false;
            }
            if ($password.val() !== $passwordConfirm.val()) {
                e.preventDefault();
                toastError('Password dan konfirmasi tidak cocok.');
                $passwordConfirm.focus();
                return false;
            }
        }

        // disable submit + spinner
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
