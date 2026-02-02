import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

window.btnDeleteUser = function (id, title) {
    showDeleteModal({
        modalId: 'deleteUserModal',
        formId: 'deleteUserForm',
        itemNameId: 'delete_user_title',
        id,
        name: title,
        route: window.userRoutes.destroy
    });
};

$(function() {
    if (!window.userRoutes || !window.userRoutes.index) {
        console.error('userRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#users-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.userRoutes.index,
            type: 'GET',
            dataType: 'json',
            cache: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            xhrFields: { withCredentials: true },
            beforeSend: function() {
                // optional debug
                console.log('DataTables: requesting users JSON from', window.userRoutes.index);
            },
            dataFilter: function(data) {
                // try parse & log if invalid JSON
                try {
                    JSON.parse(data);
                    return data;
                } catch (e) {
                    console.error('DataTables: response is not JSON', data);
                    return data;
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error('DataTables AJAX error:', textStatus, errorThrown, xhr.status, xhr.responseText);
                if (window.toast) window.toast.error('Gagal memuat data user. Cek console.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'branch', name: 'branch', orderable: false, searchable: false },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'is_superadmin', name: 'is_superadmin', orderable: false, searchable: false, className: 'text-center' },
            { data: 'is_active', name: 'is_active', orderable: false, searchable: false, className: 'text-center' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[7, 'desc']],
        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#users-table'));
        }
    });

    $(document).on('click', '.js-delete-user', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        showDeleteModal({
            modalId: 'deleteUserModal',
            formId: 'deleteUserForm',
            itemNameId: 'delete_user_title',
            id,
            name,
            route: window.userRoutes.destroy
        });
    });

    // initial icons/tooltips
    try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
    initTooltips(document);
});
