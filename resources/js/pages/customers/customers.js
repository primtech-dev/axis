import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

$(function () {

    const table = new DataTable('#customers-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: window.customerRoutes.index,
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'phone' },
            { data: 'is_active', orderable: false, searchable: false },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],

        drawCallback: function () {
            // 🔥 INI YANG SELAMA INI HILANG
            initTooltips(document.querySelector('#customers-table'));

            // icon refresh (kalau pakai tabler/lucide)
            try {
                if (window.lucide?.replace) {
                    window.lucide.replace();
                }
            } catch (e) {}
        }
    });

    $(document).on('click', '.js-delete-customer', function () {
        showDeleteModal({
            modalId: 'deleteCustomerModal',
            formId: 'deleteCustomerForm',
            id: $(this).data('id'),
            name: $(this).data('name'),
            route: window.customerRoutes.destroy
        });
    });

    $(document).on('click', '.js-toggle-active', function () {

        const id = $(this).data('id');

        $.ajax({
            url: window.customerRoutes.toggleActive.replace(':id', id),
            type: 'POST', // ⬅️ pakai POST
            data: {
                _method: 'PUT', // ⬅️ spoof PUT
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function (res) {
                if (res.success) {
                    if (window.toast?.success) {
                        window.toast.success('Status customer diperbarui');
                    }
                    table.ajax.reload(null, false);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                if (window.toast?.error) {
                    window.toast.error('Gagal mengubah status customer');
                }
            }
        });
    });

});
