import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

let table; // ⬅️ WAJIB GLOBAL

$(function () {

    table = new DataTable('#suppliers-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: window.supplierRoutes.index,
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'type' },
            { data: 'phone' },
            { data: 'is_active', orderable: false, searchable: false },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[5, 'desc']],
        drawCallback: function () {
            if (window.lucide) window.lucide.replace();
            initTooltips(document.querySelector('#suppliers-table'));
        }
    });

    initTooltips(document);
});

/* =========================
 * TOGGLE ACTIVE — INI YANG HILANG ❗
 * ========================= */
$(document).on('click', '.js-toggle-active', function () {

    const id = $(this).data('id');

    $.ajax({
        url: window.supplierRoutes.toggleActive.replace(':id', id),
        type: 'PUT',
        data: {
            _token: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        },
        success: function (res) {

            if (window.toast?.success) {
                window.toast.success(res.message || 'Status supplier berhasil diubah');
            }

            table.ajax.reload(null, false);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            if (window.toast?.error) {
                window.toast.error('Gagal mengubah status supplier');
            }
        }
    });
});
