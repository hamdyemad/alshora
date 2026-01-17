@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('reservation.reservations_management'), 'url' => route('admin.reservations.index')],
                    ['title' => trans('reservation.appointment_details')]
                ]" />
            </div>
        </div>

        {{-- Header Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">{{ trans('reservation.appointment_details') }} #{{ $appointment->id }}</h4>
                                <p class="text-muted mb-0">{{ trans('reservation.view_appointment_details') }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.reservations.index') }}" class="btn btn-light">
                                    <i class="uil uil-arrow-left"></i> {{ trans('reservation.back_to_list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Appointment Details --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                            <i class="uil uil-calendar-alt me-2"></i>{{ trans('reservation.appointment_information') }}
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.appointment_date') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.time_slot') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.day_period') }}</label>
                                    <p class="fs-15 color-dark">
                                        <span class="badge badge-round badge-lg bg-primary me-2">{{ trans('reservation.' . $appointment->day) }}</span>
                                        <span class="badge badge-round badge-lg bg-secondary">{{ trans('reservation.' . $appointment->period) }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.status') }}</label>
                                    <p class="fs-15">
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'rejected' => 'danger',
                                                'completed' => 'success',
                                                'cancelled' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge badge-round badge-lg bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                            {{ trans('reservation.' . $appointment->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.consultation_price') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <span class="text-success">{{ number_format($appointment->consultation_price ?? 0, 2) }}</span>
                                        <small class="text-muted">{{ trans('common.egp') }}</small>
                                    </p>
                                </div>
                            </div>

                            @if($appointment->notes)
                                <div class="col-12 mb-25">
                                    <div class="view-item">
                                        <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.notes') }}</label>
                                        <p class="fs-15 color-dark">{{ $appointment->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer & Lawyer Info --}}
            <div class="col-lg-4">
                {{-- Customer Information --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                            <i class="uil uil-user me-2"></i>{{ trans('reservation.customer_information') }}
                        </h6>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle me-3 overflow-hidden" style="width: 50px; height: 50px;">
                                @if($appointment->customer->logo)
                                    <img src="{{ asset('storage/' . $appointment->customer->logo->path) }}"
                                         alt="{{ $appointment->customer->name }}"
                                         class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                        <i class="uil uil-user" style="font-size: 24px; color: #6c757d;"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $appointment->customer->name ?? trans('reservation.unknown_customer') }}</h6>
                                <small class="text-muted">{{ $appointment->customer->user->email ?? trans('reservation.no_email') }}</small>
                            </div>
                        </div>

                        @if($appointment->customer->phone)
                            <div class="mb-2">
                                <i class="uil uil-phone me-2 text-muted"></i>
                                <span>{{ $appointment->customer->phone }}</span>
                            </div>
                        @endif

                        <div class="mb-2">
                            <i class="uil uil-calendar-alt me-2 text-muted"></i>
                            <span>{{ trans('reservation.member_since') }} {{ $appointment->customer->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Lawyer Information --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                            <i class="uil uil-briefcase me-2"></i>{{ trans('reservation.lawyer_information') }}
                        </h6>

                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar rounded-circle me-3 overflow-hidden" style="width: 50px; height: 50px;">
                                @if($appointment->lawyer->profile_image)
                                    <img src="{{ asset('storage/' . $appointment->lawyer->profile_image->path) }}"
                                         alt="{{ $appointment->lawyer->name }}"
                                         class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center w-100 h-100">
                                        <i class="uil uil-briefcase text-primary" style="font-size: 24px;"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $appointment->lawyer->name ?? trans('reservation.unknown_lawyer') }}</h6>
                                <small class="text-muted">{{ $appointment->lawyer->user->email ?? trans('reservation.no_email') }}</small>
                            </div>
                        </div>

                        @if($appointment->lawyer->phone)
                            <div class="mb-2">
                                <i class="uil uil-phone me-2 text-muted"></i>
                                <span>{{ $appointment->lawyer->phone }}</span>
                            </div>
                        @endif

                        @if($appointment->lawyer->city)
                            <div class="mb-2">
                                <i class="uil uil-map-marker me-2 text-muted"></i>
                                <span>{{ $appointment->lawyer->city->getTranslation('name', app()->getLocale()) }}</span>
                                @if($appointment->lawyer->region)
                                    <span>, {{ $appointment->lawyer->region->getTranslation('name', app()->getLocale()) }}</span>
                                @endif
                            </div>
                        @endif

                        @if($appointment->lawyer->consultation_price)
                            <div class="mb-2">
                                <i class="uil uil-money-bill me-2 text-muted"></i>
                                <span>{{ number_format($appointment->lawyer->consultation_price, 2) }} EGP</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Timestamps --}}
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                            <i class="uil uil-clock me-2"></i>{{ trans('reservation.timeline') }}
                        </h6>

                        <div class="row">
                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.created_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.last_updated') }}</label>
                                    <p class="fs-15 color-dark">{{ $appointment->updated_at->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('reservation.time_difference') }}</label>
                                    <p class="fs-15 color-dark">{{ $appointment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
