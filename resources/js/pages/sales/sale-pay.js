import TomSelect from 'tom-select';

function formatNumber(value) {
    return value
        .toString()
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseNumber(value) {
    return parseFloat(value.replace(/\./g, '')) || 0;
}

document.addEventListener('DOMContentLoaded', () => {

    /* =========================
     * TOM SELECT
     * ========================= */
    const el = document.querySelector('#cashAccountSelect');
    if (el) {
        new TomSelect(el, {
            dropdownParent: 'body',
            placeholder: 'Pilih sumber dana...',
            allowEmptyOption: true,
        });
    }

    /* =========================
     * AMOUNT FORMATTER
     * ========================= */
    const displayInput = document.querySelector('#amount_display');
    const realInput    = document.querySelector('#amount');

    if (!displayInput || !realInput) return;

    // format saat ngetik
    displayInput.addEventListener('input', () => {
        const raw = parseNumber(displayInput.value);
        realInput.value = raw;
        displayInput.value = formatNumber(raw);
    });

    // pastikan sebelum submit
    displayInput
        .closest('form')
        .addEventListener('submit', () => {
            realInput.value = parseNumber(displayInput.value);
        });
});
