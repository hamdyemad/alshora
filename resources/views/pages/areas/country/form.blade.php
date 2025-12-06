@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('areas/country.countries_management'), 'url' => route('admin.area-settings.countries.index')],
                    ['title' => isset($country) ? __('areas/country.edit_country') : __('areas/country.create_country')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($country) ? __('areas/country.edit_country') : __('areas/country.create_country') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Alert Container -->
                        <div id="alertContainer"></div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ __('areas/country.validation_errors') }}</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="countryForm" 
                              action="{{ isset($country) ? route('admin.area-settings.countries.update', $country->id) : route('admin.area-settings.countries.store') }}" 
                              method="POST">
                            @csrf
                            @if(isset($country))
                                @method('PUT')
                            @endif

                            <div class="row">
                                {{-- Dynamic Language Fields --}}
                                @foreach($languages as $language)
                                    <div class="col-md-6 mb-25">
                                        <div class="form-group">
                                            <label for="translation_{{ $language->id }}_name" class="il-gray fs-14 fw-500 mb-10" @if($language->rtl) dir="rtl" style="text-align: right; display: block;" @endif>
                                                @if($language->code == 'ar')
                                                    الاسم ({{ $language->name }}) <span class="text-danger">*</span>
                                                @else
                                                    {{ __('areas/country.name_english') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="text" 
                                                   class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('translations.' . $language->id . '.name') is-invalid @enderror" 
                                                   id="translation_{{ $language->id }}_name" 
                                                   name="translations[{{ $language->id }}][name]"  
                                                   value="{{ isset($country) ? ($country->getTranslation('name', $language->code) ?? '') : old('translations.' . $language->id . '.name') }}"
                                                   placeholder="@if($language->code == 'ar')أدخل اسم الدولة بالعربية@else{{ __('areas/country.enter_country_name_english') }}@endif"
                                                   @if($language->rtl) dir="rtl" @endif>
                                            @error('translations.' . $language->id . '.name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Country Code --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="code" class="il-gray fs-14 fw-500 mb-10">
                                            {{ __('areas/country.country_code') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('code') is-invalid @enderror" 
                                               id="code" 
                                               name="code"  
                                               value="{{ old('code', $country->code ?? '') }}"
                                               placeholder="{{ __('areas/country.enter_country_code') }}"
                                               maxlength="3">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Phone Code --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="phone_code" class="il-gray fs-14 fw-500 mb-10">
                                            {{ __('areas/country.phone_code') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('phone_code') is-invalid @enderror" 
                                               id="phone_code" 
                                               name="phone_code"  
                                               value="{{ old('phone_code', $country->phone_code ?? '') }}"
                                               placeholder="{{ __('areas/country.enter_phone_code') }}">
                                        @error('phone_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Activation Switcher --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10 d-block">
                                            {{ __('areas/country.activation') }}
                                        </label>
                                        <div class="dm-switch-wrap d-flex align-items-center">
                                            <div class="form-check form-switch form-switch-primary form-switch-md">
                                                <input type="hidden" name="active" value="0">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       id="active" 
                                                       name="active" 
                                                       value="1"
                                                       {{ old('active', $country->active ?? 1) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('active')
                                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Default Country Switcher --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10 d-block">
                                            {{ __('areas/country.default_country') }}
                                        </label>
                                        <div class="dm-switch-wrap d-flex align-items-center">
                                            <div class="form-check form-switch form-switch-primary form-switch-md">
                                                <input type="hidden" name="default_country" value="0">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       id="default_country" 
                                                       name="default_country" 
                                                       value="1"
                                                       {{ old('default_country', $country->default_country ?? 0) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('default_country')
                                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-15 mt-30">
                                <a href="{{ route('admin.area-settings.countries.index') }}" 
                                   class="btn btn-light btn-default btn-squared fw-400 text-capitalize">
                                    <i class="uil uil-angle-left"></i> {{ __('areas/country.cancel') }}
                                </a>
                                <button type="submit" id="submitBtn" 
                                        class="btn btn-primary btn-default btn-squared text-capitalize"
                                        style="display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="uil uil-check"></i> 
                                    <span>{{ isset($country) ? __('areas/country.update_country') : __('areas/country.add_country') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AJAX Form Submission
            const countryForm = document.getElementById('countryForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

            countryForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                // Update loading text dynamically
                const loadingText = @json(isset($country) ? trans('loading.updating') : trans('loading.creating'));
                const loadingSubtext = '{{ trans("loading.please_wait") }}';
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    overlay.querySelector('.loading-text').textContent = loadingText;
                    overlay.querySelector('.loading-subtext').textContent = loadingSubtext;
                }
                
                // Show loading overlay
                LoadingOverlay.show();

                // Clear previous alerts
                alertContainer.innerHTML = '';

                // Remove previous validation errors
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                // Start progress bar animation
                LoadingOverlay.animateProgressBar(30, 300).then(() => {
                    // Prepare form data
                    const formData = new FormData(countryForm);

                    // Send AJAX request
                    return fetch(countryForm.action, {
                        method: countryForm.method,
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });
                })
                .then(response => {
                    // Progress to 60%
                    LoadingOverlay.animateProgressBar(60, 200);
                    
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Progress to 90%
                    return LoadingOverlay.animateProgressBar(90, 200).then(() => data);
                })
                .then(data => {
                    // Complete progress bar
                    return LoadingOverlay.animateProgressBar(100, 200).then(() => {
                        // Show success animation with dynamic message
                        const successMessage = @json(isset($country) ? trans('loading.updated_successfully') : trans('loading.created_successfully'));
                        LoadingOverlay.showSuccess(
                            successMessage,
                            '{{ trans("loading.redirecting") }}'
                        );
                        
                        // Show success alert
                        showAlert('success', data.message || successMessage);
                        
                        // Redirect after 1.5 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route("admin.area-settings.countries.index") }}';
                        }, 1500);
                    });
                })
                .catch(error => {
                    // Hide loading overlay and reset progress bar
                    LoadingOverlay.hide();
                    
                    // Handle validation errors
                    if (error.errors) {
                        // Clear previous errors
                        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                        
                        Object.keys(error.errors).forEach(key => {
                            // Handle nested field names like translations.1.name
                            let fieldName = key;
                            
                            // Convert dot notation to bracket notation for translations
                            if (key.includes('translations.')) {
                                // translations.1.name -> translations[1][name]
                                fieldName = key.replace(/translations\.(\d+)\.(\w+)/, 'translations[$1][$2]');
                            }
                            
                            // Try to find the input with exact name match
                            let input = document.querySelector(`[name="${fieldName}"]`);
                            
                            // If not found, try with escaped brackets
                            if (!input) {
                                input = document.querySelector(`[name="${fieldName.replace(/\[/g, '\\[').replace(/\]/g, '\\]')}"]`);
                            }
                            
                            if (input) {
                                input.classList.add('is-invalid');
                                
                                // Remove existing feedback if any
                                const existingFeedback = input.parentNode.querySelector('.invalid-feedback');
                                if (existingFeedback) {
                                    existingFeedback.remove();
                                }
                                
                                // Add new feedback
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                feedback.textContent = error.errors[key][0];
                                input.parentNode.appendChild(feedback);
                                
                                // Scroll to first error
                                if (Object.keys(error.errors)[0] === key) {
                                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }
                        });
                        showAlert('danger', error.message || '{{ __("Please check the form for errors") }}');
                    } else {
                        showAlert('danger', error.message || '{{ __("An error occurred") }}');
                    }
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    const btnIcon = submitBtn.querySelector('i');
                    const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                    if (btnIcon) btnIcon.classList.remove('d-none');
                    if (btnText) btnText.classList.remove('d-none');
                    submitBtn.querySelector('.spinner-border').classList.add('d-none');
                });
            });

            // Show alert function
            function showAlert(type, message) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show mb-20`;
                alert.innerHTML = `
                    <i class="uil uil-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                alertContainer.appendChild(alert);

                // Scroll to top to show alert
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    </script>
    @endpush
@endsection

{{-- Include Loading Overlay Component outside content section --}}
@push('after-body')
    <x-loading-overlay 
        :loadingText="trans('loading.processing')" 
        :loadingSubtext="trans('loading.please_wait')" 
    />
@endpush
