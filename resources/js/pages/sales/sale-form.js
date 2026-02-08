import $ from 'jquery';
import TomSelect from 'tom-select';

let purchaseIndex = 0;
let selectedPurchaseIds = [];

/* ================= FORMAT ================= */
function num(v) {
    return parseFloat(v) || 0;
}

/* ================= PAYMENT ================= */
function togglePayment() {
    const t = $('#paymentType').val();
    $('#cashAccountWrapper').toggleClass('d-none', t === 'CREDIT');
    $('#dueDateWrapper').toggleClass('d-none', t === 'CASH');
}

/* ================= RECALC ================= */
function recalc() {
    let subtotal = 0;

    $('.item-row').each(function () {

        const qty = parseFloat($(this).find('.qty').val()) || 0;
        const price = parseNumber($(this).find('.price-display').val());

        const rowTotal = qty * price;

        // set raw
        $(this).find('.price-raw').val(price);
        $(this).find('.subtotal-raw').val(rowTotal.toFixed(2));

        // set display
        $(this).find('.subtotal-display')
            .val(formatNumber(Math.round(rowTotal)));

        subtotal += rowTotal;
    });

    // SUBTOTAL
    $('#subtotal').val(subtotal.toFixed(2));
    $('#subtotal_display').val(formatNumber(Math.round(subtotal)));

    // DISKON
    const discount = parseNumber($('#discount_display').val());
    $('#discount').val(discount);

    // PAJAK
    const tax = parseNumber($('#tax_display').val());
    $('#tax').val(tax);

    // GRAND TOTAL
    const grandTotal = subtotal - discount + tax;

    $('#grand_total').val(grandTotal.toFixed(2));
    $('#grand_total_display').val(formatNumber(Math.round(grandTotal)));
}


/* ================= PURCHASE BLOCK ================= */
function purchaseBlock(idx) {
    return `
<div class="border rounded p-3 mb-3 purchase-block" data-index="${idx}">
    <div class="row mb-3 align-items-end">
        <div class="col-md-4">
            <label>No Pembelian</label>
            <select class="form-select purchase-select">
                <option value="">— Pilih Pembelian —</option>
                ${window.availablePurchases.map(p =>
        `<option value="${p.id}">${p.invoice_number}</option>`
    ).join('')}
            </select>
        </div>

        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger removePurchase">
                Hapus
            </button>
        </div>
    </div>

    <div class="item-container"></div>
</div>`;
}

/* ================= LOAD ITEMS ================= */
function loadItems(purchaseId, container, baseIndex) {

    container.html(`
        <div class="mb-2">
            <small class="text-muted">Detail item dari pembelian</small>
        </div>

        <div class="row fw-semibold border-bottom pb-2 mb-2 text-muted">
            <div class="col-md-4">Nama Item</div>
            <div class="col-md-2 text-center">Qty</div>
            <div class="col-md-2 text-center">Satuan</div>
            <div class="col-md-2 text-end">Harga Satuan</div>
            <div class="col-md-2 text-end">Subtotal</div>
        </div>
    `);

    $.get(`/api/purchases/${purchaseId}/items`, function (items) {

        let html = '';

        items.forEach((it, i) => {

            html += `
            <div class="row align-items-center g-2 mb-2 item-row">

                <!-- NAMA ITEM -->
                <div class="col-md-4">
                    <div class="fw-medium">${it.product.name}</div>
                    <small class="text-muted">Dari pembelian</small>
                </div>

                <!-- QTY -->
                <div class="col-md-2">
                    <input type="number"
                        step="0.0001"
                        name="items[${baseIndex}_${i}][qty]"
                        class="form-control qty text-center"
                        value="${parseFloat(it.qty)}">
                </div>

                <!-- SATUAN -->
                <div class="col-md-2 text-center">
                    <span class="badge bg-light text-dark">
                        ${it.product.unit?.name ?? '-'}
                    </span>
                </div>

                <!-- HARGA SATUAN -->
                <div class="col-md-2">
                    <input type="text"
                        class="form-control price-display text-end"
                        value="0">

                    <input type="hidden"
                        name="items[${baseIndex}_${i}][price]"
                        class="price-raw"
                        value="0">
                </div>

                <!-- SUBTOTAL -->
                <div class="col-md-2">
                    <input type="text"
                        class="form-control subtotal-display text-end"
                        readonly>

                    <input type="hidden"
                        name="items[${baseIndex}_${i}][subtotal]"
                        class="subtotal-raw"
                        value="0">
                </div>

                <!-- HIDDEN -->
                <input type="hidden"
                       name="items[${baseIndex}_${i}][purchase_item_id]"
                       value="${it.id}">
                <input type="hidden"
                       name="items[${baseIndex}_${i}][product_id]"
                       value="${it.product_id}">
            </div>
            `;
        });

        container.append(html);
        recalc();
    });
    console.log('Purchase item qty:', it.qty);
}
/* ================= INIT ================= */
$(function () {

    new TomSelect('#customerSelect', { dropdownParent: 'body' });
    new TomSelect('#cashAccountSelect', { dropdownParent: 'body' });

    $('#paymentType').on('change', togglePayment);
    togglePayment();

    $('#addPurchase').on('click', function () {
        const idx = purchaseIndex++;

        $('#purchaseContainer').append(purchaseBlock(idx));

        const block = $(`.purchase-block[data-index="${idx}"]`);
        const select = block.find('.purchase-select')[0];

        new TomSelect(select, {
            dropdownParent: 'body',
            searchField: ['text'],
            placeholder: 'Cari nomor pembelian'
        });
    });

    $(document).on('change', '.purchase-select', function () {

        const select = $(this);
        const purchaseId = select.val();
        const block = select.closest('.purchase-block');
        const idx = block.data('index');
        const container = block.find('.item-container');

        if (!purchaseId) return;

        // 🔒 CEK DUPLIKAT
        if (selectedPurchaseIds.includes(purchaseId)) {
            alert(`Nomor pembelian ${select.find('option:selected').text()} sudah dipilih`);
            select.val('').trigger('change');
            return;
        }

        // kalau sebelumnya sudah pilih, hapus dulu dari list
        const prev = block.data('purchase-id');
        if (prev) {
            selectedPurchaseIds = selectedPurchaseIds.filter(id => id !== prev);
        }

        block.data('purchase-id', purchaseId);
        selectedPurchaseIds.push(purchaseId);

        container.empty();
        loadItems(purchaseId, container, idx);
    });


    $(document).on('input', '.price-display, #discount_display, #tax_display', function () {
        const val = parseNumber($(this).val());
        $(this).val(formatNumber(val));
        recalc();
    });

    $(document).on('click', '.removePurchase', function () {
        const block = $(this).closest('.purchase-block');
        const pid = block.data('purchase-id');

        if (pid) {
            selectedPurchaseIds = selectedPurchaseIds.filter(id => id !== pid);
        }

        block.remove();
        recalc();
    });
});

/* ================= NUMBER FORMAT ================= */

// format 1000000 -> 1.000.000
function formatNumber(value) {
    return value.toString()
        .replace(/\D/g, '')
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// parse 1.000.000 -> 1000000
function parseNumber(value) {
    return parseFloat(value.replace(/\./g, '')) || 0;
}
