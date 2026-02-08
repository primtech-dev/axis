import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

$(function () {

    const table = $('#accounts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.accountRoutes.index,
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'code' },
            { data: 'name' },
            { data: 'type' },
            { data: 'is_active' },
            { data: 'action', orderable: false, searchable: false },
        ],
        drawCallback: function () {
            initTooltips(); // 🔥 INI KUNCI
        }
    });

    // delete
    $(document).on('click', '.js-delete-account', function () {
        const id = $(this).data('id');
        const form = $('#deleteAccountForm');
        form.attr('action', window.accountRoutes.destroy.replace(':id', id));
        $('#deleteAccountModal').modal('show');
    });

});

/* =========================
 * TOOLTIP INIT
 * ========================= */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    tooltipTriggerList.forEach(el => {
        if (!el._tooltipInstance) {
            el._tooltipInstance = new bootstrap.Tooltip(el);
        }
    });
}
