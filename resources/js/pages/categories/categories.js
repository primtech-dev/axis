import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

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

$(function() {
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
            error: function(xhr, textStatus, errorThrown) {
                console.error('DataTables AJAX error:', textStatus, errorThrown, xhr.responseText);
                if (window.toast) window.toast.error('Gagal memuat data kategori. Cek console.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false },
            { data: 'slug', name: 'slug' },
            { data: 'parent', name: 'parent', orderable: false, searchable: false },
            { data: 'position', name: 'position' },
            { data: 'is_active', name: 'is_active', className: 'text-center', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[6, 'desc']],
        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#categories-table'));
        }
    });

    $(document).on('click', '.js-delete-category', function(e) {
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

    // initial icons/tooltips
    try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
    initTooltips(document);
});
