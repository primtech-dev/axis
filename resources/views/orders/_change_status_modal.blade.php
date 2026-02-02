<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="changeStatusForm" method="POST" action="#" data-action-template="{{ url('/admin/orders/:id/status') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status Order <span id="changeStatusOrderNo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="order_id" id="changeStatusOrderId" value="">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status baru</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="complete">Complete</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="return">Return</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status_notes" class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" id="status_notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="alert alert-warning small">
                        Perhatian: Mengubah status ke <strong>cancelled</strong> atau <strong>shipped</strong> akan memicu proses inventory (unreserve / confirm shipment) jika layanan inventori diaktifkan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
