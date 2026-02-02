import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

window.btnDeleteRole = function (id, title) {
    showDeleteModal({
        modalId: 'deleteRoleModal',
        formId: 'deleteRoleForm',
        itemNameId: 'delete_role_title',
        id,
        name: title,
        route: window.roleRoutes.destroy
    });
};

$(function() {
    if (!window.roleRoutes || !window.roleRoutes.index) {
        console.error('roleRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#roles-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.roleRoutes.index,
            type: 'GET',
            dataType: 'json',
            cache: false,
            error: function(xhr, textStatus, errorThrown) {
                console.error('DataTables AJAX error:', textStatus, errorThrown, xhr.responseText);
                if (window.toast) window.toast.error('Gagal memuat data role. Cek console.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[3, 'desc']],
        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#roles-table'));
        }
    });

    $(document).on('click', '.js-delete-role', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        showDeleteModal({
            modalId: 'deleteRoleModal',
            formId: 'deleteRoleForm',
            itemNameId: 'delete_role_title',
            id,
            name,
            route: window.roleRoutes.destroy
        });
    });

    try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
    initTooltips(document);
});
