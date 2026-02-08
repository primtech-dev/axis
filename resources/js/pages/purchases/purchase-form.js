import $ from 'jquery';
import TomSelect from 'tom-select';

let rowIndex = 0;

/* =========================
 * TOGGLE PAYMENT TYPE
 * ========================= */
function togglePaymentFields() {
    const type = $('#paymentType').val();

    if (type === 'CASH') {
        $('#cashAccountWrapper').removeClass('d-none');
        $('#dueDateWrapper').addClass('d-none');
        $('#dueDate').val('');
    } else {
        $('#cashAccountWrapper').addClass('d-none');
        $('#cashAccountSelect').val('');
        $('#dueDateWrapper').removeClass('d-none');
    }
}

/* =========================
 * FORMATTER
 * ========================= */
function formatNumber(value) {
    return value
        .toString()
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function parseNumber(value) {
    return parseFloat(value.replace(/\./g, '')) || 0;
}

/* =========================
 * INIT SELECT
 * ========================= */
new TomSelect('#supplierSelect', {
    dropdownParent: 'body',
    placeholder: 'Cari supplier...',
});

new TomSelect('#cashAccountSelect', {
    dropdownParent: 'body',
    placeholder: 'Pilih sumber dana...',
});

function initProductSelect(el) {
    new TomSelect(el, {
        dropdownParent: 'body',
        placeholder: 'Cari produk...',
    });
}

/* =========================
 * ROW TEMPLATE
 * ========================= */
function rowTemplate(index) {
    return `
    <tr>
        <td>
            <select name="items[${index}][product_id]"
                    class="form-select product-select">
                <option value="">— Pilih Produk —</option>
                ${window.products.map(p =>
                    `<option value="${p.id}">${p.name}</option>`
                ).join('')}
            </select>
        </td>

        <td>
            <input type="number" step="0.0001"
                   name="items[${index}][qty]"
                   class="form-control qty" value="1">
        </td>

        <td>
            <input type="text"
                   class="form-control price-display"
                   value="0">
            <input type="hidden"
                   name="items[${index}][price]"
                   class="price-raw" value="0">
        </td>

        <td>
            <input type="text"
                   class="form-control subtotal-display"
                   readonly>
            <input type="hidden"
                   name="items[${index}][subtotal]"
                   class="subtotal-raw" value="0">
        </td>

        <td class="text-center">
            <button type="button"
                    class="btn btn-sm btn-outline-danger removeRow">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    </tr>`;
}

/* =========================
 * RECALC
 * ========================= */
function recalc() {
    let subtotal = 0;

    $('#itemsTable tbody tr').each(function () {
        const qty = parseFloat($(this).find('.qty').val()) || 0;
        const price = parseNumber($(this).find('.price-display').val());

        $(this).find('.price-raw').val(price);

        const rowSubtotal = qty * price;

        $(this).find('.subtotal-raw').val(rowSubtotal.toFixed(2));
        $(this).find('.subtotal-display')
            .val(formatNumber(rowSubtotal.toFixed(0)));

        subtotal += rowSubtotal;
    });

    $('#subtotal').val(subtotal.toFixed(2));
    $('#subtotal_display').val(formatNumber(subtotal.toFixed(0)));

    const discount = parseNumber($('#discount_display').val());
    const tax = parseNumber($('#tax_display').val());

    $('#discount').val(discount);
    $('#tax').val(tax);

    const grandTotal = subtotal - discount + tax;

    $('#grand_total').val(grandTotal.toFixed(2));
    $('#grand_total_display').val(formatNumber(grandTotal.toFixed(0)));
}

/* =========================
 * EVENTS
 * ========================= */
$(function () {

    $('#paymentType').on('change', togglePaymentFields);
    togglePaymentFields();

    $('#addRow').on('click', function () {
        $('#itemsTable tbody').append(rowTemplate(rowIndex));

        const selectEl = document
            .querySelector('#itemsTable tbody tr:last-child .product-select');

        initProductSelect(selectEl);
        rowIndex++;
    });

    $(document).on(
        'input',
        '.qty, .price-display, #discount_display, #tax_display',
        recalc
    );

    $(document).on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        recalc();
    });

    // default 1 row
    $('#addRow').trigger('click');
});
