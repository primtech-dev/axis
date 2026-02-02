import bootstrap from 'bootstrap/dist/js/bootstrap';

export function initTooltips() {
    const existingTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    existingTooltips.forEach(element => {
        const tooltipInstance = bootstrap.Tooltip.getInstance(element);
        if (tooltipInstance) {
            tooltipInstance.dispose();
        }
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    return [...tooltipTriggerList].map(tooltipTriggerEl =>
        new bootstrap.Tooltip(tooltipTriggerEl, {
            trigger: 'hover'
        })
    );
}

document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
});
