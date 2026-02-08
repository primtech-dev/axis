import $ from 'jquery';

$(function () {

    const form = document.getElementById('productForm');
    if (!form) return;

    /* =========================
     * TOM SELECT
     * ========================= */
    if (window.TomSelect) {

        new TomSelect('#categorySelect', {
            maxItems: 1,
            placeholder: 'Pilih kategori',
            allowEmptyOption: true,
        });

        new TomSelect('#unitSelect', {
            maxItems: 1,
            placeholder: 'Pilih unit',
            allowEmptyOption: true,
        });
    }

    /* =========================
     * VALIDASI FRONTEND
     * ========================= */
    const $sku  = $('input[name="sku"]');
    const $name = $('input[name="name"]');

    function toastError(msg) {
        if (window.toast?.error) window.toast.error(msg);
        else alert(msg);
    }

    $('#productForm').on('submit', function (e) {

        if (!$sku.val().trim()) {
            e.preventDefault();
            toastError('SKU wajib diisi.');
            $sku.focus();
            return;
        }

        if (!$name.val().trim()) {
            e.preventDefault();
            toastError('Nama produk wajib diisi.');
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
