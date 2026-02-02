import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

// optional: ensure window.lucide available elsewhere (entry)
window.btnDeleteBranch = function (id, title) {
    showDeleteModal({
        modalId: 'deleteBranchModal',
        formId: 'deleteBranchForm',
        itemNameId: 'delete_branch_title',
        id,
        name: title,
        route: window.branchRoutes.destroy
    });
};

$(function() {
    if (!window.branchRoutes || !window.branchRoutes.index) {
        console.error('branchRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#branches-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.branchRoutes.index,
            type: 'GET',
            dataType: 'json',
            cache: false,
            error: function(xhr, textStatus, errorThrown) {
                console.error('DataTables AJAX error:', textStatus, errorThrown);
                console.error('Response:', xhr.responseText);
                if (window.toast) window.toast.error('Gagal memuat data cabang. Cek console.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'city', name: 'city' },
            { data: 'phone', name: 'phone' },
            { data: 'is_active', name: 'is_active', className: 'text-center', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[6, 'desc']],
        drawCallback: function() {
            // 1) jika pakai lucide, replace icon dulu
            try {
                if (window.lucide && typeof window.lucide.replace === 'function') {
                    window.lucide.replace();
                }
            } catch (e) {
                console.warn('lucide.replace error', e);
            }

            // 2) init tooltips pada tabel (scope-limited)
            initTooltips(document.querySelector('#branches-table'));
        }
    });

    // Delegated click handler untuk tombol delete (ada di kolom action)
    $(document).on('click', '.js-delete-branch', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        showDeleteModal({
            modalId: 'deleteBranchModal',
            formId: 'deleteBranchForm',
            itemNameId: 'delete_branch_title',
            id,
            name,
            route: window.branchRoutes.destroy
        });
    });

    // initial run (tooltips/icons) untuk tombol yang mungkin ada sebelum table draw
    try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
    initTooltips(document);
});
