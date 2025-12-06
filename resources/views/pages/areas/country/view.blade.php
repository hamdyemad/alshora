@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('areas/country.countries_management'), 'url' => route('admin.area-settings.countries.index')],
                    ['title' => __('areas/country.view_country')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-500">{{ __('areas/country.country_details') }}</h5>
                        <div class="d-flex gap-10">
                            <a href="{{ route('admin.area-settings.countries.edit', $country->id) }}" class="btn btn-primary btn-sm">
                                <i class="uil uil-edit me-2"></i>{{ __('common.edit') }}
                            </a>
                            <a href="{{ route('admin.area-settings.countries.index') }}" class="btn btn-light btn-sm">
                                <i class="uil uil-arrow-left me-2"></i>{{ __('common.back_to_list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-info-circle me-2"></i>{{ __('common.basic_information') }}
                                </h6>
                            </div>

                            {{-- Dynamic Language Translations --}}
                            @php
                                $languages = \App\Models\Language::all();
                            @endphp
                            
                            @foreach($languages as $language)
                                <div class="col-md-6 mb-25">
                                    <div class="view-item">
                                        <label class="il-gray fs-14 fw-500 mb-10" @if($language->rtl) dir="rtl" style="text-align: right; display: block;" @endif>
                                            @if($language->code == 'ar')
                                                الاسم بالعربية
                                            @elseif($language->code == 'en')
                                                {{ __('areas/country.name_english') }}
                                            @else
                                                {{ __('areas/country.name') }} ({{ $language->name }})
                                            @endif
                                        </label>
                                        <p class="fs-15 color-dark fw-500" @if($language->rtl) dir="rtl" style="text-align: right;" @endif>
                                            {{ $country->getTranslation('name', $language->code) ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('areas/country.country_code') }}</label>
                                    <p class="fs-15 color-dark">{{ $country->code }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('areas/country.phone_code') }}</label>
                                    <p class="fs-15 color-dark">{{ $country->phone_code ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('areas/country.activation') }}</label>
                                    <p class="fs-15">
                                        @if($country->active)
                                            <span class="badge bg-success">{{ __('areas/country.active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('areas/country.inactive') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('areas/country.cities_count') }}</label>
                                    <p class="fs-15 color-dark">
                                        <span class="badge badge-primary" style="border-radius: 6px; padding: 8px 16px; font-size: 14px;">
                                            <i class="uil uil-building me-1"></i>{{ $country->cities()->count() }} {{ __('areas/country.cities') }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            {{-- Quick Actions --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-apps me-2"></i>{{ __('common.quick_actions') }}
                                </h6>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.area-settings.cities.index', ['country_id' => $country->id]) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="uil uil-building me-2"></i>{{ __('areas/country.view_cities') }}
                                    </a>
                                    <a href="{{ route('admin.area-settings.cities.create', ['country_id' => $country->id]) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="uil uil-plus me-2"></i>{{ __('areas/country.add_city') }}
                                    </a>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-clock me-2"></i>{{ __('common.timestamps') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $country->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $country->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
</style>
@endpush
