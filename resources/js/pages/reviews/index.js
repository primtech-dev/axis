import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

$(function () {
    new DataTable('#reviewsTable', {
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: window.reviewRoutes.index,

        // ✅ default sort terbaru TANPA sentuh controller
        order: [[6, 'desc']], // created_at column index

        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'product_name', defaultContent:'-' },
            { data: 'customer_name', defaultContent:'-' },
            { data: 'order_no', defaultContent:'-' },
            { data: 'rating', orderable:false, searchable:false },
            { data: 'status', orderable:false },
            { data: 'created_at' },
            { data: 'action', orderable:false, searchable:false }
        ]
    });
});
