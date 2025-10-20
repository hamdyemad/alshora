<div class="col-xxl-4 mb-25">
    <div class="card border-0 px-25">
        <div class="card-header px-0 border-0">
            <h6>Earnings</h6>
            <div class="card-extra">
                <ul class="card-tab-links nav-tabs nav" role="tablist">
                    <li>
                        <a class="active" href="#earnings-today" data-bs-toggle="tab" id="earnings-today-tab"
                            role="tab" aria-selected="true">{{ trans('dashboard.today') }}</a>
                    </li>
                    <li>
                        <a href="#earnings-week" data-bs-toggle="tab" id="earnings-week-tab"
                            role="tab" aria-selected="false">{{ trans('dashboard.week') }}</a>
                    </li>
                    <li>
                        <a href="#earnings-month" data-bs-toggle="tab" id="earnings-month-tab"
                            role="tab" aria-selected="false">{{ trans('dashboard.month') }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body p-0 pb-25">
            <div class="tab-content">
                <div class="tab-pane active show" id="earnings-today" role="tabpanel"
                    aria-labelledby="earnings-today-tab">
                    <div class="label-detailed label-detailed--two">
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$8,550</strong> <span
                                class="label-detailed__status color-success"><img class="svg"
                                    src="img/svg/arrow-success-up.svg" alt="">
                                <strong>25%</strong></span> </div>
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$5,550</strong> <span
                                class="label-detailed__status color-danger"><img class="svg"
                                    src="img/svg/arrow-danger-down.svg" alt="">
                                <strong>15%</strong></span> </div>
                    </div>
                    <div class="parentContainer" style="height: 180px;">
                        <canvas id="earningsToday"></canvas>
                    </div>
                </div>
                <div class="tab-pane" id="earnings-week" role="tabpanel"
                    aria-labelledby="earnings-week-tab">
                    <div class="label-detailed label-detailed--two">
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$8,550</strong> <span
                                class="label-detailed__status color-success"><img class="svg"
                                    src="img/svg/arrow-success-up.svg" alt="">
                                <strong>25%</strong></span> </div>
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$5,550</strong> <span
                                class="label-detailed__status color-danger"><img class="svg"
                                    src="img/svg/arrow-danger-down.svg" alt="">
                                <strong>15%</strong></span> </div>
                    </div>
                    <div class="parentContainer" style="height: 180px;">
                        <canvas id="earningsWeek"></canvas>
                    </div>
                </div>
                <div class="tab-pane" id="earnings-month" role="tabpanel"
                    aria-labelledby="earnings-month-tab">
                    <div class="label-detailed label-detailed--two">
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$8,550</strong> <span
                                class="label-detailed__status color-success"><img class="svg"
                                    src="img/svg/arrow-success-up.svg" alt="">
                                <strong>25%</strong></span> </div>
                        <div class="label-detailed__single"><strong
                                class="label-detailed__total">$5,550</strong> <span
                                class="label-detailed__status color-danger"><img class="svg"
                                    src="img/svg/arrow-danger-down.svg" alt="">
                                <strong>15%</strong></span> </div>
                    </div>
                    <div class="parentContainer" style="height: 180px;">
                        <canvas id="earningsMonth"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
