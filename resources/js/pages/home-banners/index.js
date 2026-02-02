import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

window.btnDeleteBanner = function (id, name) {
    showDeleteModal({
        modalId: 'deleteBannerModal',
        formId: 'deleteBannerForm',
        itemNameId: 'delete_banner_title',
        id,
        name,
        route: window.bannerRoutes.destroy
    });
};

$(function () {
    const table = new DataTable('#homeBannersTable', {
        processing: true,
        serverSide: true,
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        autoWidth: false,
        ajax: {
            url: window.bannerRoutes.index,
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', orderable: false, searchable: false },
            { data: 'code' },
            { data: 'title' },
            { data: 'sort_order', className: 'text-center' },
            { data: 'status', className: 'text-center', orderable: false },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[4, 'asc']],
        drawCallback: function () {
            try {
                if (window.lucide) window.lucide.replace();
            } catch (e) {}
            initTooltips(document.querySelector('#homeBannersTable'));
        }
    });

    // delete handler
    $(document).on('click', '.js-delete-banner', function () {
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        btnDeleteBanner(id, name);
    });
});
