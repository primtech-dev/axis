import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

window.btnDeleteUnit = function (id, title) {
    showDeleteModal({
        modalId: 'deleteUnitModal',
        formId: 'deleteUnitForm',
        itemNameId: 'delete_unit_title',
        id,
        name: title,
        route: window.unitRoutes.destroy
    });
};

$(function () {

    if (!window.unitRoutes || !window.unitRoutes.index) {
        console.error('unitRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#units-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,

        ajax: {
            url: window.unitRoutes.index,
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
                    window.toast.error('Gagal memuat data unit.');
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

        order: [[2, 'desc']], // created_at DESC

        drawCallback: function () {
            try {
                if (window.lucide && typeof window.lucide.replace === 'function') {
                    window.lucide.replace();
                }
            } catch (e) {}

            initTooltips(document.querySelector('#units-table'));
        }
    });

    $(document).on('click', '.js-delete-unit', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const name = $(this).data('name') || '';

        showDeleteModal({
            modalId: 'deleteUnitModal',
            formId: 'deleteUnitForm',
            itemNameId: 'delete_unit_title',
            id,
            name,
            route: window.unitRoutes.destroy
        });
    });

    // initial render
    try {
        if (window.lucide && typeof window.lucide.replace === 'function') {
            window.lucide.replace();
        }
    } catch (e) {}

    initTooltips(document);
});
