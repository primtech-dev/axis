import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

let table;

$(function () {

    table = new DataTable('#payment-methods-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: window.paymentMethodRoutes.index,
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'category' },
            { data: 'sort_order' },
            { data: 'is_active', orderable: false, searchable: false },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[4, 'asc']],
        drawCallback: function () {
            if (window.lucide) window.lucide.replace();
            initTooltips(document.querySelector('#payment-methods-table'));
        }
    });
});

/* =========================
 * TOGGLE ACTIVE
 * ========================= */
$(document).on('click', '.js-toggle-active', function () {

    const id = $(this).data('id');

    $.ajax({
        url: window.paymentMethodRoutes.toggleActive.replace(':id', id),
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
                window.toast.error('Gagal mengubah status payment method');
            }
        }
    });
});

/* =========================
 * DELETE
 * ========================= */
$(document).on('click', '.js-delete-payment-method', function () {

    showDeleteModal({
        modalId: 'deletePaymentMethodModal',
        formId: 'deletePaymentMethodForm',
        id: $(this).data('id'),
        name: $(this).data('name'),
        route: window.paymentMethodRoutes.destroy
    });
});
