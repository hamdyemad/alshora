@extends('layout.app')

@section('styles')
    <style>
        /* Image Upload Styles */
        .logo-upload-wrapper {
            width: 100%;
        }

        .logo-preview-container {
            position: relative;
            width: 100%;
            height: 250px;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9fafb;
            transition: all 0.3s ease;
        }

        .logo-preview-container:hover {
            border-color: #8231D3;
            background-color: #f5f3ff;
        }

        .logo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            text-align: center;
            padding: 20px;
        }

        .logo-placeholder i {
            font-size: 48px;
            margin-bottom: 12px;
            color: #d1d5db;
        }

        .logo-placeholder p {
            margin: 0 0 5px 0;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
        }

        .logo-placeholder small {
            font-size: 12px;
            color: #9ca3af;
        }

        .uploaded-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .logo-preview-container:hover .logo-overlay {
            opacity: 1;
        }

        .btn-change-image,
        .btn-remove-image {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-change-image {
            background-color: #8231D3;
            color: white;
        }

        .btn-change-image:hover {
            background-color: #6b21a8;
            transform: translateY(-2px);
        }

        .btn-remove-image {
            background-color: #ef4444;
            color: white;
        }

        .btn-remove-image:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
        }

        .btn-change-image i,
        .btn-remove-image i {
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('branches_of_laws.branches_of_laws_management'), 'url' => route('admin.branches-of-laws.index')],
                    ['title' => isset($branchOfLaw) ? __('branches_of_laws.edit_branch_of_law') : __('branches_of_laws.create_branch_of_law')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($branchOfLaw) ? __('branches_of_laws.edit_branch_of_law') : __('branches_of_laws.create_branch_of_law') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Alert Container --}}
                        <div id="alertContainer"></div>

                        <form action="{{ isset($branchOfLaw) ? route('admin.branches-of-laws.update', $branchOfLaw->id) : route('admin.branches-of-laws.store') }}" 
                              method="POST" 
                              id="branchOfLawForm"
                              enctype="multipart/form-data">
                            @csrf
                            @if(isset($branchOfLaw))
                                @method('PUT')
                            @endif

                            {{-- Title Fields --}}
                            <div class="row">
                                <div class="col-12 mb-25">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-text me-2"></i>{{ __('branches_of_laws.title') }}
                                    </h6>
                                </div>

                                @foreach($languages as $language)
                                    <div class="col-md-6 mb-25">
                                        <div class="form-group">
                                            <label for="title_{{ $language->code }}" class="il-gray fs-14 fw-500 mb-10">
                                                {{ __('branches_of_laws.title') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('title_' . $language->code) is-invalid @enderror"
                                                id="title_{{ $language->code }}" 
                                                name="title_{{ $language->code }}"
                                                value="{{ isset($branchOfLaw) ? $branchOfLaw->getTranslation('title', $language->code) : old('title_' . $language->code) }}"
                                                placeholder="{{ __('branches_of_laws.enter_title_' . $language->code) }}"
                                                @if($language->rtl) dir="rtl" @endif>
                                            @error('title_' . $language->code)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Image and Status --}}
                            <div class="row">
                                <div class="col-12 mb-25 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-image me-2"></i>{{ __('common.basic_information') }}
                                    </h6>
                                </div>

                                {{-- Image Upload --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10">
                                            {{ __('branches_of_laws.image') }}
                                            @if(!isset($branchOfLaw))
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <div class="logo-upload-wrapper">
                                            <div class="logo-preview-container" id="branch-image-preview-container">
                                                @if (isset($branchOfLaw) && $branchOfLaw->image)
                                                    <img src="{{ asset('storage/' . $branchOfLaw->image->path) }}"
                                                        alt="Branch Image" id="branch-image-preview"
                                                        class="uploaded-image">
                                                @else
                                                    <div class="logo-placeholder" id="branch-image-placeholder">
                                                        <i class="uil uil-image"></i>
                                                        <p>{{ __('branches_of_laws.click_upload_image') }}</p>
                                                        <small>{{ __('branches_of_laws.recommended_size') }}</small>
                                                    </div>
                                                @endif
                                                <div class="logo-overlay">
                                                    <button type="button" class="btn-change-image"
                                                        onclick="document.getElementById('image').click()">
                                                        <i class="uil uil-camera"></i> {{ __('branches_of_laws.change') }}
                                                    </button>
                                                    <button type="button" class="btn-remove-image"
                                                        onclick="removeImage('branch-image')"
                                                        style="{{ isset($branchOfLaw) && $branchOfLaw->image ? '' : 'display: none;' }}">
                                                        <i class="uil uil-trash-alt"></i> {{ __('branches_of_laws.remove') }}
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="image" name="image"
                                                accept="image/*" onchange="previewBranchImage(this, 'branch-image')">
                                        </div>
                                        @error('image')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Active Status --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="active" class="il-gray fs-14 fw-500 mb-10">
                                            {{ __('branches_of_laws.activation') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('active') is-invalid @enderror"
                                            id="active" name="active">
                                            <option value="1"
                                                {{ (isset($branchOfLaw) && $branchOfLaw->active == 1) || old('active') == 1 ? 'selected' : '' }}>
                                                {{ __('branches_of_laws.active') }}</option>
                                            <option value="0"
                                                {{ (isset($branchOfLaw) && $branchOfLaw->active == 0) || old('active') == 0 ? 'selected' : '' }}>
                                                {{ __('branches_of_laws.inactive') }}</option>
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
                                        <a href="{{ route('admin.branches-of-laws.index') }}" class="btn btn-light btn-default btn-squared me-15 text-capitalize">
                                            {{ __('branches_of_laws.cancel') }}
                                        </a>
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-default btn-squared text-capitalize" style="display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="uil uil-check"></i>
                                            <span class="ms-2">{{ __('branches_of_laws.save') }}</span>
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
        // Image Preview Function
        function previewBranchImage(input, type) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const container = document.getElementById(`${type}-preview-container`);
                    const placeholder = document.getElementById(`${type}-placeholder`);
                    const removeBtn = container.querySelector('.btn-remove-image');

                    // Check if preview image already exists
                    let previewImg = document.getElementById(`${type}-preview`);

                    if (!previewImg) {
                        // Create new image element
                        previewImg = document.createElement('img');
                        previewImg.id = `${type}-preview`;
                        previewImg.className = 'uploaded-image';
                        previewImg.alt = 'Preview';
                        container.insertBefore(previewImg, container.firstChild);
                    }

                    // Set image source
                    previewImg.src = e.target.result;

                    // Hide placeholder and show remove button
                    if (placeholder) placeholder.style.display = 'none';
                    if (removeBtn) removeBtn.style.display = 'inline-block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove Image Function
        function removeImage(type) {
            const container = document.getElementById(`${type}-preview-container`);
            const previewImg = document.getElementById(`${type}-preview`);
            const placeholder = document.getElementById(`${type}-placeholder`);
            const removeBtn = container.querySelector('.btn-remove-image');
            const inputField = document.getElementById(type.replace('-', '_'));

            // Remove preview image
            if (previewImg) {
                previewImg.remove();
            }

            // Show placeholder and hide remove button
            if (placeholder) placeholder.style.display = 'flex';
            if (removeBtn) removeBtn.style.display = 'none';

            // Clear file input
            if (inputField) inputField.value = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // AJAX Form Submission
            const branchOfLawForm = document.getElementById('branchOfLawForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

            branchOfLawForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                // Update loading text dynamically
                const loadingText = @json(isset($branchOfLaw) ? __('branches_of_laws.update_branch_of_law') : __('branches_of_laws.create_branch_of_law'));
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
                        const formData = new FormData(branchOfLawForm);

                        // Send AJAX request
                        return fetch(branchOfLawForm.action, {
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
                            const successMessage = @json(isset($branchOfLaw) ? __('branches_of_laws.updated_successfully') : __('branches_of_laws.created_successfully'));
                            LoadingOverlay.showSuccess(
                                successMessage,
                                'Redirecting...'
                            );

                            // Show success alert
                            showAlert('success', data.message || successMessage);

                            // Redirect after 1.5 seconds
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('admin.branches-of-laws.index') }}';
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
