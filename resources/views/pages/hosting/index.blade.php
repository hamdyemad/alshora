@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ __('hosting.hosting') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="uil uil-estate"></i>{{ __('menu.dashboard.title') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('hosting.hosting') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default card-md mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>{{ __('hosting.hosting_schedule') }}</h6>
                    <a href="{{ route('admin.hosting.settings') }}" class="btn btn-primary btn-default btn-squared">
                        <i class="uil uil-setting"></i>
                        {{ __('hosting.manage_settings') }}
                    </a>
                </div>
                <div class="card-body">
                    @if($hostingTimes->isEmpty())
                        <div class="alert alert-info" role="alert">
                            <i class="uil uil-info-circle"></i>
                            {{ __('hosting.no_hosting_times') }}
                            <a href="{{ route('admin.hosting.settings') }}" class="alert-link">{{ __('hosting.configure_now') }}</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="25%">{{ __('hosting.day') }}</th>
                                        <th width="25%">{{ __('hosting.from_time') }}</th>
                                        <th width="25%">{{ __('hosting.to_time') }}</th>
                                        <th width="25%">{{ __('hosting.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($days as $dayKey => $dayName)
                                        @php
                                            $daySlots = $hostingTimes->get($dayKey) ?? collect([]);
                                        @endphp
                                        
                                        @if($daySlots->isNotEmpty())
                                            @foreach($daySlots as $index => $slot)
                                                <tr>
                                                    <td>
                                                        @if($index === 0)
                                                            <strong>{{ $dayName }}</strong>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ date('h:i A', strtotime($slot->from_time)) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ date('h:i A', strtotime($slot->to_time)) }}</span>
                                                    </td>
                                                    <td>
                                                        @if($slot->is_active)
                                                            <span class="badge badge-success">{{ __('hosting.active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('hosting.inactive') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
