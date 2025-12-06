@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('lawyer.lawyers_management'), 'url' => route('admin.lawyers.index')],
                    ['title' => trans('lawyer.view_lawyer')]
                ]" />
            </div>
        </div>

        {{-- Profile Header Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            {{-- Profile Image --}}
                            <div class="col-auto">
                                @php
                                    $profileImage = $lawyer->profile_image;
                                @endphp
                                @if($profileImage)
                                    <img src="{{ asset('storage/' . $profileImage->path) }}" 
                                         alt="{{ $lawyer->getTranslation('name', app()->getLocale()) }}" 
                                         class="rounded-circle shadow" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow" 
                                         style="width: 100px; height: 100px;">
                                        <i class="uil uil-user" style="font-size: 48px; color: #ccc;"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Lawyer Info --}}
                            <div class="col">
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 me-3">{{ $lawyer->getTranslation('name', app()->getLocale()) }}</h4>
                                    @if($lawyer->active)
                                        <span class="badge bg-success badge-lg badge-round"><i class="uil uil-check-circle me-1"></i> {{ trans('lawyer.verified') }}</span>
                                    @else
                                        <span class="badge bg-warning badge-lg badge-round"><i class="uil uil-exclamation-triangle me-1"></i> {{ trans('lawyer.pending_verification') }}</span>
                                    @endif
                                </div>
                                <p class="text-muted mb-2">
                                    <i class="uil uil-star"></i> 
                                    <span class="fw-bold">0</span> (0 {{ trans('lawyer.client_review') }})
                                </p>
                                <p class="mb-0">
                                    <span class="text-muted">{{ trans('lawyer.consultation_price') }}:</span>
                                    <span class="fs-18 fw-bold text-primary">{{ number_format($lawyer->consultation_price, 2) }} {{ trans('common.egp') }}</span>
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-auto">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.lawyers.index') }}" class="btn btn-sm btn-light">
                                        <i class="uil uil-arrow-left"></i> {{ __('common.back_to_list') }}
                                    </a>
                                    <a href="{{ route('admin.lawyers.edit', $lawyer->id) }}" class="btn btn-sm btn-primary">
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
                {{-- Tabs Navigation --}}
                <ul class="nav nav-tabs mb-4" id="lawyerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="uil uil-user me-2"></i>{{ trans('lawyer.lawyer_data') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ratings-tab" data-bs-toggle="tab" data-bs-target="#ratings" type="button" role="tab">
                            <i class="uil uil-star me-2"></i>{{ trans('lawyer.ratings_about') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">
                            <i class="uil uil-calendar-alt me-2"></i>{{ trans('lawyer.bookings') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="agenda-tab" data-bs-toggle="tab" data-bs-target="#agenda" type="button" role="tab">
                            <i class="uil uil-schedule me-2"></i>{{ trans('lawyer.agenda') }}
                        </button>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content" id="lawyerTabsContent">
                    {{-- Lawyer Data Tab --}}
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-user me-2"></i>{{ trans('lawyer.basic_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.name') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ $lawyer->getTranslation('name', app()->getLocale()) ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.gender') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($lawyer->gender)
                                            <span class="badge badge-round badge-lg {{ $lawyer->gender == 'male' ? 'bg-primary' : 'bg-info' }}">
                                                {{ trans('lawyer.' . $lawyer->gender) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.registration_number') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ $lawyer->registration_number }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.degree_of_registration') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($lawyer->degree_of_registration)
                                            <span class="badge badge-round badge-lg bg-secondary">{{ ucwords(str_replace('_', ' ', $lawyer->degree_of_registration)) }}</span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.email') }}</label>
                                    <p class="fs-15 color-dark">
                                        <i class="uil uil-envelope me-2"></i>{{ $lawyer->user?->email ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.phone_country') }}</label>
                                    <p class="fs-15 color-dark">
                                        @if($lawyer->phoneCountry)
                                            {{ $lawyer->phoneCountry->getTranslation('name', app()->getLocale()) }}
                                            ({{ $lawyer->phoneCountry->phone_code }})
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.phone') }}</label>
                                    <p class="fs-15 color-dark">
                                        <i class="uil uil-phone me-2"></i>{{ $lawyer->phone ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.consultation_price') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ number_format($lawyer->consultation_price, 2) }} EGP
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.status') }}</label>
                                    <p class="fs-15">
                                        @if($lawyer->active)
                                            <span class="badge badge-round badge-lg bg-success">{{ trans('lawyer.active') }}</span>
                                        @else
                                            <span class="badge badge-round badge-lg bg-danger">{{ trans('lawyer.inactive') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Subscription & Settings Section --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-ticket me-2"></i>{{ trans('lawyer.subscription') }} & {{ trans('common.settings') }}
                                </h6>
                            </div>

                            {{-- Current Subscription --}}
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.current_subscription') }}</label>
                                    <p class="fs-15 color-dark">
                                        @if($lawyer->subscription)
                                            <span class="badge badge-round badge-lg badge-info">
                                                <i class="uil uil-ticket me-1"></i>{{ $lawyer->subscription->getTranslation('name', app()->getLocale()) }}
                                            </span>
                                            <br>
                                            <small class="text-muted mt-2 d-block">
                                                @if($lawyer->subscription_expires_at)
                                                    @if($lawyer->subscription_expires_at->isPast())
                                                        <span class="text-danger">
                                                            <i class="uil uil-exclamation-triangle"></i>
                                                            {{ trans('lawyer.expired') }}: {{ $lawyer->subscription_expires_at->format('Y-m-d') }}
                                                        </span>
                                                    @else
                                                        <span class="text-success">
                                                            <i class="uil uil-check-circle"></i>
                                                            {{ trans('lawyer.active_until') }}: {{ $lawyer->subscription_expires_at->format('Y-m-d') }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </small>
                                        @else
                                            <span class="text-muted">{{ trans('lawyer.no_subscription') }}</span>
                                        @endif
                                    </p>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#renewSubscriptionModal">
                                        <i class="uil uil-sync me-1"></i>{{ trans('lawyer.renew_subscription') }}
                                    </button>
                                </div>
                            </div>

                            {{-- Ads Enabled Toggle --}}
                            <div class="col-md-3 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10 d-block">{{ trans('lawyer.ads_enabled') }}</label>
                                    <div class="form-check form-switch form-switch-primary form-switch-md">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="adsToggle" 
                                               {{ $lawyer->ads_enabled ? 'checked' : '' }}
                                               onchange="toggleAds({{ $lawyer->id }}, this)">
                                    </div>
                                </div>
                            </div>

                            {{-- Block Toggle --}}
                            <div class="col-md-3 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10 d-block">{{ trans('lawyer.blocked') }}</label>
                                    <div class="form-check form-switch form-switch-danger form-switch-md">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="blockToggle" 
                                               {{ $lawyer->user->is_blocked ? 'checked' : '' }}
                                               onchange="toggleBlock({{ $lawyer->id }}, this)">
                                    </div>
                                </div>
                            </div>

                            {{-- Images Section --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-image me-2"></i>{{ trans('lawyer.images') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.profile_image') }}</label>
                                    <div class="mt-10">
                                        @php
                                            $profileImage = $lawyer->profile_image;
                                        @endphp
                                        @if($profileImage)
                                            <img src="{{ asset('storage/' . $profileImage->path) }}" 
                                                 alt="Profile Image" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                        @else
                                            <div class="text-center p-4 bg-light rounded" style="max-width: 300px;">
                                                <i class="uil uil-user" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="text-muted mt-2 mb-0">{{ trans('lawyer.no_image') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.id_card_image') }}</label>
                                    <div class="mt-10">
                                        @php
                                            $idCard = $lawyer->id_card;
                                        @endphp
                                        @if($idCard)
                                            <img src="{{ asset('storage/' . $idCard->path) }}" 
                                                 alt="ID Card" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="max-width: 400px; max-height: 300px; object-fit: cover;">
                                        @else
                                            <div class="text-center p-4 bg-light rounded" style="max-width: 400px;">
                                                <i class="uil uil-credit-card" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="text-muted mt-2 mb-0">{{ trans('lawyer.no_image') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Location Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-map-marker me-2"></i>{{ trans('lawyer.location_information') }}
                                </h6>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.address') }}</label>
                                    <p class="fs-15 color-dark">
                                        {{ $lawyer->address ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.city') }}</label>
                                    <p class="fs-15 color-dark">
                                        {{ $lawyer->city?->getTranslation('name', app()->getLocale()) ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.region') }}</label>
                                    <p class="fs-15 color-dark">
                                        {{ $lawyer->region?->getTranslation('name', app()->getLocale()) ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            @if($lawyer->latitude && $lawyer->longitude)
                                <div class="col-md-12 mb-25">
                                    <div class="view-item">
                                        <label class="il-gray fs-14 fw-500 mb-10">
                                            <i class="uil uil-map-pin me-1"></i>{{ trans('lawyer.coordinates') }}
                                        </label>
                                        <p class="fs-15 color-dark">
                                            <span class="badge badge-round badge-lg bg-primary me-2">Lat: {{ number_format($lawyer->latitude, 6) }}</span>
                                            <span class="badge badge-round badge-lg bg-primary">Lng: {{ number_format($lawyer->longitude, 6) }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endif

                            {{-- Experiences Section --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-briefcase me-2"></i>{{ trans('lawyer.experiences') }}
                                </h6>
                            </div>

                            {{-- Experience English --}}
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.experience_en') }}</label>
                                    <p class="fs-15 color-dark">
                                        {{ $lawyer->getTranslation('experience', 'en') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Experience Arabic --}}
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10" dir="rtl" style="text-align: right; display: block;">{{ trans('lawyer.experience_ar') }}</label>
                                    <p class="fs-15 color-dark" dir="rtl" style="text-align: right;">
                                        {{ $lawyer->getTranslation('experience', 'ar') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Office Hours Section --}}
                            <div class="col-12 mb-30 mt-20">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-15 mb-20">
                                    <h6 class="fw-500 color-dark mb-0">
                                        <i class="uil uil-clock me-2"></i>{{ trans('lawyer.office_hours') }}
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOfficeHoursModal">
                                        <i class="uil uil-edit me-1"></i>{{ trans('lawyer.edit_office_hours') }}
                                    </button>
                                </div>
                            </div>

                            <div class="col-12 mb-25">
                                @if(isset($lawyer->officeHours) && $lawyer->officeHours->count() > 0)
                                    <div class="alert alert-info mb-3">
                                        <i class="uil uil-info-circle me-2"></i>
                                        {{ $lawyer->officeHours->count() }} {{ trans('lawyer.office_hours') }} {{ trans('lawyer.found') }}
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-3">
                                        <i class="uil uil-exclamation-triangle me-2"></i>
                                        {{ trans('lawyer.no_office_hours_set') }}. {{ trans('lawyer.click_edit_to_add') }}
                                    </div>
                                @endif
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ trans('lawyer.period') }}</th>
                                                <th>{{ trans('lawyer.saturday') }}</th>
                                                <th>{{ trans('lawyer.sunday') }}</th>
                                                <th>{{ trans('lawyer.monday') }}</th>
                                                <th>{{ trans('lawyer.tuesday') }}</th>
                                                <th>{{ trans('lawyer.wednesday') }}</th>
                                                <th>{{ trans('lawyer.thursday') }}</th>
                                                <th>{{ trans('lawyer.friday') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                                                $periods = ['morning', 'evening'];
                                                // Check if officeHours relation exists and is loaded
                                                $officeHours = isset($lawyer->officeHours) && $lawyer->officeHours 
                                                    ? $lawyer->officeHours->keyBy(function($item) {
                                                        return $item->day . '_' . $item->period;
                                                    }) 
                                                    : collect();
                                            @endphp

                                            @foreach($periods as $period)
                                                <tr>
                                                    <td class="fw-bold text-center">{{ trans('lawyer.' . $period) }}</td>
                                                    @foreach($days as $day)
                                                        @php
                                                            $key = $day . '_' . $period;
                                                            $hour = $officeHours->get($key);
                                                        @endphp
                                                        <td class="text-center align-middle">
                                                            @if($hour && $hour->is_available == 1 && $hour->from_time && $hour->to_time)
                                                                <div class="badge badge-round badge-lg badge-success text-white p-2">
                                                                    <div class="px-2"> from: {{ \Carbon\Carbon::parse($hour->from_time)->format('h:i A') }} </div>
                                                                    <div class="px-2"> to:  {{ \Carbon\Carbon::parse($hour->to_time)->format('h:i A') }}</div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted small">{{ trans('lawyer.not_available') }}</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Specializations Section --}}
                            <div class="col-12 mb-30 mt-20">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-15 mb-20">
                                    <h6 class="fw-500 color-dark mb-0">
                                        <i class="uil uil-briefcase me-2"></i>{{ trans('lawyer.specializations') }}
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editSpecializationsModal">
                                        <i class="uil uil-edit me-1"></i>{{ trans('lawyer.edit_specializations') }}
                                    </button>
                                </div>
                            </div>

                            <div class="col-12 mb-25">
                                @if($lawyer->sectionsOfLaws && $lawyer->sectionsOfLaws->count() > 0)
                                    <div class="alert alert-success mb-3">
                                        <i class="uil uil-check-circle me-2"></i>
                                        {{ $lawyer->sectionsOfLaws->count() }} {{ trans('lawyer.specializations') }} {{ trans('lawyer.found') }}
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($lawyer->sectionsOfLaws as $section)
                                            <span class="badge badge-lg badge-round bg-primary px-3 py-2">
                                                <i class="uil uil-balance-scale me-1"></i>
                                                {{ $section->getTranslation('name', app()->getLocale()) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-3">
                                        <i class="uil uil-exclamation-triangle me-2"></i>
                                        {{ trans('lawyer.no_specializations') }}. {{ trans('lawyer.click_edit_to_add') }}
                                    </div>
                                @endif
                            </div>

                            {{-- Renewal Date --}}
                            <div class="col-12 mb-30 mt-20">
                                <div class="alert alert-warning">
                                    <i class="uil uil-calendar-alt me-2"></i>
                                    <strong>{{ trans('lawyer.renewal_date') }}:</strong> 2022-11-30
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
                                    <p class="fs-15 color-dark">{{ $lawyer->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $lawyer->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ratings Tab --}}
                    <div class="tab-pane fade" id="ratings" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="uil uil-star" style="font-size: 64px; color: #ffc107;"></i>
                                    </div>
                                    <h3 class="mb-3">{{ trans('lawyer.rating') }}: 1 / 5</h3>
                                    <p class="text-muted mb-4">(0 {{ trans('lawyer.client_opinion') }})</p>
                                    
                                    <div class="alert alert-info">
                                        <i class="uil uil-info-circle me-2"></i>{{ trans('lawyer.no_reviews_yet') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bookings Tab --}}
                    <div class="tab-pane fade" id="bookings" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="uil uil-calendar-alt" style="font-size: 64px; color: #6c757d;"></i>
                                    </div>
                                    <h4 class="mb-3">{{ trans('lawyer.bookings') }}</h4>
                                    <div class="alert alert-info">
                                        <i class="uil uil-info-circle me-2"></i>{{ trans('lawyer.no_bookings_yet') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Agenda Tab --}}
                    <div class="tab-pane fade" id="agenda" role="tabpanel">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="uil uil-schedule" style="font-size: 64px; color: #6c757d;"></i>
                                    </div>
                                    <h4 class="mb-3">{{ trans('lawyer.agenda') }}</h4>
                                    <div class="alert alert-info">
                                        <i class="uil uil-info-circle me-2"></i>{{ trans('lawyer.no_agenda_items') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Office Hours Edit Modal --}}
    <div class="modal fade" id="editOfficeHoursModal" tabindex="-1" aria-labelledby="editOfficeHoursModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="editOfficeHoursModalLabel">
                        <i class="uil uil-clock me-2"></i>{{ trans('lawyer.edit_office_hours') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="officeHoursForm">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="150">{{ trans('lawyer.day') }}</th>
                                        <th>{{ trans('lawyer.period') }}</th>
                                        <th width="150">{{ trans('lawyer.from_time') }}</th>
                                        <th width="150">{{ trans('lawyer.to_time') }}</th>
                                        <th width="100" class="text-center">{{ trans('lawyer.available') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                                        $periods = ['morning', 'evening'];
                                        $existingHours = $lawyer->officeHours->keyBy(function($item) {
                                            return $item->day . '_' . $item->period;
                                        });
                                    @endphp

                                    @foreach($days as $day)
                                        @foreach($periods as $periodIndex => $period)
                                            @php
                                                $key = $day . '_' . $period;
                                                $existingHour = $existingHours->get($key);
                                            @endphp
                                            <tr>
                                                @if($periodIndex == 0)
                                                    <td rowspan="2" class="align-middle fw-bold">
                                                        {{ trans('lawyer.' . $day) }}
                                                    </td>
                                                @endif
                                                <td class="align-middle">
                                                    <span class="badge badge-round badge-lg {{ $period == 'morning' ? 'bg-warning' : 'bg-info' }}">
                                                        {{ trans('lawyer.' . $period) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                        name="office_hours[{{ $day }}][{{ $period }}][from_time]" 
                                                        class="form-control form-control-sm"
                                                        value="{{ $existingHour->from_time ?? '' }}">
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                        name="office_hours[{{ $day }}][{{ $period }}][to_time]" 
                                                        class="form-control form-control-sm"
                                                        value="{{ $existingHour->to_time ?? '' }}">
                                                </td>
                                                <td class="text-center align-middle">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input type="hidden" name="office_hours[{{ $day }}][{{ $period }}][is_available]" value="0">
                                                        <input type="checkbox" 
                                                            class="form-check-input" 
                                                            name="office_hours[{{ $day }}][{{ $period }}][is_available]" 
                                                            value="1"
                                                            {{ ($existingHour->is_available ?? 0) == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="uil uil-times me-1"></i>{{ trans('common.cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveOfficeHours()">
                        <i class="uil uil-check me-1"></i>{{ trans('common.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Specializations Modal --}}
    <div class="modal fade" id="editSpecializationsModal" tabindex="-1" aria-labelledby="editSpecializationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="editSpecializationsModalLabel">
                        <i class="uil uil-briefcase me-2"></i>{{ trans('lawyer.edit_specializations') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="specializationsForm">
                        @csrf
                        <div class="mb-3">
                            <label for="sections_of_laws" class="form-label">
                                {{ trans('lawyer.sections_of_laws') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" id="sections_of_laws_modal" name="sections_of_laws[]" multiple="multiple" style="width: 100%;">
                                @php
                                    $allSectionsOfLaws = \App\Models\SectionOfLaw::where('active', 1)->get();
                                    $selectedSectionIds = $lawyer->sectionsOfLaws->pluck('id')->toArray();
                                @endphp
                                @foreach($allSectionsOfLaws as $section)
                                    <option value="{{ $section->id }}" {{ in_array($section->id, $selectedSectionIds) ? 'selected' : '' }}>
                                        {{ $section->getTranslation('name', app()->getLocale()) }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ trans('lawyer.select_at_least_one_specialization') }}</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="uil uil-times me-1"></i>{{ trans('common.cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveSpecializations()">
                        <i class="uil uil-check me-1"></i>{{ trans('common.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .view-item label {
        color: #9299b8;
        margin-bottom: 8px;
    }
    .view-item p {
        margin-bottom: 0;
        font-weight: 500;
    }
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
    }
    .nav-tabs .nav-link:hover {
        border-bottom-color: #dee2e6;
    }
    .nav-tabs .nav-link.active {
        color: #5f63f2;
        border-bottom-color: #5f63f2;
        background: transparent;
    }
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
@endpush

{{-- Renew Subscription Modal --}}
<div class="modal fade" id="renewSubscriptionModal" tabindex="-1" aria-labelledby="renewSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="renewSubscriptionModalLabel">
                    <i class="uil uil-sync me-2"></i>{{ trans('lawyer.renew_subscription') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="renewSubscriptionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="subscription_id" class="form-label">{{ trans('lawyer.select_subscription') }}</label>
                        <select class="form-control" id="subscription_id" name="subscription_id" required>
                            <option value="">{{ trans('lawyer.select_subscription') }}</option>
                            @php
                                $subscriptions = \App\Models\Subscription::where('active', 1)->get();
                            @endphp
                            @foreach($subscriptions as $sub)
                                <option value="{{ $sub->id }}">
                                    {{ $sub->getTranslation('name', app()->getLocale()) }} - {{ $sub->number_of_months }} {{ trans('subscription.months') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('common.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="renewSubscription({{ $lawyer->id }})">
                    <i class="uil uil-check me-1"></i>{{ trans('common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle Ads
    function toggleAds(lawyerId, checkbox) {
        const url = `{{ route('admin.lawyers.toggle-ads', ':id') }}`.replace(':id', lawyerId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showToast(data.message, 'success');
            } else {
                checkbox.checked = !checkbox.checked;
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Toggle Ads Error:', error);
            checkbox.checked = !checkbox.checked;
            showToast('{{ trans("lawyer.error_toggling_ads") }}', 'error');
        });
    }

    // Toggle Block
    function toggleBlock(lawyerId, checkbox) {
        const url = `{{ route('admin.lawyers.toggle-block', ':id') }}`.replace(':id', lawyerId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showToast(data.message, 'success');
            } else {
                checkbox.checked = !checkbox.checked;
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Toggle Block Error:', error);
            checkbox.checked = !checkbox.checked;
            showToast('{{ trans("lawyer.error_toggling_block") }}', 'error');
        });
    }

    // Renew Subscription
    function renewSubscription(lawyerId) {
        const subscriptionId = document.getElementById('subscription_id').value;
        
        if (!subscriptionId) {
            showToast('{{ trans("lawyer.select_subscription") }}', 'error');
            return;
        }
        
        const url = `{{ route('admin.lawyers.renew-subscription', ':id') }}`.replace(':id', lawyerId);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                subscription_id: subscriptionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('renewSubscriptionModal'));
                modal.hide();
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            showToast('{{ trans("lawyer.error_renewing_subscription") }}', 'error');
        });
    }

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    function confirmBlockUser() {
        if (confirm('{{ trans("lawyer.confirm_block_message") }}')) {
            // Add AJAX call to block user
            alert('{{ trans("lawyer.user_blocked_success") }}');
        }
    }

    function confirmDeleteLawyer() {
        if (confirm('{{ trans("lawyer.confirm_delete_message") }}')) {
            // Add AJAX call or form submission to delete lawyer
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.lawyers.destroy", $lawyer->id) }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function saveOfficeHours() {
        const form = document.getElementById('officeHoursForm');
        const formData = new FormData(form);
        
        // Show loading state
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ trans("common.saving") }}...';
        
        fetch('{{ route("admin.lawyers.update-office-hours", $lawyer->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                alertDiv.style.zIndex = '9999';
                alertDiv.innerHTML = `
                    <i class="uil uil-check-circle me-2"></i>${data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editOfficeHoursModal'));
                modal.hide();
                
                // Reload page after 1 second to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || '{{ trans("lawyer.error_updating_office_hours") }}');
            }
        })
        .catch(error => {
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <i class="uil uil-exclamation-triangle me-2"></i>${error.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Restore button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    }

    // Initialize Select2 when modal is shown
    document.getElementById('editSpecializationsModal').addEventListener('shown.bs.modal', function () {
        $('#sections_of_laws_modal').select2({
            dropdownParent: $('#editSpecializationsModal'),
            placeholder: '{{ trans("lawyer.select_sections_of_laws") }}',
            allowClear: true,
            width: '100%'
        });
    });

    // Save Specializations
    function saveSpecializations() {
        const selectedSections = $('#sections_of_laws_modal').val();
        
        if (!selectedSections || selectedSections.length === 0) {
            showToast('{{ trans("lawyer.select_at_least_one_specialization") }}', 'error');
            return;
        }
        
        // Show loading state
        const saveBtn = event.target;
        const originalText = saveBtn.innerHTML;
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ trans("common.saving") }}...';
        
        const url = '{{ route("admin.lawyers.update-specializations", $lawyer->id) }}';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sections_of_laws: selectedSections
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSpecializationsModal'));
                modal.hide();
                
                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || '{{ trans("lawyer.error_updating_specializations") }}');
            }
        })
        .catch(error => {
            showToast(error.message, 'error');
            
            // Restore button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        });
    }
</script>
@endpush
