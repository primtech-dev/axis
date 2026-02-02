import { CustomChartJs, ins } from "../app";

/**
 * ORDERS BAR (TOP CARD)
 */
const ordersData = window.dashboard.ordersPerDay;

new CustomChartJs({
    selector: '#ordersChart',
    options: () => ({
        type: 'bar',
        data: {
            labels: ordersData.map(d => d.date),
            datasets: [{
                data: ordersData.map(d => d.total),
                backgroundColor: ins('chart-primary'),
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { display: false, grid: { display: false } },
                y: { display: false, grid: { display: false } }
            }
        }
    })
});

/**
 * ORDER STATUS PIE
 */
const stats = window.dashboard.orderStats;

new CustomChartJs({
    selector: '#orderStatusChart',
    options: () => ({
        type: 'pie',
        data: {
            labels: ['Complete', 'Cancelled', 'Return'],
            datasets: [{
                data: [stats.complete, stats.cancelled, stats.returned],
                backgroundColor: [
                    ins('chart-success'),
                    ins('chart-danger'),
                    ins('chart-warning')
                ]
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            }
        }
    })
});

/**
 * ORDERS TREND LINE
 */
new CustomChartJs({
    selector: '#ordersTrendChart',
    options: () => ({
        type: 'line',
        data: {
            labels: ordersData.map(d => d.date),
            datasets: [{
                label: 'Orders',
                data: ordersData.map(d => d.total),
                borderColor: ins('chart-primary'),
                backgroundColor: ins('chart-primary-rgb', 0.2),
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    grid: { borderDash: [4, 4] }
                }
            }
        }
    })
});
