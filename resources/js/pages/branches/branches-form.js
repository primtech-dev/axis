/**
 * resources/js/pages/admin/branches-form.js
 *
 * Dipakai untuk Create & Edit Cabang.
 * - Memakai initChoices helper untuk manager select (jika ada)
 * - Auto-generate kode cabang dari nama (jika field kode kosong)
 * - Validasi input latitude/longitude/phone sebelum submit
 * - Disable submit button & show spinner saat submit
 */

import $ from 'jquery';
import { initChoices } from '../../utils/choices-helper';

// Helper: show toast if available
function toastError(msg) {
    if (window.toast && typeof window.toast.error === 'function') {
        window.toast.error(msg);
    } else {
        // fallback console
        console.warn('Toast not available:', msg);
    }
}

function toastSuccess(msg) {
    if (window.toast && typeof window.toast.success === 'function') {
        window.toast.success(msg);
    } else {
        console.log('Success:', msg);
    }
}

// Generate branch code from name
// Example: "Kencana Jakarta Timur" -> "KNC-JAKARTA-TIMUR"
// If name is short it becomes "KNC-<NAME>"
function generateBranchCode(name) {
    if (!name) return '';

    // Normalize: remove non-alphanumeric (allow spaces and dash), replace spaces with dash
    const cleaned = name
        .toString()
        .normalize('NFKD')               // normalize accents
        .replace(/[^\w\s-]/g, '')        // remove non word/space/dash
        .trim()
        .replace(/\s+/g, '-')            // spaces -> dash
        .replace(/-+/g, '-')             // collapse multiple dashes
        .toUpperCase();

    // Prefix KNC- for brand identity, but avoid duplicate if user already types KNC-
    const prefix = cleaned.startsWith('KNC-') ? '' : 'KNC-';
    const code = `${prefix}${cleaned}`;

    // limit length to 32 characters
    return code.substring(0, 32);
}

// Validate float number (latitude/longitude)
function isNumeric(value) {
    if (value === null || value === undefined) return false;
    return !isNaN(value) && isFinite(value);
}

// Phone basic validation: allow numbers, +, -, space, parentheses
function isValidPhone(value) {
    if (!value) return true; // optional field
    const cleaned = value.trim();
    // simple pattern: digits and common symbols
    return /^[0-9+\-\s()]{4,20}$/.test(cleaned);
}

$(function () {
    // Init Choices for manager select (single)
    try {
        const managerSelect = document.querySelector('select[name="manager_admin_user_id"]');
        if (managerSelect) {
            initChoices(managerSelect, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                placeholderValue: 'Pilih manager (opsional)',
                removeItems: false,
                maxItemCount: 1,
                allowHTML: false,
            });
        }
    } catch (err) {
        console.warn('Choices init failed:', err);
    }

    // Auto-generate code from name if code field is empty
    const $name = $('#name');
    const $code = $('#code');

    if ($name.length && $code.length) {
        $name.on('input', function () {
            const currentCode = $code.val();
            // only auto-fill if code is empty OR if code was previously auto-filled
            // detect auto-filled by checking prefix KNC- or if code equals previous generated
            // For simplicity: only auto-fill when code is empty
            if (!currentCode || currentCode.trim() === '') {
                const generated = generateBranchCode($(this).val());
                $code.val(generated);
            }
        });
    }

    // Validate latitude/longitude on blur
    $('#latitude, #longitude').on('blur', function () {
        const val = $(this).val().trim();
        if (val === '') return; // optional
        if (!isNumeric(val)) {
            toastError('Latitude/Longitude harus berupa angka (contoh: -6.200000).');
            $(this).val('');
            $(this).focus();
        }
    });

    // Validate phone on blur
    $('#phone').on('blur', function () {
        const val = $(this).val().trim();
        if (val === '') return;
        if (!isValidPhone(val)) {
            toastError('Format nomor telepon tidak valid. Gunakan angka dan simbol + - () jika perlu.');
            $(this).focus();
        }
    });

    // Form submit handler: validate few fields client-side and show loading state
    $('#branchForm').on('submit', function (e) {
        // Basic client-side checks
        // Required: code, name
        const codeVal = $('#code').val().trim();
        const nameVal = $('#name').val().trim();

        if (!codeVal) {
            e.preventDefault();
            toastError('Kode cabang harus diisi.');
            $('#code').focus();
            return false;
        }

        if (!nameVal) {
            e.preventDefault();
            toastError('Nama cabang harus diisi.');
            $('#name').focus();
            return false;
        }

        // lat/long numeric
        const latVal = $('#latitude').val().trim();
        const longVal = $('#longitude').val().trim();
        if (latVal && !isNumeric(latVal)) {
            e.preventDefault();
            toastError('Latitude harus berupa angka.');
            $('#latitude').focus();
            return false;
        }
        if (longVal && !isNumeric(longVal)) {
            e.preventDefault();
            toastError('Longitude harus berupa angka.');
            $('#longitude').focus();
            return false;
        }

        // phone format
        const phoneVal = $('#phone').val().trim();
        if (phoneVal && !isValidPhone(phoneVal)) {
            e.preventDefault();
            toastError('Format nomor telepon tidak valid.');
            $('#phone').focus();
            return false;
        }

        // disable submit button & show spinner
        const $btn = $(this).find('button[type="submit"]').first();
        $btn.prop('disabled', true);

        // detect if this is create or edit by presence of method input _method=PUT
        const isEdit = $(this).find('input[name="_method"][value="PUT"]').length > 0;
        const text = isEdit ? 'Menyimpan perubahan...' : 'Membuat cabang...';

        $btn.data('orig-html', $btn.html());
        $btn.html(`<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> ${text}`);

        // let form submit proceed
        return true;
    });

    // If there are validation errors passed from server, optionally show toast(s)
    if (typeof window.serverValidationErrors !== 'undefined' && Array.isArray(window.serverValidationErrors)) {
        window.serverValidationErrors.forEach(err => toastError(err));
    }
});
