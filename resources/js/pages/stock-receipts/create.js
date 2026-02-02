// resources/js/pages/stock-receipts/create.js
import $ from 'jquery';

/* dynamic loaders */
function loadScript(src) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) return resolve();
        const s = document.createElement('script');
        s.src = src;
        s.async = true;
        s.onload = () => resolve();
        s.onerror = () => reject(new Error('Failed to load ' + src));
        document.head.appendChild(s);
    });
}
function loadCss(href) {
    if (document.querySelector(`link[href="${href}"]`)) return;
    const l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = href;
    document.head.appendChild(l);
}

/* ensure select2 loaded once */
let select2Ready = null;
async function ensureSelect2() {
    if (select2Ready) return select2Ready;
    select2Ready = (async () => {
        // expose jQuery globally so Select2 binds to same instance
        window.jQuery = window.$ = $;
        loadCss('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
        if (typeof $().select2 !== 'function') {
            await loadScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        }
        return true;
    })();
    return select2Ready;
}

/* init branch select (explicit, show options) */
async function initBranchSelect() {
    try {
        await ensureSelect2();
        const $branch = $('#branch_id');
        if (!$branch.length) return;
        // only init once
        if ($branch.data('select2-initialized')) return;
        // Determine options from data attributes
        const allowClear = $branch.data('allow-clear') === 'false' ? false : ($branch.data('allow-clear') === false ? false : true);
        const noPlaceholder = $branch.data('no-placeholder') ? true : false;

        $branch.select2({
            width: '100%',
            allowClear: allowClear,
            placeholder: noPlaceholder ? null : ($branch.data('placeholder') || '— Pilih cabang —'),
            // dropdownParent: document.body // uncomment if modals cause z-index issues
        });
        $branch.data('select2-initialized', true);

        // If no value selected but there are options, set first non-empty option as visual hint? (optional)
        // We won't auto-select value; keep placeholder if explicit empty option exists.
    } catch (err) {
        console.warn('initBranchSelect failed', err);
    }
}

/* init variant select (ajax) for a given element */
function initVariantSelect($select) {
    if (!($select && $select.length)) return;
    if (typeof $().select2 !== 'function') {
        console.warn('Select2 not ready for variant select');
        return;
    }
    if ($select.data('select2-initialized')) return;

    const ajaxUrl = window.stockReceiptVariantSearchUrl || '/stock-receipts/variant-search';

    $select.select2({
        placeholder: 'Cari variant (SKU / variant_name / produk)',
        ajax: {
            url: ajaxUrl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term || '',
                    branch_id: $('#branch_id').val() || ''
                };
            },
            processResults: function (data) {
                if (!data) return { results: [] };
                if (Array.isArray(data.results)) return { results: data.results };
                const list = data.items || data.variants || [];
                return {
                    results: list.map(it => ({
                        id: it.id,
                        text: it.text || it.variant_name || it.sku || `${it.sku || ''} ${it.variant_name || ''}`.trim(),
                        sku: it.sku,
                        variant_name: it.variant_name
                    }))
                };
            },
            cache: true,
            error: function (jqXHR) {
                console.error('Variant search error', jqXHR);
            }
        },
        minimumInputLength: 1,
        width: '100%'
    });

    $select.data('select2-initialized', true);
}

/* add item row */
function addItemRow(prefill = {}) {
    const $items = $('#items-wrapper');
    const tpl = document.getElementById('item-row-template').content;
    $items.append(tpl.cloneNode(true));
    const $row = $items.children().last();
    const $sel = $row.find('.variant-select');

    // ensure select2 lib loaded then init variant select
    ensureSelect2()
        .then(() => initVariantSelect($sel))
        .then(() => {
            if (prefill.variant_id) {
                const optionText = prefill.text || prefill.variant_name || prefill.sku || prefill.variant_id;
                const option = new Option(optionText, prefill.variant_id, true, true);
                $sel.append(option).trigger('change');
            }
        })
        .catch((err) => {
            console.warn('Select2 not available for new row', err);
        });

    $row.find('.btnRemoveItem').on('click', function () {
        $row.remove();
    });

    return $row;
}

function reindexItemRows() {
    $('#items-wrapper .item-row').each(function(index) {
        const $row = $(this);

        // variant
        $row.find('.variant-select')
            .attr('name', `items[${index}][variant_id]`);

        // quantity
        $row.find('.qty-input')
            .attr('name', `items[${index}][quantity_received]`);
    });
}

/* duplicate last row */
function duplicateLastRow() {
    const $last = $('#items-wrapper .item-row').last();
    if (!$last.length) return addItemRow();
    const variantId = $last.find('.variant-select').val();
    const variantText = $last.find('.variant-select option:selected').text() || null;
    const qty = $last.find('.qty-input').val() || 1;

    const $new = addItemRow({ variant_id: variantId, text: variantText });
    if ($new) {
        $new.find('.qty-input').val(qty);
    }
}

/* init main */
$(function () {
    // load select2 and init branch select asap
    ensureSelect2().then(() => {
        initBranchSelect();
    }).catch(() => {
        console.warn('Select2 failed to load; continuing without it');
    }).finally(() => {
        // ensure at least one item row exists
        if ($('#items-wrapper .item-row').length === 0) addItemRow();

        // handlers
        $('#btnAddItem').on('click', (e) => { e.preventDefault(); addItemRow(); });
        $('#btnAddItemCopyLast').on('click', (e) => { e.preventDefault(); duplicateLastRow(); });

        // When branch changes, clear variant selection values (but keep branch options)
        $('#branch_id').on('change', function () {
            $('#items-wrapper .variant-select').each(function () {
                const $s = $(this);
                // clear only selection, don't remove list of options of branch select
                $s.val(null).trigger('change');
                // mark for re-init so ajax will use new branch param when typed
                $s.data('select2-initialized', false);
                ensureSelect2().then(() => initVariantSelect($s)).catch(()=>{});
            });
        });

        // submit validation
        $('#stockReceiptForm').on('submit', function (e) {

            // 1) Reindex sebelum submit
            reindexItemRows();

            // 2) Basic validation
            const rows = $('#items-wrapper .item-row');
            if (!rows.length) {
                e.preventDefault();
                if (window.toast) window.toast.error('Minimal 1 item harus diisi.');
                return false;
            }

            let ok = true;

            rows.each(function () {
                const variantVal = $(this).find('.variant-select').val();
                const qtyVal = $(this).find('.qty-input').val();

                if (!variantVal) ok = false;
                if (!qtyVal || isNaN(qtyVal) || parseInt(qtyVal) <= 0) ok = false;
            });

            if (!ok) {
                e.preventDefault();
                if (window.toast) window.toast.error('Periksa kembali variant dan quantity.');
                return false;
            }

            // 3) Disable button to prevent double submit
            const $btn = $(this).find('button[type="submit"]').first();
            $btn.prop('disabled', true);
            $btn.html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...'
            );

            return true;
        });

    });
});

/* debug helpers */
window.__stockReceiptDebug = {
    branchOptionsCount: () => $('#branch_id option').length,
    isSelect2: () => typeof $().select2 === 'function',
    variantSelects: () => $('.variant-select').length
};
