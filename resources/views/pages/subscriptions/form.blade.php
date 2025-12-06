@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('subscription.subscriptions_management'), 'url' => route('admin.subscriptions.index')],
                    ['title' => isset($subscription) ? __('subscription.edit_subscription') : __('subscription.create_subscription')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($subscription) ? __('subscription.edit_subscription') : __('subscription.create_subscription') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Alert Container -->
                        <div id="alertContainer"></div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ __('subscription.validation_errors') }}</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="subscriptionForm" 
                              action="{{ isset($subscription) ? route('admin.subscriptions.update', $subscription->id) : route('admin.subscriptions.store') }}" 
                              method="POST">
                            @csrf
                            @if(isset($subscription))
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
                                                    {{ __('subscription.name_english') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="text" 
                                                   class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('translations.' . $language->id . '.name') is-invalid @enderror" 
                                                   id="translation_{{ $language->id }}_name" 
                                                   name="translations[{{ $language->id }}][name]"  
                                                   value="{{ isset($subscription) ? ($subscription->getTranslation('name', $language->code) ?? '') : old('translations.' . $language->id . '.name') }}"
                                                   placeholder="@if($language->code == 'ar')أدخل اسم الباقة بالعربية@else{{ __('subscription.enter_subscription_name_english') }}@endif"
                                                   @if($language->rtl) dir="rtl" @endif>
                                            @error('translations.' . $language->id . '.name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Number of Months --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="number_of_months" class="il-gray fs-14 fw-500 mb-10">
                                            {{ __('subscription.number_of_months') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('number_of_months') is-invalid @enderror" 
                                               id="number_of_months" 
                                               name="number_of_months"  
                                               value="{{ old('number_of_months', $subscription->number_of_months ?? '') }}"
                                               placeholder="{{ __('subscription.enter_number_of_months') }}"
                                               min="1"
                                               max="120">
                                        @error('number_of_months')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">{{ __('subscription.months_hint') }}</small>
                                    </div>
                                </div>

                                {{-- Activation Switcher --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10 d-block">
                                            {{ __('subscription.activation') }}
                                        </label>
                                        <div class="dm-switch-wrap d-flex align-items-center">
                                            <div class="form-check form-switch form-switch-primary form-switch-md">
                                                <input type="hidden" name="active" value="0">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       id="active" 
                                                       name="active" 
                                                       value="1"
                                                       {{ old('active', $subscription->active ?? 1) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('active')
                                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-15 mt-30">
                                <a href="{{ route('admin.subscriptions.index') }}" 
                                   class="btn btn-light btn-default btn-squared fw-400 text-capitalize">
                                    <i class="uil uil-angle-left"></i> {{ __('subscription.cancel') }}
                                </a>
                                <button type="submit" id="submitBtn" 
                                        class="btn btn-primary btn-default btn-squared text-capitalize"
                                        style="display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="uil uil-check"></i> 
                                    <span>{{ isset($subscription) ? __('subscription.update_subscription') : __('subscription.add_subscription') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('subscriptionForm');
        const submitBtn = document.getElementById('submitBtn');
        const alertContainer = document.getElementById('alertContainer');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable button and show spinner
            submitBtn.disabled = true;
            const btnIcon = submitBtn.querySelector('i');
            const btnText = submitBtn.querySelector('span:not(.spinner-border)');
            if (btnIcon) btnIcon.classList.add('d-none');
            if (btnText) btnText.classList.add('d-none');
            submitBtn.querySelector('.spinner-border').classList.remove('d-none');

            // Update loading text dynamically
            const loadingText = @json(isset($subscription) ? __('subscription.updating_subscription') : __('subscription.creating_subscription'));
            const loadingSubtext = @json(__('common.please_wait'));
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
                // Get form data
                const formData = new FormData(form);

                // Send AJAX request
                return fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
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
                    const successMessage = @json(isset($subscription) ? __('subscription.updated_successfully') : __('subscription.created_successfully'));
                    LoadingOverlay.showSuccess(
                        successMessage,
                        @json(__('common.redirecting'))
                    );

                    // Show success alert
                    showAlert('success', data.message || successMessage);

                    // Redirect after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('admin.subscriptions.index') }}';
                    }, 1500);
                });
            })
            .catch(error => {
                // Hide loading overlay and reset progress bar
                LoadingOverlay.hide();

                // Re-enable button
                submitBtn.disabled = false;
                if (btnIcon) btnIcon.classList.remove('d-none');
                if (btnText) btnText.classList.remove('d-none');
                submitBtn.querySelector('.spinner-border').classList.add('d-none');

                // Display validation errors
                if (error.errors) {
                    displayValidationErrors(error.errors);
                    showAlert('danger', error.message || @json(__('subscription.validation_errors')));
                } else {
                    showAlert('danger', error.message || @json(__('subscription.error_saving')));
                }
            });
        });

        function displayValidationErrors(errors) {
            Object.keys(errors).forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = errors[fieldName][0];
                    field.parentNode.appendChild(feedback);
                }
            });
        }

        function showAlert(type, message) {
            const alert = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            alertContainer.innerHTML = alert;
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
</script>
@endpush
