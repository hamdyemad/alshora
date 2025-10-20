@extends('layout.app')

@section('content')
    <div class="crm mb-25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i
                                                class="uil uil-estate"></i>{{ trans('dashboard.title') }}</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
                {{-- Sales Overview Header --}}
                @include('pages.dashboard.stats-overview')

                {{-- Sales Reports Chart --}}
                @include('pages.dashboard.sales-reports')

                {{-- Charts Row: Sales, Earnings, Total Sales --}}
                <div class="col-12">
                    <div class="row">
                        @include('pages.dashboard.sales-chart')
                        @include('pages.dashboard.earnings-chart')
                        @include('pages.dashboard.total-sales-chart')
                    </div>
                </div>

                {{-- Statistics Cards --}}
                @include('pages.dashboard.stats-cards')

                {{-- Orders Overview & Top Selling Products --}}
                <div class="col-12">
                    <div class="row">
                        @include('pages.dashboard.orders-overview')
                        @include('pages.dashboard.top-selling-products')
                    </div>
                </div>

                {{-- Latest Orders --}}
                @include('pages.dashboard.latest-orders')

                {{-- Best Customers --}}
                @include('pages.dashboard.best-customers')

            </div>
            <!-- ends: .row -->
        </div>
    </div>

    {{-- Chart Scripts --}}
    @include('pages.dashboard.charts-scripts')
@endsection
