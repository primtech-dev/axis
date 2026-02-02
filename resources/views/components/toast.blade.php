<!-- Toast Container -->
<div class="toast-container position-fixed top-0 mt-5 end-0 p-3" style="z-index: 9999;">
    <div id="appToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body d-flex align-items-start gap-3 p-3">
            <!-- Icon -->
            <span class="toast-icon flex-shrink-0">
                <i id="toastIcon" class="ti ti-circle-check fs-3"></i>
            </span>

            <!-- Content -->
            <div class="toast-content flex-grow-1">
                <h6 id="toastTitle" class="mb-1 fw-semibold">Success</h6>
                <p id="toastMessage" class="mb-0 text-muted">This is a success message</p>
            </div>

            <!-- Close Button -->
            <button type="button" class="btn-close flex-shrink-0 mt-1" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
