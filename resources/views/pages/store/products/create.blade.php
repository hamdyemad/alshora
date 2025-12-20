@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    [
                        'title' => trans('dashboard.title'),
                        'url' => route('admin.dashboard'),
                        'icon' => 'uil uil-estate',
                    ],
                    ['title' => trans('store.products'), 'url' => route('admin.store.products.index')],
                    ['title' => trans('store.add_product')],
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ trans('store.add_product') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="alertContainer"></div>

                        <form id="productForm"
                            action="{{ route('admin.store.products.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-12 mb-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-info-circle me-2"></i>{{ trans('store.basic_information') }}
                                    </h6>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="category_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('store.select_category') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id">
                                            <option value="">{{ trans('common.select') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="price" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('store.product_price') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" step="0.01"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('price') is-invalid @enderror"
                                            id="price" name="price"
                                            value="{{ old('price') }}"
                                            placeholder="{{ trans('store.enter_price') }}">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('store.product_name_en') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_en') is-invalid @enderror"
                                            id="name_en" name="name_en"
                                            value="{{ old('name_en') }}"
                                            placeholder="{{ trans('store.enter_product_name_en') }}">
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_ar" class="il-gray fs-14 fw-500 mb-10" dir="rtl"
                                            style="text-align: right; display: block;">
                                            {{ trans('store.product_name_ar') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_ar') is-invalid @enderror"
                                            id="name_ar" name="name_ar"
                                            value="{{ old('name_ar') }}"
                                            placeholder="{{ trans('store.enter_product_name_ar') }}" dir="rtl">
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="description_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('store.product_description_en') }}
                                        </label>
                                        <textarea
                                            class="form-control ip-gray radius-xs b-light px-15 @error('description_en') is-invalid @enderror"
                                            id="description_en" name="description_en" rows="4"
                                            placeholder="{{ trans('store.enter_description_en') }}">{{ old('description_en') }}</textarea>
                                        @error('description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="description_ar" class="il-gray fs-14 fw-500 mb-10" dir="rtl" style="text-align: right; display: block;">
                                            {{ trans('store.product_description_ar') }}
                                        </label>
                                        <textarea
                                            class="form-control ip-gray radius-xs b-light px-15 @error('description_ar') is-invalid @enderror"
                                            id="description_ar" name="description_ar" rows="4"
                                            placeholder="{{ trans('store.enter_description_ar') }}" dir="rtl">{{ old('description_ar') }}</textarea>
                                        @error('description_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Image Upload --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('store.product_image') }}
                                        </label>
                                        <div class="logo-upload-wrapper">
                                            <div class="logo-preview-container" id="image-preview-container">
                                                <div class="logo-placeholder" id="image-placeholder">
                                                    <i class="uil uil-image"></i>
                                                    <p>{{ trans('store.click_upload_image') }}</p>
                                                    <small>{{ trans('store.recommended_size') }}</small>
                                                </div>
                                                <div class="logo-overlay">
                                                    <button type="button" class="btn-change-image"
                                                        onclick="document.getElementById('image').click()">
                                                        <i class="uil uil-camera"></i> {{ trans('common.change') }}
                                                    </button>
                                                    <button type="button" class="btn-remove-image"
                                                        onclick="removeImage('image')"
                                                        style="display: none;">
                                                        <i class="uil uil-trash-alt"></i> {{ trans('common.remove') }}
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="file" class="d-none" id="image" name="image"
                                                accept="image/*" onchange="previewImage(this, 'image')">
                                        </div>
                                        @error('image')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-25 mt-20">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10 d-block">
                                            {{ trans('common.active') }}
                                        </label>
                                        <div class="dm-switch-wrap d-flex align-items-center">
                                            <div class="form-check form-switch form-switch-primary form-switch-md">
                                                <input type="hidden" name="active" value="0">
                                                <input type="checkbox" class="form-check-input" id="active"
                                                    name="active" value="1"
                                                    {{ old('active', 1) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('active')
                                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-15 mt-30">
                                <a href="{{ route('admin.store.products.index') }}"
                                    class="btn btn-light btn-default btn-squared fw-400 text-capitalize">
                                    <i class="uil uil-angle-left"></i> {{ trans('common.cancel') }}
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="btn btn-primary btn-default btn-squared text-capitalize"
                                    style="display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="uil uil-check"></i>
                                    <span>{{ trans('store.add_product') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
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
            function previewImage(input, type) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const container = document.getElementById(`${type}-preview-container`);
                        const placeholder = document.getElementById(`${type}-placeholder`);
                        let previewImg = document.getElementById(`${type}-preview`);

                        if (!previewImg) {
                            previewImg = document.createElement('img');
                            previewImg.id = `${type}-preview`;
                            previewImg.className = 'uploaded-image';
                            previewImg.alt = 'Preview';
                            const overlay = container.querySelector('.logo-overlay');
                            container.insertBefore(previewImg, overlay);
                        }

                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        if (placeholder) placeholder.style.display = 'none';

                        const removeBtn = container.querySelector('.btn-remove-image');
                        if (removeBtn) removeBtn.style.display = 'inline-block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            function removeImage(type) {
                const container = document.getElementById(`${type}-preview-container`);
                const previewImg = document.getElementById(`${type}-preview`);
                const placeholder = document.getElementById(`${type}-placeholder`);
                const removeBtn = container.querySelector('.btn-remove-image');
                const inputField = document.getElementById(type);

                if (previewImg) previewImg.remove();
                if (placeholder) placeholder.style.display = 'flex';
                if (removeBtn) removeBtn.style.display = 'none';
                if (inputField) inputField.value = '';
            }

            document.addEventListener('DOMContentLoaded', function() {
                const productForm = document.getElementById('productForm');
                const submitBtn = document.getElementById('submitBtn');
                const alertContainer = document.getElementById('alertContainer');

                productForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitBtn.disabled = true;
                    const btnIcon = submitBtn.querySelector('i');
                    const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                    if (btnIcon) btnIcon.classList.add('d-none');
                    if (btnText) btnText.classList.add('d-none');
                    submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                    LoadingOverlay.show();
                    alertContainer.innerHTML = '';
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                    LoadingOverlay.animateProgressBar(30, 300).then(() => {
                            const formData = new FormData(productForm);
                            return fetch(productForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            });
                        })
                        .then(response => {
                            LoadingOverlay.animateProgressBar(60, 200);
                            if (!response.ok) {
                                return response.json().then(data => { throw data; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            return LoadingOverlay.animateProgressBar(90, 200).then(() => data);
                        })
                        .then(data => {
                            return LoadingOverlay.animateProgressBar(100, 200).then(() => {
                                LoadingOverlay.showSuccess('{{ trans('store.product_created') }}', '{{ trans('common.redirecting') }}...');
                                showAlert('success', data.message || '{{ trans('store.product_created') }}');
                                setTimeout(() => {
                                    window.location.href = data.redirect || '{{ route('admin.store.products.index') }}';
                                }, 1500);
                            });
                        })
                        .catch(error => {
                            LoadingOverlay.hide();
                            if (error.errors) {
                                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                                Object.keys(error.errors).forEach(key => {
                                    let input = document.querySelector(`[name="${key}"]`);
                                    if (input) {
                                        input.classList.add('is-invalid');
                                        const feedback = document.createElement('div');
                                        feedback.className = 'invalid-feedback d-block';
                                        feedback.textContent = error.errors[key][0];
                                        input.parentNode.appendChild(feedback);
                                        if (Object.keys(error.errors)[0] === key) {
                                            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                        }
                                    }
                                });
                                showAlert('danger', error.message || '{{ trans('common.please_check_form_errors') }}');
                            } else {
                                showAlert('danger', error.message || '{{ trans('common.an_error_occurred') }}');
                            }
                            submitBtn.disabled = false;
                            const btnIcon = submitBtn.querySelector('i');
                            const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                            if (btnIcon) btnIcon.classList.remove('d-none');
                            if (btnText) btnText.classList.remove('d-none');
                            submitBtn.querySelector('.spinner-border').classList.add('d-none');
                        });
                });

                function showAlert(type, message) {
                    const alert = document.createElement('div');
                    alert.className = `alert alert-${type} alert-dismissible fade show mb-20`;
                    alert.innerHTML = `
                        <i class="uil uil-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    alertContainer.appendChild(alert);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        </script>
    @endpush
@endsection

@push('after-body')
    <x-loading-overlay loadingText="{{ trans('store.processing') }}" loadingSubtext="{{ trans('common.please_wait') }}" />
@endpush
