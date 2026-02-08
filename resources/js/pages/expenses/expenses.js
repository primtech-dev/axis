import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { initTooltips } from '../../utils/tooltip-helper';

$(function () {

    new DataTable('#expenses-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: window.expenseRoutes.index,

        columns: [
            { data: 'DT_RowIndex', orderable: false },
            { data: 'date' },
            { data: 'purchase' },
            { data: 'supplier' },
            { data: 'grand_total' },
            { data: 'status', orderable: false },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false },
        ],

        order: [[1, 'desc']],

        drawCallback: function () {
            if (window.lucide) window.lucide.replace();
            initTooltips(document.querySelector('#expenses-table'));
        }
    });

});
