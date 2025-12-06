@extends('layout.app')

@section('styles')
    <style>
        /* Logo Upload Styles */
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
                    ['title' => trans('customer.customers_management'), 'url' => route('admin.customers.index')],
                    ['title' => isset($customer) ? trans('customer.edit_customer') : trans('customer.create_customer')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($customer) ? trans('customer.edit_customer') : trans('customer.create_customer') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Alert Container --}}
                        <div id="alertContainer"></div>

                        <form id="customerForm"
                            action="{{ isset($customer) ? route('admin.customers.update', $customer->id) : route('admin.customers.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($customer))
                                @method('PUT')
                            @endif

                            <div class="row">
                                {{-- Name Fields Section --}}
                                <div class="col-12 mb-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-user me-2"></i>{{ trans('customer.customer_information') }}
                                    </h6>
                                </div>

                                {{-- Name English --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.name') }} (EN) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_en') is-invalid @enderror"
                                            id="name_en"
                                            name="name_en"
                                            value="{{ isset($customer) ? $customer->getTranslation('name', 'en') : old('name_en') }}"
                                            placeholder="{{ trans('customer.enter_name') }} (EN)">
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Name Arabic --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_ar" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.name') }} (AR) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_ar') is-invalid @enderror"
                                            id="name_ar"
                                            name="name_ar"
                                            value="{{ isset($customer) ? $customer->getTranslation('name', 'ar') : old('name_ar') }}"
                                            placeholder="{{ trans('customer.enter_name') }} (AR)"
                                            dir="rtl">
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Contact Information Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-envelope me-2"></i>{{ trans('customer.contact_information') }}
                                    </h6>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="email" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.email') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="email"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('email') is-invalid @enderror"
                                            id="email" name="email"
                                            value="{{ isset($customer) ? $customer->user?->email : old('email') }}"
                                            placeholder="{{ trans('customer.enter_email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password (only for new customers) --}}
                                @if(!isset($customer))
                                    <div class="col-md-6 mb-25">
                                        <div class="form-group">
                                            <label for="password" class="il-gray fs-14 fw-500 mb-10">
                                                {{ __('common.password') }} <span class="text-danger">*</span>
                                            </label>
                                            <input type="password"
                                                class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('password') is-invalid @enderror"
                                                id="password" name="password"
                                                placeholder="{{ __('common.enter_password') }}">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                {{-- Phone Country --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="phone_country_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.phone_country') }} <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-control select2 ih-medium ip-gray radius-xs b-light px-15 @error('phone_country_id') is-invalid @enderror"
                                            id="phone_country_id" name="phone_country_id">
                                            <option value="">{{ trans('customer.select_country') }}</option>
                                            @php
                                                $selectedPhoneCountryId = isset($customer) ? $customer->phone_country_id : old('phone_country_id');
                                            @endphp
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    data-phone-code="{{ $country->phone_code }}"
                                                    {{ $selectedPhoneCountryId == $country->id ? 'selected' : '' }}>
                                                    {{ $country->getTranslation('name', app()->getLocale()) }} ({{ $country->phone_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('phone_country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="phone" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.phone') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('phone') is-invalid @enderror"
                                            id="phone" name="phone"
                                            value="{{ isset($customer) ? $customer->phone : old('phone') }}"
                                            placeholder="{{ trans('customer.enter_phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Location Information Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-map-marker me-2"></i>{{ trans('customer.location_information') }}
                                    </h6>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-12 mb-25">
                                    <div class="form-group">
                                        <label for="address" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.address') }}
                                        </label>
                                        <textarea
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('address') is-invalid @enderror"
                                            id="address" name="address" rows="3"
                                            placeholder="{{ trans('customer.enter_address') }}">{{ isset($customer) ? $customer->address : old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- City --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="city_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.city') }}
                                        </label>
                                        <select
                                            class="form-control select2 ih-medium ip-gray radius-xs b-light px-15 @error('city_id') is-invalid @enderror"
                                            id="city_id" name="city_id">
                                            <option value="">{{ trans('customer.select_city') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ (isset($customer) && $customer->city_id == $city->id) || old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Region --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="region_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.region') }}
                                        </label>
                                        <select
                                            class="form-control select2 ih-medium ip-gray radius-xs b-light px-15 @error('region_id') is-invalid @enderror"
                                            id="region_id" name="region_id">
                                            <option value="">{{ trans('customer.select_region') }}</option>
                                            @if(isset($customer) && $customer->region_id && $customer->region)
                                                <option value="{{ $customer->region_id }}" selected>
                                                    {{ $customer->region->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('region_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Logo Upload Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-image me-2"></i>{{ trans('customer.logo') }}
                                    </h6>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('customer.logo') }}
                                        </label>
                                        <div class="logo-upload-wrapper">
                                            <div class="logo-preview-container" id="logo-preview-container">
                                                @php
                                                    $logoPath = isset($customer) && $customer->attachments->where('type', 'logo')->first()
                                                        ? asset('storage/' . $customer->attachments->where('type', 'logo')->first()->path)
                                                        : null;
                                                @endphp
                                                @if ($logoPath)
                                                    <img src="{{ $logoPath }}" alt="Logo" id="logo-preview" class="uploaded-image">
                                                @else
                                                    <div class="logo-placeholder" id="logo-placeholder">
                                                        <i class="uil uil-image-upload"></i>
                                                        <p>{{ trans('customer.click_upload_logo') }}</p>
                                                        <small>{{ trans('customer.recommended_size') }}</small>
                                                    </div>
                                                @endif
                                                <div class="logo-overlay">
                                                    <button type="button" class="btn-change-image"
                                                        onclick="document.getElementById('logo').click()">
                                                        <i class="uil uil-camera"></i> {{ trans('customer.change') }}
                                                    </button>
                                                    <button type="button" class="btn-remove-image"
                                                        onclick="removeImage('logo')"
                                                        style="{{ $logoPath ? '' : 'display: none;' }}">
                                                        <i class="uil uil-trash-alt"></i> {{ trans('customer.remove') }}
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="logo" name="logo"
                                                accept="image/*" onchange="previewImage(this, 'logo')">
                                        </div>
                                        @error('logo')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Active Status --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="active" class="il-gray fs-14 fw-500 mb-10 d-block">
                                            {{ trans('customer.status') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="hidden" name="active" value="0">
                                            <div class="form-check form-switch form-switch-primary form-switch-md">
                                                <input type="checkbox"
                                                       class="form-check-input @error('active') is-invalid @enderror"
                                                       id="active"
                                                       name="active"
                                                       value="1"
                                                       {{ (isset($customer) && $customer->active == 1) || old('active') == 1 || !isset($customer) ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('active')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="button-group d-flex pt-25 justify-content-end">
                                        <a href="{{ route('admin.customers.index') }}"
                                           class="btn btn-light btn-default btn-squared me-15 text-capitalize">
                                            {{ trans('customer.cancel') }}
                                        </a>
                                        <button type="submit" id="submitBtn"
                                                class="btn btn-primary btn-default btn-squared text-capitalize"
                                                style="display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="uil uil-check"></i>
                                            <span class="ms-2">{{ trans('customer.save') }}</span>
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
        function previewImage(input, type) {
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
            const inputField = document.getElementById(type);

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
            // Initialize Select2
            $('.select2').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });

            // City/Region Dependency
            const citySelect = $('#city_id');
            const regionSelect = $('#region_id');
            const existingRegionId = {{ isset($customer) && $customer->region_id ? $customer->region_id : 'null' }};

            citySelect.on('change', function() {
                const cityId = $(this).val();
                regionSelect.empty().append('<option value="">{{ trans("customer.select_region") }}</option>');

                if (cityId) {
                    fetch(`/api/v1/area/regions/by-city/${cityId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.data) {
                                data.data.forEach(region => {
                                    const option = new Option(region.name, region.id, false, region.id == existingRegionId);
                                    regionSelect.append(option);
                                });
                                regionSelect.trigger('change');
                            }
                        })
                        .catch(error => console.error('Error loading regions:', error));
                }
            });

            // Trigger city change if editing
            @if(isset($customer) && $customer->city_id)
                citySelect.trigger('change');
            @endif

            // AJAX Form Submission
            const customerForm = document.getElementById('customerForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

            customerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                // Update loading text dynamically
                const loadingText = @json(isset($customer) ? trans('customer.updated_successfully') : trans('customer.created_successfully'));
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
                document.querySelectorAll('.invalid-feedback, .text-danger.mt-2').forEach(el => el.remove());

                // Start progress bar animation
                LoadingOverlay.animateProgressBar(30, 300).then(() => {
                        // Prepare form data
                        const formData = new FormData(customerForm);

                        // Send AJAX request
                        return fetch(customerForm.action, {
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
                            // Show success animation
                            const successMessage = @json(isset($customer) ? trans('customer.updated_successfully') : trans('customer.created_successfully'));
                            LoadingOverlay.showSuccess(
                                successMessage,
                                'Redirecting...'
                            );

                            // Show success alert
                            showAlert('success', data.message || successMessage);

                            // Redirect after 1.5 seconds
                            setTimeout(() => {
                                window.location.href = data.redirect || '{{ route('admin.customers.index') }}';
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

                                // Try with escaped brackets
                                if (!input) {
                                    input = document.querySelector(
                                        `[name="${key.replace(/\[/g, '\\[').replace(/\]/g, '\\]')}"]`
                                    );
                                }

                                if (input) {
                                    input.classList.add('is-invalid');

                                    // Remove existing feedback if any
                                    const existingFeedback = input.parentNode.querySelector(
                                        '.invalid-feedback, .text-danger.mt-2');
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
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    </script>
@endpush
