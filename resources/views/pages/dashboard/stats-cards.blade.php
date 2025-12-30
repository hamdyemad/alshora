<div class="col-12">
    <div class="row">
        {{-- Total Users --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $totalUsers }}</h1>
                            <p>{{ trans('dashboard.total_users') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-primary color-primary">
                                <i class="uil uil-users-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Lawyers --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $totalLawyers }}</h1>
                            <p>{{ trans('dashboard.total_lawyers') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-info color-info">
                                <i class="uil uil-balance-scale"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Customers --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $totalCustomers }}</h1>
                            <p>{{ trans('dashboard.total_customers') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-success color-success">
                                <i class="uil uil-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Appointments --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $totalAppointments }}</h1>
                            <p>{{ trans('dashboard.total_appointments') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-primary color-primary">
                                <i class="uil uil-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Reviews --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $totalReviews }}</h1>
                            <p>{{ trans('dashboard.total_reviews') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-warning color-warning">
                                <i class="uil uil-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Subscriptions --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $activeSubscriptions }}</h1>
                            <p>{{ trans('dashboard.active_subscriptions') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-info color-info">
                                <i class="uil uil-award"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Lawyers --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $activeLawyers }}</h1>
                            <p>{{ trans('dashboard.active_lawyers') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-success color-success">
                                <i class="uil uil-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Active Customers --}}
        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-25">
            <div class="ap-po-details ap-po-details--2 p-25 radius-xl d-flex justify-content-between">
                <div class="overview-content w-100">
                    <div class="ap-po-details-content d-flex flex-wrap justify-content-between">
                        <div class="ap-po-details__titlebar">
                            <h1>{{ $activeCustomers }}</h1>
                            <p>{{ trans('dashboard.active_customers') }}</p>
                        </div>
                        <div class="ap-po-details__icon-area">
                            <div class="svg-icon order-bg-opacity-primary color-primary">
                                <i class="uil uil-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
