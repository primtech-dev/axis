import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('#saleSelect');
    if (!el) return;

    new TomSelect(el, {
        dropdownParent: 'body',
        placeholder: 'Cari nomor penjualan...',
        allowEmptyOption: true,
    });
});
