import TomSelect from 'tom-select';
import $ from 'jquery';

let rowIndex = 0;

$(function () {

    document.querySelectorAll('.tom-select').forEach(el => {
        new TomSelect(el, {
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            }
        });
    });

});

function formatNumber(value) {
    if (isNaN(value)) value = 0;

    const parts = value.toString().split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return parts.join(',');
}

function parseNumber(value) {
    if (!value) return 0;

    return parseFloat(
        value
            .toString()
            .replace(/\./g, '')
            .replace(',', '.')
    ) || 0;
}

$(function () {

    const $tbody = $('#itemsTable tbody');
    const $subtotal = $('#subtotal');
    const $grandTotal = $('#grandTotal');
    const $totalText = $('#totalText');

    function recalc() {
        let total = 0;

        $tbody.find('tr').each(function () {
            const $row = $(this);

            const qtyRaw = parseNumber($row.find('.qty').val());
            const priceRaw = parseNumber($row.find('.price').val());

            const rowSubtotal = qtyRaw * priceRaw;
            total += rowSubtotal;

            // subtotal boleh rapi
            $row.find('.subtotal').val(
                formatNumber(rowSubtotal.toFixed(2))
            );
        });

        $subtotal.val(total);
        $grandTotal.val(total);
        $totalText.text(formatNumber(total.toFixed(2)));
    }


    function addRow() {

        const index = rowIndex++;

        $tbody.append(`
            <tr>
                <td>
                    <input type="text"
                        name="items[${index}][name]"
                        class="form-control"
                        required>
                </td>
                <td>
                    <input type="text"
                        name="items[${index}][qty]"
                        class="form-control qty text-end"
                        value="0">
                </td>
                <td>
                    <input type="text"
                        name="items[${index}][price]"
                        class="form-control price text-end"
                        value="0">
                </td>
                <td>
                    <input type="text"
                        name="items[${index}][subtotal]"
                        class="form-control subtotal text-end"
                        readonly>
                </td>
                <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-danger removeRow">×</button>
                </td>
            </tr>
        `);

        recalc();
    }


    // Format saat user ngetik
    $tbody.on('input', '.qty, .price', function () {
        const raw = parseNumber(this.value);

        // tampilkan ribuan TANPA maksa decimal
        this.value = formatNumber(raw);

        recalc();
    });

    $tbody.on('click', '.removeRow', function () {
        $(this).closest('tr').remove();
        recalc();
    });

    $('#addRow').on('click', addRow);

    // Submit → pastikan value KIRIM RAW
    $('#expenseForm').on('submit', function () {

        // 🔥 PAKSA HITUNG DULU
        recalc();

        // kirim angka mentah ke backend
        $tbody.find('.qty, .price, .subtotal').each(function () {
            this.value = parseNumber(this.value);
        });

    });

    $('#paymentType').on('change', function () {
        const val = $(this).val();

        $('#dueDateWrapper').toggleClass('d-none', val !== 'CREDIT');
        $('#cashAccountWrapper').toggleClass('d-none', val !== 'CASH');
    }).trigger('change');

    addRow();
});
