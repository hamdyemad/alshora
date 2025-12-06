@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('subscription.subscriptions_management'), 'url' => route('admin.subscriptions.index')],
                    ['title' => __('subscription.view_subscription')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-500">{{ __('subscription.subscription_details') }}</h5>
                        <div class="d-flex gap-10">
                            <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-primary btn-sm">
                                <i class="uil uil-edit me-2"></i>{{ __('common.edit') }}
                            </a>
                            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-light btn-sm">
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
                                                {{ __('subscription.name_english') }}
                                            @else
                                                {{ __('subscription.name') }} ({{ $language->name }})
                                            @endif
                                        </label>
                                        <p class="fs-15 color-dark fw-500" @if($language->rtl) dir="rtl" style="text-align: right;" @endif>
                                            {{ $subscription->getTranslation('name', $language->code) ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('subscription.number_of_months') }}</label>
                                    <p class="fs-15 color-dark">
                                        <span class="badge badge-round badge-lg badge-info">
                                            <i class="uil uil-calendar-alt me-1"></i>{{ $subscription->number_of_months }} {{ __('subscription.months') }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('subscription.activation') }}</label>
                                    <p class="fs-15">
                                        @if($subscription->active)
                                            <span class="badge badge-round badge-lg badge-success">
                                                <i class="uil uil-check me-1"></i>{{ __('subscription.active') }}
                                            </span>
                                        @else
                                            <span class="badge badge-round badge-lg badge-danger">
                                                <i class="uil uil-times me-1"></i>{{ __('subscription.inactive') }}
                                            </span>
                                        @endif
                                    </p>
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
                                    <p class="fs-15 color-dark">{{ $subscription->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $subscription->updated_at->format('Y-m-d H:i:s') }}</p>
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
