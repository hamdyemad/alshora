@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lawyers Chart
        const lawyersCtx = document.getElementById('lawyersChart');
        if (lawyersCtx) {
            new Chart(lawyersCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ trans('dashboard.active_lawyers') }}', '{{ trans('dashboard.inactive_lawyers') }}'],
                    datasets: [{
                        data: [{{ $activeLawyers }}, {{ $totalLawyers - $activeLawyers }}],
                        backgroundColor: [
                            'rgba(32, 201, 151, 0.8)',
                            'rgba(255, 76, 81, 0.8)'
                        ],
                        borderColor: [
                            'rgba(32, 201, 151, 1)',
                            'rgba(255, 76, 81, 1)'
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
                                    return (context.label || '') + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Appointments Chart
        const appointmentsCtx = document.getElementById('appointmentsChart');
        if (appointmentsCtx) {
            new Chart(appointmentsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ trans('dashboard.pending_appointments') }}', '{{ trans('dashboard.confirmed_appointments') }}', '{{ trans('dashboard.other_appointments') }}'],
                    datasets: [{
                        data: [{{ $pendingAppointments }}, {{ $confirmedAppointments }}, {{ $totalAppointments - $pendingAppointments - $confirmedAppointments }}],
                        backgroundColor: [
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(32, 201, 151, 0.8)',
                            'rgba(156, 39, 176, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 193, 7, 1)',
                            'rgba(32, 201, 151, 1)',
                            'rgba(156, 39, 176, 1)'
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
                                    return (context.label || '') + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Customers Chart
        const customersCtx = document.getElementById('customersChart');
        if (customersCtx) {
            new Chart(customersCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ trans('dashboard.active_customers') }}', '{{ trans('dashboard.inactive_customers') }}'],
                    datasets: [{
                        data: [{{ $activeCustomers }}, {{ $totalCustomers - $activeCustomers }}],
                        backgroundColor: [
                            'rgba(32, 201, 151, 0.8)',
                            'rgba(255, 76, 81, 0.8)'
                        ],
                        borderColor: [
                            'rgba(32, 201, 151, 1)',
                            'rgba(255, 76, 81, 1)'
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
                                    return (context.label || '') + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Reviews Chart
        const reviewsCtx = document.getElementById('reviewsChart');
        if (reviewsCtx) {
            new Chart(reviewsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['{{ trans('dashboard.approved_reviews') }}', '{{ trans('dashboard.pending_reviews') }}'],
                    datasets: [{
                        data: [{{ $approvedReviews }}, {{ $pendingReviews }}],
                        backgroundColor: [
                            'rgba(32, 201, 151, 0.8)',
                            'rgba(255, 193, 7, 0.8)'
                        ],
                        borderColor: [
                            'rgba(32, 201, 151, 1)',
                            'rgba(255, 193, 7, 1)'
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
                                    return (context.label || '') + ': ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
