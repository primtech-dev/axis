import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

$(function(){
    const table = new DataTable('#stock-receipts-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: window.stockReceiptRoutes.index,
            type: 'GET'
        },
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'public_id', name: 'public_id' },
            { data: 'branch', name: 'branch' },
            { data: 'supplier_name', name: 'supplier_name' },
            { data: 'reference_no', name: 'reference_no' },
            { data: 'status', name: 'status' },
            { data: 'total_items', name: 'total_items' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', orderable:false, searchable:false, className:'text-center'}
        ],
        order: [[6,'desc']],
        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#stock-receipts-table'));
        }
    });

    $(document).on('click', '.js-delete-stock-receipt', function(e){
        e.preventDefault();
        const id = $(this).data('id');
        showDeleteModal({
            modalId: 'deleteStockReceiptModal',
            formId: 'deleteStockReceiptForm',
            id,
            name: $(this).data('name'),
            route: window.stockReceiptRoutes.destroy
        });
    });
});
