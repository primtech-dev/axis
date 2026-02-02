/**
 * Toast Helper
 * Modern toast notification system with side accent design
 */
import bootstrap from 'bootstrap/dist/js/bootstrap';

class ToastHelper {
    constructor() {
        this.toastElement = null;
        this.toastTitle = null;
        this.toastMessage = null;
        this.toastIcon = null;
        this.toastInstance = null;

        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        this.toastElement = document.getElementById('appToast');
        this.toastTitle = document.getElementById('toastTitle');
        this.toastMessage = document.getElementById('toastMessage');
        this.toastIcon = document.getElementById('toastIcon');

        if (this.toastElement) {
            this.toastInstance = new bootstrap.Toast(this.toastElement, {
                autohide: true,
                delay: 3500
            });
        }
    }

    show(message, type = 'primary', title = null) {
        if (!this.toastInstance) {
            console.warn('Toast instance not initialized');
            return;
        }

        // Remove all existing color classes
        this.toastElement.classList.remove(
            'toast-success', 'toast-danger', 'toast-warning', 'toast-info', 'toast-primary'
        );

        // Add type class
        this.toastElement.classList.add(`toast-${type}`);

        // Set title and icon based on type
        const config = {
            'success': {
                title: 'Success',
                icon: 'ti ti-circle-check'
            },
            'danger': {
                title: 'Error',
                icon: 'ti ti-circle-x'
            },
            'warning': {
                title: 'Warning',
                icon: 'ti ti-alert-triangle'
            },
            'info': {
                title: 'Informational',
                icon: 'ti ti-info-circle'
            },
            'primary': {
                title: 'Notification',
                icon: 'ti ti-bell'
            }
        };

        const typeConfig = config[type] || config['primary'];

        // Set title
        this.toastTitle.textContent = title || typeConfig.title;

        // Set icon
        this.toastIcon.className = `${typeConfig.icon} fs-3`;

        // Set message
        this.toastMessage.textContent = message;

        // Show toast
        this.toastInstance.show();
    }

    success(message, title = null) {
        this.show(message, 'success', title);
    }

    error(message, title = null) {
        this.show(message, 'danger', title);
    }

    warning(message, title = null) {
        this.show(message, 'warning', title);
    }

    info(message, title = null) {
        this.show(message, 'info', title);
    }
}

// Initialize and make globally available
window.toast = new ToastHelper();

export default ToastHelper;
