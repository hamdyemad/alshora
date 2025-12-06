@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('customer.customers_management'), 'url' => route('admin.customers.index')],
                    ['title' => trans('customer.customer_details')]
                ]" />
            </div>
        </div>

        {{-- Profile Header Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            {{-- Logo --}}
                            <div class="col-auto">
                                @php
                                    $logo = $customer->attachments->where('type', 'logo')->first();
                                @endphp
                                @if($logo)
                                    <img src="{{ asset('storage/' . $logo->path) }}" 
                                         alt="{{ $customer->getTranslation('name', app()->getLocale()) }}" 
                                         class="rounded shadow" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center shadow" 
                                         style="width: 100px; height: 100px;">
                                        <i class="uil uil-user" style="font-size: 48px; color: #ccc;"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Customer Info --}}
                            <div class="col">
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 me-3">{{ $customer->getTranslation('name', app()->getLocale()) }}</h4>
                                    @if($customer->active)
                                        <span class="badge bg-success badge-lg badge-round">
                                            <i class="uil uil-check-circle me-1"></i> {{ trans('customer.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger badge-lg badge-round">
                                            <i class="uil uil-times-circle me-1"></i> {{ trans('customer.inactive') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted mb-2">
                                    <i class="uil uil-envelope me-1"></i>
                                    {{ $customer->user?->email ?? '-' }}
                                </p>
                                <p class="mb-0 text-muted">
                                    <i class="uil uil-phone me-1"></i>
                                    {{ $customer->phoneCountry?->phone_code ?? '' }} {{ $customer->phone }}
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-light">
                                        <i class="uil uil-arrow-left"></i> {{ __('common.back_to_list') }}
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-primary">
                                        <i class="uil uil-edit"></i> {{ __('common.edit') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-user me-2"></i>{{ trans('customer.customer_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.name') }} (EN)</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ $customer->getTranslation('name', 'en') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.name') }} (AR)</label>
                                    <p class="fs-15 color-dark fw-500" dir="rtl">
                                        {{ $customer->getTranslation('name', 'ar') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.status') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($customer->active)
                                            <span class="badge badge-round badge-lg bg-success">
                                                <i class="uil uil-check me-1"></i>{{ trans('customer.active') }}
                                            </span>
                                        @else
                                            <span class="badge badge-round badge-lg bg-danger">
                                                <i class="uil uil-times me-1"></i>{{ trans('customer.inactive') }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_at') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-calendar-alt me-1"></i>
                                        {{ $customer->created_at->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Contact Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-envelope me-2"></i>{{ trans('customer.contact_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.email') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-envelope text-primary me-1"></i>
                                        {{ $customer->user?->email ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.phone') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-phone text-success me-1"></i>
                                        {{ $customer->phoneCountry?->phone_code ?? '' }} {{ $customer->phone }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.phone_country') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($customer->phoneCountry)
                                            <span class="badge badge-light border">
                                                {{ $customer->phoneCountry->getTranslation('name', app()->getLocale()) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Location Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-map-marker me-2"></i>{{ trans('customer.location_information') }}
                                </h6>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.address') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-map-marker text-danger me-1"></i>
                                        {{ $customer->address ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.city') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($customer->city)
                                            <span class="badge badge-light border">
                                                <i class="uil uil-building me-1"></i>
                                                {{ $customer->city->getTranslation('name', app()->getLocale()) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.region') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($customer->region)
                                            <span class="badge badge-light border">
                                                <i class="uil uil-location-point me-1"></i>
                                                {{ $customer->region->getTranslation('name', app()->getLocale()) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="col-12 mb-30 mt-40">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-clock me-2"></i>{{ __('common.timestamps') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_at') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-calendar-alt text-primary me-2"></i>
                                        {{ $customer->created_at->format('Y-m-d H:i:s') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-calendar-alt text-info me-2"></i>
                                        {{ $customer->updated_at->format('Y-m-d H:i:s') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
