@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Today
        const salesTodayCtx = document.getElementById('salesToday');
        if (salesTodayCtx) {
            new Chart(salesTodayCtx, {
                type: 'bar',
                data: {
                    labels: ['12am', '3am', '6am', '9am', '12pm', '3pm', '6pm', '9pm'],
                    datasets: [{
                        label: 'Sales',
                        data: [500, 800, 600, 1200, 1000, 1500, 1300, 1800],
                        backgroundColor: 'rgba(91, 105, 255, 0.2)',
                        borderColor: 'rgba(91, 105, 255, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Sales Week
        const salesWeekCtx = document.getElementById('salesWeek');
        if (salesWeekCtx) {
            new Chart(salesWeekCtx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [5000, 7500, 6000, 9000, 8000, 10000, 9500],
                        backgroundColor: 'rgba(91, 105, 255, 0.2)',
                        borderColor: 'rgba(91, 105, 255, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Sales Month
        const salesMonthCtx = document.getElementById('salesMonth');
        if (salesMonthCtx) {
            new Chart(salesMonthCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Sales',
                        data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 32000, 27000, 35000, 40000, 38000],
                        backgroundColor: 'rgba(91, 105, 255, 0.2)',
                        borderColor: 'rgba(91, 105, 255, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Orders Overview Pie Chart
        const ordersOverviewCtx = document.getElementById('ordersOverviewChart');
        if (ordersOverviewCtx) {
            new Chart(ordersOverviewCtx, {
                type: 'doughnut',
                data: {
                    labels: ['New', 'In Progress', 'Delivered', 'Cancelled', 'Want to Return', 'Return in Progress', 'Refunded'],
                    datasets: [{
                        data: [2, 2, 2, 3, 0, 0, 1],
                        backgroundColor: [
                            'rgba(91, 105, 255, 0.8)', 'rgba(255, 193, 7, 0.8)', 'rgba(32, 201, 151, 0.8)',
                            'rgba(255, 76, 81, 0.8)', 'rgba(255, 152, 0, 0.8)', 'rgba(156, 39, 176, 0.8)', 'rgba(103, 58, 183, 0.8)'
                        ],
                        borderColor: [
                            'rgba(91, 105, 255, 1)', 'rgba(255, 193, 7, 1)', 'rgba(32, 201, 151, 1)',
                            'rgba(255, 76, 81, 1)', 'rgba(255, 152, 0, 1)', 'rgba(156, 39, 176, 1)', 'rgba(103, 58, 183, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return (context.label || '') + ': ' + context.parsed + ' orders';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Total Sales Today
        const totalSalesTodayCtx = document.getElementById('totalSalesToday');
        if (totalSalesTodayCtx) {
            new Chart(totalSalesTodayCtx, {
                type: 'line',
                data: {
                    labels: ['12am', '3am', '6am', '9am', '12pm', '3pm', '6pm', '9pm'],
                    datasets: [{
                        label: 'Total Sales',
                        data: [600, 900, 700, 1400, 1200, 1700, 1500, 2000],
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Total Sales Week
        const totalSalesWeekCtx = document.getElementById('totalSalesWeek');
        if (totalSalesWeekCtx) {
            new Chart(totalSalesWeekCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Total Sales',
                        data: [6000, 8500, 7000, 10000, 9000, 11000, 10500],
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Total Sales Month
        const totalSalesMonthCtx = document.getElementById('totalSalesMonth');
        if (totalSalesMonthCtx) {
            new Chart(totalSalesMonthCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Total Sales',
                        data: [15000, 22000, 18000, 28000, 25000, 35000, 32000, 38000, 33000, 42000, 48000, 45000],
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Earnings Today
        const earningsTodayCtx = document.getElementById('earningsToday');
        if (earningsTodayCtx) {
            new Chart(earningsTodayCtx, {
                type: 'line',
                data: {
                    labels: ['12am', '3am', '6am', '9am', '12pm', '3pm', '6pm', '9pm'],
                    datasets: [{
                        label: 'Earnings',
                        data: [100, 200, 150, 300, 250, 400, 350, 500],
                        backgroundColor: 'rgba(32, 201, 151, 0.1)',
                        borderColor: 'rgba(32, 201, 151, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Earnings Week
        const earningsWeekCtx = document.getElementById('earningsWeek');
        if (earningsWeekCtx) {
            new Chart(earningsWeekCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Earnings',
                        data: [1000, 1500, 1200, 1800, 1600, 2000, 1900],
                        backgroundColor: 'rgba(32, 201, 151, 0.1)',
                        borderColor: 'rgba(32, 201, 151, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        // Earnings Month
        const earningsMonthCtx = document.getElementById('earningsMonth');
        if (earningsMonthCtx) {
            new Chart(earningsMonthCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Earnings',
                        data: [5000, 7000, 6500, 8500],
                        backgroundColor: 'rgba(32, 201, 151, 0.1)',
                        borderColor: 'rgba(32, 201, 151, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    });
</script>
@endpush
