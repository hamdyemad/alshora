@extends('layout.app')

@section('styles')
    {{-- Styles are included globally in app.scss --}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('vendor.vendors_management'), 'url' => route('admin.vendors.index')],
                    ['title' => isset($vendor) ? __('vendor.edit_vendor') : __('vendor.create_vendor')]
                ]" />
            </div>
        </div>

        <div class="checkout wizard1 wizard7 global-shadow px-sm-50 px-20 py-sm-50 py-30 mb-30 bg-white radius-xl w-100">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-12">
                    {{-- Use Wizard Component --}}
                    <x-wizard :steps="[
                        __('vendor.vendor_information'),
                        __('vendor.vendor_documents'),
                        __('vendor.vendor_account_details'),
                        __('vendor.review_submit')
                    ]" :currentStep="1" />

                    <form id="vendorForm" action="{{ route('admin.vendors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Step 1: Vendor Information --}}
                        <div class="wizard-step" id="step-1">
                            <div class="card checkout-shipping-form shadow-none border-0 mt-3">
                                <div class="card-header border-bottom-0 pb-sm-0 pb-1">
                                    <h4 class="fw-500">1. {{ __('vendor.vendor_information') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        {{-- Logo Upload --}}
                                        <div class="col-md-6 mb-25">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.logo') }} <span class="text-danger">*</span>
                                                </label>
                                                <div class="logo-upload-wrapper">
                                                    <div class="logo-preview-container" id="logo-preview-container">
                                                        <div class="logo-placeholder" id="logo-placeholder">
                                                            <i class="uil uil-image-plus"></i>
                                                            <p>{{ __('vendor.click_to_upload_logo') }}</p>
                                                            <small>{{ __('vendor.recommended_logo_size') }}</small>
                                                        </div>
                                                        <div class="logo-overlay">
                                                            <button type="button" class="btn-change-image">
                                                                <i class="uil uil-camera"></i> {{ __('vendor.change') }}
                                                            </button>
                                                            <button type="button" class="btn-remove-image" style="display: none;">
                                                                <i class="uil uil-trash-alt"></i> {{ __('vendor.remove') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="file" class="d-none" id="logo" name="logo" accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Banner Upload --}}
                                        <div class="col-md-6 mb-25">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.banner') }} <span class="text-danger">*</span>
                                                </label>
                                                <div class="image-upload-wrapper">
                                                    <div class="image-preview-container banner-preview" id="banner-preview-container">
                                                        <div class="image-placeholder" id="banner-placeholder">
                                                            <i class="uil uil-image-plus"></i>
                                                            <p>{{ __('vendor.click_to_upload_banner') }}</p>
                                                            <small>{{ __('vendor.recommended_banner_size') }}</small>
                                                        </div>
                                                        <div class="image-overlay">
                                                            <button type="button" class="btn-change-image">
                                                                <i class="uil uil-camera"></i> {{ __('vendor.change') }}
                                                            </button>
                                                            <button type="button" class="btn-remove-image" style="display: none;">
                                                                <i class="uil uil-trash-alt"></i> {{ __('vendor.remove') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <input type="file" class="d-none" id="banner" name="banner" accept="image/*">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Name Fields for Each Language --}}
                                        @foreach($languages as $language)
                                        <div class="col-md-6 mb-25">
                                            <div class="form-group">
                                                <label for="translations_{{ $language->id }}_name" 
                                                       class="il-gray fs-14 fw-500 mb-10"
                                                       @if($language->rtl) dir="rtl" @endif>
                                                    {{ __('vendor.name') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="translations_{{ $language->id }}_name" 
                                                       name="translations[{{ $language->id }}][name]"  
                                                       placeholder="{{ __('vendor.enter_vendor_name_in') }} {{ $language->name }}"
                                                       @if($language->rtl) dir="rtl" @endif>
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- Description Fields for Each Language --}}
                                        @foreach($languages as $language)
                                        <div class="col-md-6 mb-25">
                                            <div class="form-group">
                                                <label for="translations_{{ $language->id }}_description" 
                                                       class="il-gray fs-14 fw-500 mb-10"
                                                       @if($language->rtl) dir="rtl" @endif>
                                                    {{ __('vendor.description') }} ({{ $language->name }})
                                                </label>
                                                <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                          id="translations_{{ $language->id }}_description" 
                                                          name="translations[{{ $language->id }}][description]" 
                                                          rows="4"
                                                          placeholder="{{ __('vendor.enter_vendor_description_in') }} {{ $language->name }}"
                                                          @if($language->rtl) dir="rtl" @endif></textarea>
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- Country Selection --}}
                                        <div class="col-12 mb-25">
                                            <div class="form-group">
                                                <label for="country_id" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.country') }} <span class="text-danger">*</span>
                                                </label>
                                                <div class="dm-select">
                                                    <select class="form-control select2-country" 
                                                            id="country_id" 
                                                            name="country_id">
                                                        <option value="">{{ __('vendor.select_country') }}</option>
                                                        @if(isset($countries))
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country['id'] }}">
                                                                    {{ $country['name'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Activity Selection --}}
                                        <div class="col-12 mb-25">
                                            <div class="form-group">
                                                <label for="activity_ids" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('common.activity') }} <span class="text-danger">*</span>
                                                </label>
                                                <div class="dm-select">
                                                    <select class="form-control select2-activity" 
                                                            id="activity_ids" 
                                                            name="activity_ids[]"
                                                            multiple>
                                                        @if(isset($activities))
                                                            @foreach($activities as $activity)
                                                                <option value="{{ $activity['id'] }}">
                                                                    {{ $activity['name'] }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- SEO Section Header --}}
                                        <div class="col-md-12 mb-20">
                                            <h6 class="fw-500 color-dark border-bottom pb-15">
                                                <i class="uil uil-search me-2"></i>{{ __('vendor.seo_information') }}
                                            </h6>
                                        </div>

                                        {{-- Meta Title --}}
                                        <div class="col-md-12 mb-25">
                                            <div class="form-group">
                                                <label for="meta_title" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.meta_title') }}
                                                </label>
                                                <input type="text" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="meta_title" 
                                                       name="meta_title"  
                                                       placeholder="{{ __('vendor.enter_meta_title') }}">
                                            </div>
                                        </div>

                                        {{-- Meta Description --}}
                                        <div class="col-md-12 mb-25">
                                            <div class="form-group">
                                                <label for="meta_description" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.meta_description') }}
                                                </label>
                                                <textarea class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                          id="meta_description" 
                                                          name="meta_description" 
                                                          rows="3"
                                                          placeholder="{{ __('vendor.enter_meta_description') }}"></textarea>
                                            </div>
                                        </div>

                                        {{-- Meta Keywords --}}
                                        <div class="col-md-12 mb-25">
                                            <div class="form-group">
                                                <label for="meta_keywords" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.meta_keywords') }}
                                                </label>
                                                <input type="text" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="meta_keywords" 
                                                       name="meta_keywords"  
                                                       placeholder="{{ __('vendor.enter_meta_keywords') }}">
                                                <small class="form-text text-muted">{{ __('vendor.separate_keywords_commas') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-20 mb-20 justify-content-end">
                                        <button type="button" class="btn btn-primary btn-default btn-squared next-step">
                                            {{ __('vendor.next') }} <i class="ms-10 me-0 las la-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Vendor Documents --}}
                        <div class="wizard-step" id="step-2" style="display: none;">
                            <div class="card checkout-shipping-form shadow-none border-0 mt-3">
                                <div class="card-header border-bottom-0 pb-sm-0 pb-1 d-flex justify-content-between align-items-center">
                                    <h4 class="fw-500">2. {{ __('vendor.vendor_documents') }}</h4>
                                    <button type="button" class="btn btn-sm btn-secondary" id="addDocumentRow">
                                        <i class="uil uil-plus"></i> {{ __('vendor.add_document') }}
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="documentsContainer">
                                        {{-- First Document Row --}}
                                        <div class="document-row mb-25">
                                            <div class="row">
                                                @foreach($languages as $language)
                                                <div class="col-12 mb-3">
                                                    <label class="il-gray fs-14 fw-500 mb-10"
                                                           @if($language->rtl) dir="rtl" style="text-align: right; display: block;" @endif>
                                                        {{ __('vendor.document_name') }} ({{ $language->name }})
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                           name="documents[0][translations][{{ $language->id }}][name]"
                                                           placeholder="{{ __('vendor.eg_business_license') }}"
                                                           @if($language->rtl) dir="rtl" @endif>
                                                </div>
                                                @endforeach
                                                <div class="col-6 mb-3">
                                                    <label class="il-gray fs-14 fw-500 mb-10">
                                                        {{ __('vendor.document_file') }}
                                                    </label>
                                                    <div class="image-upload-wrapper">
                                                        <div class="image-preview-container document-preview" id="document-preview-container-0">
                                                            <div class="image-placeholder" id="document-placeholder-0">
                                                                <i class="uil uil-file-upload"></i>
                                                                <p>{{ __('vendor.click_to_upload_document') }}</p>
                                                                <small>{{ __('vendor.accepted_document_types') }}</small>
                                                            </div>
                                                            <div class="image-overlay">
                                                                <button type="button" class="btn-change-image">
                                                                    <i class="uil uil-file-upload"></i> {{ __('vendor.change') }}
                                                                </button>
                                                                <button type="button" class="btn-remove-image" style="display: none;">
                                                                    <i class="uil uil-trash-alt"></i> {{ __('vendor.remove') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <input type="file" 
                                                               class="d-none" 
                                                               id="document-file-0"
                                                               name="documents[0][file]"
                                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-document-row w-100" style="display: none;">
                                                        <i class="uil uil-trash-alt"></i> {{ __('vendor.remove') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex pt-20 mb-20 justify-content-between">
                                        <button type="button" class="btn btn-light btn-default btn-squared prev-step">
                                            <i class="me-10 ms-0 las la-arrow-left"></i> {{ __('vendor.previous') }}
                                        </button>
                                        <button type="button" class="btn btn-primary btn-default btn-squared next-step">
                                            {{ __('vendor.next') }} <i class="ms-10 me-0 las la-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Vendor Account --}}
                        <div class="wizard-step" id="step-3" style="display: none;">
                            <div class="card checkout-shipping-form shadow-none border-0 mt-3">
                                <div class="card-header border-bottom-0 pb-sm-0 pb-1">
                                    <h4 class="fw-500">3. {{ __('vendor.vendor_account_details') }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        {{-- Email --}}
                                        <div class="col-md-12 mb-25">
                                            <div class="form-group">
                                                <label for="email" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.email') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="email" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="email" 
                                                       name="email"  
                                                       placeholder="{{ __('vendor.enter_email') }}"
                                                       @if(Helper::is_rtl()) dir="rtl" @endif>
                                            </div>
                                        </div>

                                        {{-- Password --}}
                                        <div class="col-md-6 mb-25">
                                            <div class="form-group">
                                                <label for="password" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.password') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="password" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="password" 
                                                       name="password"  
                                                       placeholder="{{ __('vendor.enter_password') }}">
                                            </div>
                                        </div>

                                        {{-- Password Confirmation --}}
                                        <div class="col-md-6 mb-25">
                                            <div class="form-group">
                                                <label for="password_confirmation" class="il-gray fs-14 fw-500 mb-10">
                                                    {{ __('vendor.confirm_password') }} <span class="text-danger">*</span>
                                                </label>
                                                <input type="password" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation"  
                                                       placeholder="{{ __('vendor.confirm_password') }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex pt-20 mb-20 justify-content-between">
                                        <button type="button" class="btn btn-light btn-default btn-squared prev-step">
                                            <i class="me-10 ms-0 las la-arrow-left"></i> {{ __('vendor.previous') }}
                                        </button>
                                        <button type="button" class="btn btn-primary btn-default btn-squared next-step">
                                            {{ __('vendor.next') }} <i class="ms-10 me-0 las la-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 4: Review & Submit --}}
                        <div class="wizard-step" id="step-4" style="display: none;">
                            <div class="card checkout-shipping-form shadow-none border-0 mt-3">
                                <div class="card-header border-bottom-0 pb-sm-0 pb-1">
                                    <h4 class="fw-500">4. {{ __('vendor.review_submit') }}</h4>
                                </div>
                                <div class="card-body">
                                    @if($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show mb-25" role="alert">
                                            <h5 class="alert-heading"><i class="uil uil-exclamation-triangle me-2"></i>{{ __('vendor.validation_errors') }}</h5>
                                            <p>{{ __('vendor.please_fix_errors') }}</p>
                                            <hr>
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show mb-25" role="alert">
                                            <h5 class="alert-heading"><i class="uil uil-times-circle me-2"></i>{{ __('vendor.error') }}</h5>
                                            <p class="mb-0">{{ session('error') }}</p>
                                            @if(session('error_details'))
                                                <hr>
                                                <small class="text-muted">{{ session('error_details') }}</small>
                                            @endif
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <div class="alert alert-info mb-25">
                                        <i class="uil uil-info-circle me-2"></i>
                                        {{ __('vendor.please_review_info') }}
                                    </div>

                                    {{-- Review: Vendor Information --}}
                                    <div class="review-section mb-25" data-edit-step="1" style="cursor: pointer;">
                                        <div class="card border">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-500">
                                                    <i class="uil uil-info-circle me-2"></i>{{ __('vendor.vendor_information') }}
                                                </h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-section-btn">
                                                    <i class="uil uil-edit"></i> {{ __('common.edit') }}
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    {{-- Logo Preview --}}
                                                    <div class="col-md-6 mb-20">
                                                        <label class="fw-500 mb-10 d-block">{{ __('vendor.logo') }}</label>
                                                        <div id="review-logo" class="review-image-container">
                                                            <span class="text-muted">{{ __('vendor.no_logo_uploaded') }}</span>
                                                        </div>
                                                    </div>

                                                    {{-- Banner Preview --}}
                                                    <div class="col-md-6 mb-20">
                                                        <label class="fw-500 mb-10 d-block">{{ __('vendor.banner') }}</label>
                                                        <div id="review-banner" class="review-image-container">
                                                            <span class="text-muted">{{ __('vendor.no_banner_uploaded') }}</span>
                                                        </div>
                                                    </div>

                                                    {{-- Names (All Languages) --}}
                                                    <div class="col-md-12 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.name') }}</label>
                                                        <div id="review-names">{{ __('vendor.not_provided') }}</div>
                                                    </div>

                                                    {{-- Descriptions (All Languages) --}}
                                                    <div class="col-md-12 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.description') }}</label>
                                                        <div id="review-descriptions">{{ __('vendor.not_provided') }}</div>
                                                    </div>

                                                    {{-- Country --}}
                                                    <div class="col-md-6 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.country') }}</label>
                                                        <p class="mb-0" id="review-country">{{ __('vendor.not_provided') }}</p>
                                                    </div>

                                                    {{-- Activity --}}
                                                    <div class="col-md-6 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('common.activity') }}</label>
                                                        <p class="mb-0" id="review-activity">{{ __('vendor.not_provided') }}</p>
                                                    </div>

                                                    {{-- Meta Title --}}
                                                    <div class="col-md-12 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.meta_title') }}</label>
                                                        <p class="mb-0" id="review-meta-title">{{ __('vendor.not_provided') }}</p>
                                                    </div>

                                                    {{-- Meta Description --}}
                                                    <div class="col-md-12 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.meta_description') }}</label>
                                                        <p class="mb-0" id="review-meta-description">{{ __('vendor.not_provided') }}</p>
                                                    </div>

                                                    {{-- Meta Keywords --}}
                                                    <div class="col-md-12 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.meta_keywords') }}</label>
                                                        <p class="mb-0" id="review-meta-keywords">{{ __('vendor.not_provided') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Review: Documents --}}
                                    <div class="review-section mb-25" data-edit-step="2" style="cursor: pointer;">
                                        <div class="card border">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-500">
                                                    <i class="uil uil-file-alt me-2"></i>{{ __('vendor.vendor_documents') }}
                                                </h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-section-btn">
                                                    <i class="uil uil-edit"></i> {{ __('common.edit') }}
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div id="review-documents">
                                                    <p class="text-muted">{{ __('vendor.no_documents_uploaded') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Review: Account Details --}}
                                    <div class="review-section mb-25" data-edit-step="3" style="cursor: pointer;">
                                        <div class="card border">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-500">
                                                    <i class="uil uil-user me-2"></i>{{ __('vendor.vendor_account_details') }}
                                                </h6>
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-section-btn">
                                                    <i class="uil uil-edit"></i> {{ __('common.edit') }}
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-15">
                                                        <label class="fw-500 text-muted fs-13">{{ __('vendor.email') }}</label>
                                                        <p class="mb-0" id="review-email">{{ __('vendor.not_provided') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex pt-20 mb-20 justify-content-between">
                                        <button type="button" class="btn btn-light btn-default btn-squared prev-step">
                                            <i class="me-10 ms-0 las la-arrow-left"></i> {{ __('vendor.previous') }}
                                        </button>
                                        <button type="submit" id="submitBtn" 
                                                class="btn btn-success btn-default btn-squared"
                                                style="display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                            <i class="uil uil-check"></i>
                                            <span>{{ __('vendor.create_vendor_button') }}</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Pass translations to JavaScript
        window.vendorTranslations = {
            noLogoUploaded: "{{ __('vendor.no_logo_uploaded') }}",
            noBannerUploaded: "{{ __('vendor.no_banner_uploaded') }}",
            noDocumentsUploaded: "{{ __('vendor.no_documents_uploaded') }}",
            vendorCreatedSuccessfully: "{{ __('vendor.vendor_created_successfully') }}",
            redirecting: "{{ __('vendor.redirecting') }}",
            pleaseCheckFormErrors: "{{ __('vendor.please_check_form_errors') }}",
            anErrorOccurred: "{{ __('vendor.an_error_occurred') }}"
        };
    </script>
    @vite(['resources/js/vendor-form.js'])
@endpush

{{-- Include Loading Overlay Component outside content section --}}
@push('after-body')
    <x-loading-overlay 
        :loadingText="trans('vendor.creating_vendor')" 
        :loadingSubtext="trans('vendor.please_wait')" 
    />
@endpush