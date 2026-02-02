// resources/js/pages/products/products.js
import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

$(function() {
    if (!window.productRoutes || !window.productRoutes.index) {
        console.error('productRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#products-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.productRoutes.index,
            type: 'GET',
            dataType: 'json',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name', name: 'name' },
            {
                data: 'unit',
                name: 'unit',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (!data) return '-';
                    // if server returned object accidentally, handle it
                    if (typeof data === 'object') {
                        const code = data.code ? (data.code + ' • ') : '';
                        return code + (data.name || '-');
                    }
                    return data; // string already formatted
                }
            },
            { data: 'sku', name: 'sku' },
            { data: 'variants_count', name: 'variants_count', orderable:false, searchable:false },
            { data: 'is_active', name: 'is_active', className: 'text-center', orderable:false, searchable:false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable:false, searchable:false, className: 'text-center' }
        ],
        order: [[6,'desc']],

        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#products-table'));
        }
    });

    $(document).on('click', '.js-delete-product', function(e) {
        e.preventDefault();
        const id = $(this).data('id'), name = $(this).data('name') || '';
        showDeleteModal({
            modalId: 'deleteProductModal',
            formId: 'deleteProductForm',
            itemNameId: 'delete_product_title',
            id, name,
            route: window.productRoutes.destroy
        });
    });
});
