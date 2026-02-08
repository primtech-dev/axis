import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('#customerSelect');
    if (!el) return;

    new TomSelect(el, {
        dropdownParent: 'body',
        placeholder: 'Cari customer...',
        allowEmptyOption: true,
    });
});
