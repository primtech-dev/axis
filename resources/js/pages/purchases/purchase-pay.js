import TomSelect from 'tom-select';

document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('#cashAccountSelect');

    if (!el) return;

    new TomSelect(el, {
        dropdownParent: 'body',
        placeholder: 'Pilih sumber dana...',
        allowEmptyOption: true,
    });
});
