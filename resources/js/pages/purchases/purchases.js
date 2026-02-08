import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { initTooltips } from '../../utils/tooltip-helper';

let table;

$(function () {

    table = new DataTable('#purchases-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: window.purchaseRoutes.index,

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'invoice_number' },
            { data: 'date' },
            { data: 'supplier', orderable: false },
            { data: 'payment_type', orderable: false, searchable: false },
            { data: 'grand_total' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'created_at' },
            {
                data: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],

        order: [[2, 'desc']],

        drawCallback: function () {
            if (window.lucide) window.lucide.replace();
            initTooltips(document.querySelector('#purchases-table'));
        }
    });

});
