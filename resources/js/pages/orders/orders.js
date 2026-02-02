import $ from 'jquery';
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { showDeleteModal } from '../../utils/delete-modal-helper';
import { initTooltips } from '../../utils/tooltip-helper';

window.btnDeleteOrder = function (id, title) {
    showDeleteModal({
        modalId: 'deleteOrderModal',
        formId: 'deleteOrderForm',
        itemNameId: 'delete_order_title',
        id,
        name: title,
        route: window.orderRoutes.destroy
    });
};

window.openChangeStatusModal = function(orderId, orderNo, currentStatus = '') {
    try {
        const modalEl = document.getElementById('changeStatusModal');
        if (!modalEl) {
            console.error('Modal changeStatusModal tidak ditemukan di DOM.');
            return;
        }

        const orderIdInput = document.getElementById('changeStatusOrderId');
        const orderNoSpan = document.getElementById('changeStatusOrderNo');
        const form = document.getElementById('changeStatusForm');
        const statusSelect = document.getElementById('status');

        if (orderIdInput) orderIdInput.value = orderId;
        if (orderNoSpan) orderNoSpan.textContent = orderNo ? `(${orderNo})` : '';

        // set form action from template attribute
        if (form) {
            const tmpl = form.getAttribute('data-action-template');
            if (tmpl) {
                const action = tmpl.replace(':id', orderId);
                form.setAttribute('action', action);
            } else if (window.orderRoutes && window.orderRoutes.updateStatus) {
                const action = window.orderRoutes.updateStatus;
                form.setAttribute('action', action.includes(':id') ? action.replace(':id', orderId) : action.replace(/\/\d+$/, `/${orderId}`));
            }
        }

        // ensure select exists
        if (!statusSelect) {
            console.error('Elemen select #status tidak ditemukan di DOM modal.');
        } else {
            // jika opsi kosong, isi opsi default (robust untuk kondisi partial tidak ter-render)
            if (statusSelect.options.length === 0) {
                const statuses = [
                    { v: 'pending', t: 'Pending' },
                    { v: 'paid', t: 'Paid' },
                    { v: 'processing', t: 'Processing' },
                    { v: 'shipped', t: 'Shipped' },
                    { v: 'complete', t: 'Complete' },
                    { v: 'cancelled', t: 'Cancelled' },
                    { v: 'return', t: 'Return' }
                ];

                // hapus semua (defensive)
                statusSelect.innerHTML = '';

                statuses.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.v;
                    opt.textContent = s.t;
                    statusSelect.appendChild(opt);
                });
            }

            // pilih status saat ini jika disediakan, fallback ke index 0
            if (currentStatus) {
                // jika nilai tidak ada dalam options, tambahkan sebagai option terpilih
                if (![...statusSelect.options].some(o => o.value === currentStatus)) {
                    const opt = document.createElement('option');
                    opt.value = currentStatus;
                    opt.textContent = currentStatus;
                    opt.selected = true;
                    statusSelect.appendChild(opt);
                }
                statusSelect.value = currentStatus;
            } else {
                statusSelect.selectedIndex = 0;
            }
        }

        // show bootstrap modal
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    } catch (e) {
        console.error('openChangeStatusModal error', e);
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('changeStatusModal');
    if (!modalEl) return;

    let choicesInstance = null;

    modalEl.addEventListener('shown.bs.modal', function () {
        const sel = document.getElementById('status');
        if (!sel) return console.warn('status select not found on modal shown');

        // If Choices library is present and not initialized yet, init it
        try {
            if (window.Choices && !sel._choicesInstance) {
                choicesInstance = new Choices(sel, {
                    searchEnabled: false,
                    shouldSort: false,
                    itemSelectText: '',
                    classNames: {
                        containerOuter: 'choices',
                    }
                });
                sel._choicesInstance = choicesInstance;
                console.log('Choices initialized for #status');
                return;
            }
        } catch (e) {
            console.warn('Choices init error:', e);
        }

        // If plugin not available OR initialization failed, ensure select is visible
        sel.style.display = '';
        sel.classList.add('form-select');
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        const sel = document.getElementById('status');
        // destroy choices to prevent duplicate UI on next show (if created)
        try {
            if (sel && sel._choicesInstance && typeof sel._choicesInstance.destroy === 'function') {
                sel._choicesInstance.destroy();
                sel._choicesInstance = null;
                console.log('Choices destroyed for #status');
            }
        } catch (e) {
            console.warn('Choices destroy error:', e);
        }
    });
});

$(function() {
    if (!window.orderRoutes || !window.orderRoutes.index) {
        console.error('orderRoutes.index not defined.');
        return;
    }

    const table = new DataTable('#orders-table', {
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        ajax: {
            url: window.orderRoutes.index,
            type: 'GET',
            dataType: 'json',
            cache: false,
            error: function(xhr, textStatus, errorThrown) {
                console.error('DataTables AJAX error:', textStatus, errorThrown, xhr.responseText);
                if (window.toast) window.toast.error('Gagal memuat data order. Cek console.');
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'order_no', name: 'order_no' },
            { data: 'customer', orderable: false },
            { data: 'branch', orderable: false },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[6, 'desc']], // default terbaru
        drawCallback: function() {
            try { if (window.lucide && typeof window.lucide.replace === 'function') window.lucide.replace(); } catch(e) {}
            initTooltips(document.querySelector('#orders-table'));
        }
    });

    $(document).on('click', '.js-delete-order', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name') || '';
        showDeleteModal({
            modalId: 'deleteOrderModal',
            formId: 'deleteOrderForm',
            itemNameId: 'delete_order_title',
            id,
            name,
            route: window.orderRoutes.destroy
        });
    });

});
