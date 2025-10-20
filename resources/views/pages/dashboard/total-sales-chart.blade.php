<div class="col-xxl-4 mb-25">
    <div class="card border-0 px-25">
        <div class="card-header px-0 border-0">
            <h6>{{ trans('dashboard.total_sales') }}</h6>
            <div class="card-extra">
                <ul class="card-tab-links nav-tabs nav" role="tablist">
                    <li>
                        <a class="active" href="#totalsales-today" data-bs-toggle="tab" id="totalsales-today-tab"
                            role="tab" aria-selected="true">{{ trans('dashboard.today') }}</a>
                    </li>
                    <li>
                        <a href="#totalsales-week" data-bs-toggle="tab" id="totalsales-week-tab"
                            role="tab" aria-selected="false">{{ trans('dashboard.week') }}</a>
                    </li>
                    <li>
                        <a href="#totalsales-month" data-bs-toggle="tab" id="totalsales-month-tab"
                            role="tab" aria-selected="false">{{ trans('dashboard.month') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body p-0 pb-25">
            <div class="tab-content">
                <div class="tab-pane active show" id="totalsales-today" role="tabpanel"
                    aria-labelledby="totalsales-today-tab">
                    <div class="label-detailed label-detailed--two">
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$12,550</strong> <span
                                class="label-detailed__status color-success"><img class="svg"
                                    src="img/svg/arrow-success-up.svg" alt="">
                                <strong>30%</strong></span> </div>
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$8,550</strong> <span
                                class="label-detailed__status color-danger"><img class="svg"
                                    src="img/svg/arrow-danger-down.svg" alt="">
                                <strong>10%</strong></span> </div>
                    </div>
                    <div class="parentContainer" style="height: 180px;">
                        <canvas id="totalSalesToday"></canvas>
                    </div>
                </div>
                <div class="tab-pane" id="totalsales-week" role="tabpanel"
                    aria-labelledby="totalsales-week-tab">
                    <div class="label-detailed label-detailed--two">
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$12,550</strong> <span
                                class="label-detailed__status color-success"><img class="svg"
                                    src="img/svg/arrow-success-up.svg" alt="">
                                <strong>30%</strong></span> </div>
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$8,550</strong> <span
                                class="label-detailed__status color-danger"><img class="svg"
                                    src="img/svg/arrow-danger-down.svg" alt="">
                                <strong>10%</strong></span> </div>
                    </div>
                    <div class="parentContainer" style="height: 180px;">
                        <canvas id="totalSalesWeek"></canvas>
                    </div>
                </div>
                <div class="tab-pane" id="totalsales-month" role="tabpanel"
                    aria-labelledby="totalsales-month-tab">
                    <div class="label-detailed label-detailed--two">
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$12,550</strong> <span
                                class="label-detailed__status color-success"><img class="svg"
                                    src="img/svg/arrow-success-up.svg" alt="">
                                <strong>30%</strong></span> </div>
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$8,550</strong> <span
                                class="label-detailed__status color-danger"><img class="svg"
                                    src="img/svg/arrow-danger-down.svg" alt="">
                                <strong>10%</strong></span> </div>
                    </div>
                    <div class="parentContainer" style="height: 180px;">
                        <canvas id="totalSalesMonth"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
