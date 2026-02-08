import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

let table;

$(function () {

    table = new DataTable('#products-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: window.productRoutes.index,
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'sku' },
            { data: 'name' },
            { data: 'category_name' },
            { data: 'unit_name' },
            { data: 'price_buy_default' },
            { data: 'price_sell_default' },
            { data: 'is_active', orderable: false, searchable: false },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],
        order: [[8, 'desc']],
        drawCallback: function () {
            if (window.lucide) window.lucide.replace();
            initTooltips(document.querySelector('#products-table'));
        }
    });
});

/* =========================
 * TOGGLE ACTIVE
 * ========================= */
$(document).on('click', '.js-toggle-active', function () {

    const id = $(this).data('id');

    $.ajax({
        url: window.productRoutes.toggleActive.replace(':id', id),
        type: 'PUT',
        data: {
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },
        success: function (res) {
            if (window.toast?.success) {
                window.toast.success(res.message);
            }
            table.ajax.reload(null, false);
        },
        error: function () {
            if (window.toast?.error) {
                window.toast.error('Gagal mengubah status produk');
            }
        }
    });
});
