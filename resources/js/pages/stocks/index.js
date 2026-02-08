import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { initTooltips } from '../../utils/tooltip-helper';

$(function () {

    new DataTable('#stocks-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: window.stockRoutes.index,
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'sku' },
            { data: 'name' },
            { data: 'unit' },
            { data: 'stock', className: 'text-end' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' },
        ],
        order: [[2, 'asc']],
        drawCallback: function () {
            initTooltips(document.querySelector('#stocks-table'));
        }
    });

});
