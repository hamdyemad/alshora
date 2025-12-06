@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('news.news_management'), 'url' => route('admin.news.index')],
                    ['title' => isset($news) ? trans('news.edit_news') : trans('news.create_news')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($news) ? trans('news.edit_news') : trans('news.create_news') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Alert Container --}}
                        <div id="alertContainer"></div>

                        <form action="{{ isset($news) ? route('admin.news.update', $news->id) : route('admin.news.store') }}" 
                              method="POST" 
                              id="newsForm">
                            @csrf
                            @if(isset($news))
                                @method('PUT')
                            @endif

                            {{-- Basic Information Section --}}
                            <div class="row">
                                <div class="col-12 mb-25">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-newspaper me-2"></i>{{ trans('news.basic_information') }}
                                    </h6>
                                </div>

                                {{-- Title English --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="title_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.title_en') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('title_en') is-invalid @enderror"
                                            id="title_en" name="title_en"
                                            value="{{ isset($news) ? $news->getTranslation('title', 'en') : old('title_en') }}"
                                            placeholder="{{ trans('news.enter_title_en') }}">
                                        @error('title_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Title Arabic --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="title_ar" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.title_ar') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('title_ar') is-invalid @enderror"
                                            id="title_ar" name="title_ar"
                                            value="{{ isset($news) ? $news->getTranslation('title', 'ar') : old('title_ar') }}"
                                            placeholder="{{ trans('news.enter_title_ar') }}" dir="rtl">
                                        @error('title_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Details English --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="details_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.details_en') }} <span class="text-danger">*</span>
                                        </label>
                                        <textarea
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('details_en') is-invalid @enderror"
                                            id="details_en" name="details_en" rows="5"
                                            placeholder="{{ trans('news.enter_details_en') }}">{{ isset($news) ? $news->getTranslation('details', 'en') : old('details_en') }}</textarea>
                                        @error('details_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Details Arabic --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="details_ar" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.details_ar') }} <span class="text-danger">*</span>
                                        </label>
                                        <textarea
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('details_ar') is-invalid @enderror"
                                            id="details_ar" name="details_ar" rows="5"
                                            placeholder="{{ trans('news.enter_details_ar') }}" dir="rtl">{{ isset($news) ? $news->getTranslation('details', 'ar') : old('details_ar') }}</textarea>
                                        @error('details_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Date --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="date" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.date') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="date"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('date') is-invalid @enderror"
                                            id="date" name="date"
                                            value="{{ isset($news) ? $news->date->format('Y-m-d') : old('date', date('Y-m-d')) }}">
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="active" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.status') }} <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('active') is-invalid @enderror"
                                            id="active" name="active">
                                            <option value="1"
                                                {{ (isset($news) && $news->active == 1) || old('active') == 1 ? 'selected' : '' }}>
                                                {{ trans('news.active') }}</option>
                                            <option value="0"
                                                {{ (isset($news) && $news->active == 0) || old('active') == 0 ? 'selected' : '' }}>
                                                {{ trans('news.inactive') }}</option>
                                        </select>
                                        @error('active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Source Information Section --}}
                            <div class="row">
                                <div class="col-12 mb-25 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-link me-2"></i>{{ trans('news.source_information') }}
                                    </h6>
                                </div>

                                {{-- Source English --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="source_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.source_en') }}
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('source_en') is-invalid @enderror"
                                            id="source_en" name="source_en"
                                            value="{{ isset($news) ? $news->getTranslation('source', 'en') : old('source_en') }}"
                                            placeholder="{{ trans('news.enter_source_en') }}">
                                        @error('source_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Source Arabic --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="source_ar" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.source_ar') }}
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('source_ar') is-invalid @enderror"
                                            id="source_ar" name="source_ar"
                                            value="{{ isset($news) ? $news->getTranslation('source', 'ar') : old('source_ar') }}"
                                            placeholder="{{ trans('news.enter_source_ar') }}" dir="rtl">
                                        @error('source_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Source Link --}}
                                <div class="col-md-12 mb-25">
                                    <div class="form-group">
                                        <label for="source_link" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('news.source_link') }}
                                        </label>
                                        <input type="url"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('source_link') is-invalid @enderror"
                                            id="source_link" name="source_link"
                                            value="{{ isset($news) ? $news->source_link : old('source_link') }}"
                                            placeholder="{{ trans('news.enter_source_link') }}">
                                        @error('source_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="button-group d-flex pt-25 justify-content-end">
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-default btn-squared me-15 text-capitalize">
                                            {{ trans('news.cancel') }}
                                        </a>
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-default btn-squared text-capitalize" style="display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="uil uil-check"></i>
                                            <span class="ms-2">{{ trans('news.save') }}</span>
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
            const newsForm = document.getElementById('newsForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

            newsForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                // Update loading text dynamically
                const loadingText = @json(isset($news) ? trans('news.update_news') : trans('news.create_news'));
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
                        const formData = new FormData(newsForm);

                        // Send AJAX request
                        return fetch(newsForm.action, {
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
                            const successMessage = @json(isset($news) ? trans('news.updated_successfully') : trans('news.created_successfully'));
                            LoadingOverlay.showSuccess(
                                successMessage,
                                'Redirecting...'
                            );

                            // Show success alert
                            showAlert('success', data.message || successMessage);

                            // Redirect after 1.5 seconds
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('admin.news.index') }}';
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
