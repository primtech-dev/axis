import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

/**
 * Global helper (opsional, konsisten dengan modul lain)
 */
window.btnDeleteCategory = function (id, title) {
    showDeleteModal({
        modalId: 'deleteCategoryModal',
        formId: 'deleteCategoryForm',
        itemNameId: 'delete_category_title',
        id,
        name: title,
        route: window.categoryRoutes.destroy
    });
};

$(function () {

    if (!window.categoryRoutes || !window.categoryRoutes.index) {
        console.error('categoryRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#categories-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,

        ajax: {
            url: window.categoryRoutes.index,
            type: 'GET',
            dataType: 'json',
            cache: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error(
                    'DataTables AJAX error:',
                    textStatus,
                    errorThrown,
                    xhr.responseText
                );
                if (window.toast) {
                    window.toast.error('Gagal memuat data kategori.');
                }
            }
        },

        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                width: '5%'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'created_at',
                name: 'created_at',
                width: '20%'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '12%'
            }
        ],

        order: [[2, 'desc']], // sort by created_at desc

        drawCallback: function () {
            // re-render icons
            try {
                if (window.lucide && typeof window.lucide.replace === 'function') {
                    window.lucide.replace();
                }
            } catch (e) {}

            // re-init tooltip for dynamic rows
            initTooltips(document.querySelector('#categories-table'));
        }
    });

    /**
     * Delete button handler (delegated)
     */
    $(document).on('click', '.js-delete-category', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const name = $(this).data('name') || '';

        showDeleteModal({
            modalId: 'deleteCategoryModal',
            formId: 'deleteCategoryForm',
            itemNameId: 'delete_category_title',
            id,
            name,
            route: window.categoryRoutes.destroy
        });
    });

    // initial icons & tooltips (first load)
    try {
        if (window.lucide && typeof window.lucide.replace === 'function') {
            window.lucide.replace();
        }
    } catch (e) {}

    initTooltips(document);
});
