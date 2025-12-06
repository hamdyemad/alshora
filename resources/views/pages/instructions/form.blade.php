@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('instructions.instructions_management'), 'url' => route('admin.instructions.index')],
                    ['title' => isset($instruction) ? __('instructions.edit_instruction') : __('instructions.create_instruction')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($instruction) ? __('instructions.edit_instruction') : __('instructions.create_instruction') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Alert Container --}}
                        <div id="alertContainer"></div>

                        <form action="{{ isset($instruction) ? route('admin.instructions.update', $instruction->id) : route('admin.instructions.store') }}" 
                              method="POST" 
                              id="instructionForm">
                            @csrf
                            @if(isset($instruction))
                                @method('PUT')
                            @endif

                            {{-- Title Fields --}}
                            <div class="row">
                                <div class="col-12 mb-25">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-text me-2"></i>{{ __('instructions.title') }}
                                    </h6>
                                </div>

                                @foreach($languages as $language)
                                    <div class="col-md-6 mb-25">
                                        <div class="form-group">
                                            <label for="title_{{ $language->code }}" class="il-gray fs-14 fw-500 mb-10">
                                                {{ __('instructions.title') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('title_' . $language->code) is-invalid @enderror"
                                                id="title_{{ $language->code }}" 
                                                name="title_{{ $language->code }}"
                                                value="{{ isset($instruction) ? $instruction->getTranslation('title', $language->code) : old('title_' . $language->code) }}"
                                                placeholder="{{ __('instructions.enter_title_' . $language->code) }}"
                                                @if($language->rtl) dir="rtl" @endif>
                                            @error('title_' . $language->code)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Description Fields --}}
                            <div class="row">
                                <div class="col-12 mb-25 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-file-alt me-2"></i>{{ __('instructions.description') }}
                                    </h6>
                                </div>

                                @foreach($languages as $language)
                                    <div class="col-md-6 mb-25">
                                        <div class="form-group">
                                            <label for="description_{{ $language->code }}" class="il-gray fs-14 fw-500 mb-10">
                                                {{ __('instructions.description') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                            </label>
                                            <textarea
                                                class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('description_' . $language->code) is-invalid @enderror"
                                                id="description_{{ $language->code }}" 
                                                name="description_{{ $language->code }}"
                                                rows="8"
                                                placeholder="{{ __('instructions.enter_description_' . $language->code) }}"
                                                @if($language->rtl) dir="rtl" @endif>{{ isset($instruction) ? $instruction->getTranslation('description', $language->code) : old('description_' . $language->code) }}</textarea>
                                            @error('description_' . $language->code)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Status --}}
                            <div class="row">
                                <div class="col-12 mb-25 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-setting me-2"></i>{{ __('common.basic_information') }}
                                    </h6>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="active" class="il-gray fs-14 fw-500 mb-10">
                                            {{ __('instructions.activation') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('active') is-invalid @enderror"
                                            id="active" name="active">
                                            <option value="1"
                                                {{ (isset($instruction) && $instruction->active == 1) || old('active') == 1 ? 'selected' : '' }}>
                                                {{ __('instructions.active') }}</option>
                                            <option value="0"
                                                {{ (isset($instruction) && $instruction->active == 0) || old('active') == 0 ? 'selected' : '' }}>
                                                {{ __('instructions.inactive') }}</option>
                                        </select>
                                        @error('active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="button-group d-flex pt-25 justify-content-end">
                                        <a href="{{ route('admin.instructions.index') }}" class="btn btn-light btn-default btn-squared me-15 text-capitalize">
                                            {{ __('instructions.cancel') }}
                                        </a>
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-default btn-squared text-capitalize" style="display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="uil uil-check"></i>
                                            <span class="ms-2">{{ __('instructions.save') }}</span>
                                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AJAX Form Submission
            const instructionForm = document.getElementById('instructionForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

            instructionForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                // Update loading text dynamically
                const loadingText = @json(isset($instruction) ? __('instructions.update_instruction') : __('instructions.create_instruction'));
                const loadingSubtext = 'Please wait';
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
                        const formData = new FormData(instructionForm);

                        // Send AJAX request
                        return fetch(instructionForm.action, {
                            method: 'POST',
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
                            const successMessage = @json(isset($instruction) ? __('instructions.updated_successfully') : __('instructions.created_successfully'));
                            LoadingOverlay.showSuccess(
                                successMessage,
                                'Redirecting...'
                            );

                            // Show success alert
                            showAlert('success', data.message || successMessage);

                            // Redirect after 1.5 seconds
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('admin.instructions.index') }}';
                            }, 1500);
                        });
                    })
                    .catch(error => {
                        // Hide loading overlay and reset progress bar
                        LoadingOverlay.hide();

                        // Handle validation errors
                        if (error.errors) {
                            Object.keys(error.errors).forEach(key => {
                                let input = document.querySelector(`[name="${key}"]`);

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
                                        input.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }
                                }
                            });
                            showAlert('danger', error.message || 'Please check the form for errors');
                        } else {
                            showAlert('danger', error.message || 'An error occurred');
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
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                alertContainer.appendChild(alert);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            }
        });
    </script>
@endpush
