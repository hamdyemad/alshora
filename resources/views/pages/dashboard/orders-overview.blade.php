<div class="col-xxl-6 mb-25">
    <div class="card border-0 px-25 h-100">
        <div class="card-header px-0 border-0">
            <h6>{{ trans('dashboard.lawyers_statistics') }}</h6>
        </div>
        <div class="p-0 card-body">
            <div class="revenueSourceChart px-0">
                <div class="parentContainer position-relative" style="height: 300px;">
                    <canvas id="lawyersChart"></canvas>
                </div>
                <div class="chart-content__details mt-3">
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(91, 105, 255, 0.2);">
                            <span class="uil uil-balance-scale" style="color: #5b69ff;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.total_lawyers') }}</span>
                        <span class="data">{{ $totalLawyers }}</span>
                    </div>
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(32, 201, 151, 0.2);">
                            <span class="uil uil-check-circle" style="color: #20c997;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.active_lawyers') }}</span>
                        <span class="data">{{ $activeLawyers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-6 mb-25">
    <div class="card border-0 px-25 h-100">
        <div class="card-header px-0 border-0">
            <h6>{{ trans('dashboard.appointments_statistics') }}</h6>
        </div>
        <div class="p-0 card-body">
            <div class="revenueSourceChart px-0">
                <div class="parentContainer position-relative" style="height: 300px;">
                    <canvas id="appointmentsChart"></canvas>
                </div>
                <div class="chart-content__details mt-3">
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(91, 105, 255, 0.2);">
                            <span class="uil uil-calendar-alt" style="color: #5b69ff;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.total_appointments') }}</span>
                        <span class="data">{{ $totalAppointments }}</span>
                    </div>
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(255, 193, 7, 0.2);">
                            <span class="uil uil-clock" style="color: #ffc107;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.pending_appointments') }}</span>
                        <span class="data">{{ $pendingAppointments }}</span>
                    </div>
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(32, 201, 151, 0.2);">
                            <span class="uil uil-check-circle" style="color: #20c997;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.confirmed_appointments') }}</span>
                        <span class="data">{{ $confirmedAppointments }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-6 mb-25">
    <div class="card border-0 px-25 h-100">
        <div class="card-header px-0 border-0">
            <h6>{{ trans('dashboard.customers_statistics') }}</h6>
        </div>
        <div class="p-0 card-body">
            <div class="revenueSourceChart px-0">
                <div class="parentContainer position-relative" style="height: 300px;">
                    <canvas id="customersChart"></canvas>
                </div>
                <div class="chart-content__details mt-3">
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(91, 105, 255, 0.2);">
                            <span class="uil uil-user" style="color: #5b69ff;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.total_customers') }}</span>
                        <span class="data">{{ $totalCustomers }}</span>
                    </div>
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(32, 201, 151, 0.2);">
                            <span class="uil uil-check-circle" style="color: #20c997;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.active_customers') }}</span>
                        <span class="data">{{ $activeCustomers }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xxl-6 mb-25">
    <div class="card border-0 px-25 h-100">
        <div class="card-header px-0 border-0">
            <h6>{{ trans('dashboard.reviews_statistics') }}</h6>
        </div>
        <div class="p-0 card-body">
            <div class="revenueSourceChart px-0">
                <div class="parentContainer position-relative" style="height: 300px;">
                    <canvas id="reviewsChart"></canvas>
                </div>
                <div class="chart-content__details mt-3">
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(91, 105, 255, 0.2);">
                            <span class="uil uil-star" style="color: #5b69ff;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.total_reviews') }}</span>
                        <span class="data">{{ $totalReviews }}</span>
                    </div>
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(255, 193, 7, 0.2);">
                            <span class="uil uil-clock" style="color: #ffc107;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.pending_reviews') }}</span>
                        <span class="data">{{ $pendingReviews }}</span>
                    </div>
                    <div class="chart-content__single">
                        <span class="icon" style="background-color: rgba(32, 201, 151, 0.2);">
                            <span class="uil uil-check-circle" style="color: #20c997;"></span>
                        </span>
                        <span class="label">{{ trans('dashboard.approved_reviews') }}</span>
                        <span class="data">{{ $approvedReviews }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
