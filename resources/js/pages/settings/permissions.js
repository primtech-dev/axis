import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

window.btnDeletePermission = function (id, title) {
    showDeleteModal({
        modalId: 'deletePermissionModal',
        formId: 'deletePermissionForm',
        itemNameId: 'delete_permission_title',
        id,
        name: title,
        route: window.permissionRoutes.destroy
    });
};

$(function() {
    if (!window.permissionRoutes || !window.permissionRoutes.index) {
        console.error('permissionRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#permissions-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.permissionRoutes.index,
            type: 'GET',
            dataType: 'json',
            cache: false,
            error: function(xhr, textStatus, errorThrown) {
                console.error('DataTables AJAX error:', textStatus, errorThrown, xhr.responseText);
                if (window.toast) window.toast.error('Gagal memuat data permission. Cek console.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'display_name', name: 'display_name' },
            { data: 'group', name: 'group' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[4, 'desc']],
        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#permissions-table'));
        }
    });

    $(document).on('click', '.js-delete-permission', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        showDeleteModal({
            modalId: 'deletePermissionModal',
            formId: 'deletePermissionForm',
            itemNameId: 'delete_permission_title',
            id,
            name,
            route: window.permissionRoutes.destroy
        });
    });

    try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
    initTooltips(document);
});
