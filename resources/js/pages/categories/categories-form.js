/**
 * resources/js/pages/categories/categories-form.js
 *
 * Dipakai untuk Create & Edit Kategori.
 * - Inisialisasi select parent dengan choices (jika tersedia)
 * - Auto-generate slug dari nama jika slug kosong
 * - Validasi sederhana client-side
 * - Disable submit button & show spinner saat submit
 */

import $ from 'jquery';
import { initChoices } from '../../utils/choices-helper'; // pastikan helper ini ada

function toastError(msg) {
    if (window.toast && typeof window.toast.error === 'function') {
        window.toast.error(msg);
    } else {
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

function simpleSlugify(str) {
    if (!str) return '';
    return str.toString()
        .normalize('NFKD')
        .replace(/[^\w\s-]/g, '')   // remove non word/space/dash
        .trim()
        .replace(/\s+/g, '-')       // spaces -> dash
        .replace(/-+/g, '-')        // collapse dashes
        .toLowerCase();
}

function isNumeric(value) {
    if (value === null || value === undefined) return false;
    return !isNaN(value) && isFinite(value);
}

$(function () {
    // init Choices for parent select
    try {
        const parentSelect = document.querySelector('select[name="parent_id"]');
        if (parentSelect) {
            initChoices(parentSelect, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                placeholderValue: 'Pilih parent (opsional)',
                removeItems: false,
                maxItemCount: 1,
                allowHTML: false,
            });
        }
    } catch (err) {
        console.warn('Choices init failed:', err);
    }

    // Elements
    const $name = $('#name');
    const $slug = $('#slug');
    const $position = $('#position');
    const $form = $('#categoryForm');

    // Auto-generate slug when name changes (only when slug is empty)
    $name.on('input', function () {
        const nameVal = $(this).val().trim();
        if (!$slug.length) return;
        if (!$slug.val().trim()) {
            $slug.val(simpleSlugify(nameVal));
        }
    });

    // Validate position numeric on blur
    $position.on('blur', function () {
        const val = $(this).val();
        if (val === '' || isNumeric(val)) return;
        toastError('Posisi harus berupa angka.');
        $(this).val('0').focus();
    });

    // Form submit: basic checks
    $form.on('submit', function (e) {
        const nameVal = $name.val().trim();
        const slugVal = $slug.val().trim();

        if (!nameVal) {
            e.preventDefault();
            toastError('Nama kategori harus diisi.');
            $name.focus();
            return false;
        }

        // optional: slug pattern check
        if (slugVal && !/^[a-z0-9\-]+$/.test(slugVal)) {
            e.preventDefault();
            toastError('Slug hanya boleh huruf kecil, angka, dan tanda minus (-).');
            $slug.focus();
            return false;
        }

        // disable submit + show spinner
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
