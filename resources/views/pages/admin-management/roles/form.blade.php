@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('menu.admin managment.roles managment'), 'url' => route('admin.admin-management.roles.index')],
                    ['title' => isset($role) ? __('roles.edit_role') : __('roles.create_role')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($role) ? __('roles.edit_role') : __('roles.create_role') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Alert Container --}}
                        <div id="alertContainer"></div>

                        <form action="{{ isset($role) ? route('admin.admin-management.roles.update', $role->id) : route('admin.admin-management.roles.store') }}" 
                              method="POST" 
                              id="roleForm">
                            @csrf
                            @if(isset($role))
                                @method('PUT')
                            @endif

                            {{-- Basic Information Section --}}
                            <div class="row">
                                <div class="col-12 mb-25">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-shield me-2"></i>{{ __('roles.basic_information') }}
                                    </h6>
                                </div>

                                @foreach($languages as $language)
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_{{ $language->code }}" class="il-gray fs-14 fw-500 mb-10">
                                            {{ $language->code == 'ar' ? '🇸🇦' : '🇺🇸' }}
                                            {{ $language->code == 'ar' ? 'اسم الدور بالعربية' : 'Role Name in English' }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_'.$language->code) is-invalid @enderror"
                                            id="name_{{ $language->code }}" 
                                            name="name_{{ $language->code }}"
                                            value="{{ isset($role) ? $role->getTranslation('name', $language->code) : old('name_'.$language->code) }}"
                                            placeholder="{{ $language->code == 'ar' ? 'مثال: مدير المحتوى' : 'Example: Content Manager' }}"
                                            @if($language->code == 'ar') dir="rtl" @endif>
                                        @error('name_'.$language->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Permissions Section --}}
                            <div class="row">
                                <div class="col-12 mb-25 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                        <i class="uil uil-lock me-2"></i>{{ __('roles.permissions') }}
                                        <span class="badge bg-primary ms-2" id="selectedCount">0</span>
                                        <span class="text-muted fs-13">/ {{ $groupedPermissions->sum(fn($g) => $g->count()) }}</span>
                                    </h6>
                                </div>

                                {{-- Permissions Toolbar --}}
                                <div class="col-12 mb-20">
                                    <div class="d-flex gap-10 flex-wrap">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="selectAllBtn">
                                            <i class="uil uil-check-square me-1"></i>{{ __('roles.select_all_permissions') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" id="deselectAllBtn">
                                            <i class="uil uil-times-square me-1"></i>{{ __('roles.deselect_all_permissions') }}
                                        </button>
                                    </div>
                                </div>

                                {{-- Permissions Grid --}}
                                <div class="col-12">
                                    <div class="row">
                                        @foreach($groupedPermissions as $groupName => $permissions)
                                        @php
                                            $groupKey = Str::slug($groupName);
                                            $groupTransKey = str_replace(' ', '-', strtolower($groupName));
                                            $translatedGroupName = __('roles.groups.'.$groupTransKey) !== 'roles.groups.'.$groupTransKey 
                                                ? __('roles.groups.'.$groupTransKey) : $groupName;
                                            
                                            $moduleIcons = [
                                                'dashboard' => 'uil-dashboard', 'roles' => 'uil-users-alt', 'admins' => 'uil-user-check',
                                                'countries' => 'uil-globe', 'cities' => 'uil-building', 'regions' => 'uil-map-marker',
                                                'lawyers' => 'uil-balance-scale', 'customers' => 'uil-user-circle', 'subscriptions' => 'uil-receipt',
                                                'news' => 'uil-newspaper', 'agendas' => 'uil-calendar-alt', 'laws' => 'uil-book-open',
                                                'reviews' => 'uil-star', 'notifications' => 'uil-bell', 'hosting' => 'uil-server',
                                                'support-messages' => 'uil-comment-alt-message', 'store-categories' => 'uil-store',
                                                'store-products' => 'uil-box', 'store-orders' => 'uil-shopping-cart',
                                                'subregions' => 'uil-location-point', 'preparer-agendas' => 'uil-file-alt',
                                                'sections-of-laws' => 'uil-books', 'instructions' => 'uil-info-circle',
                                                'branches-of-laws' => 'uil-sitemap', 'drafting-contracts' => 'uil-file-contract-dollar',
                                                'drafting-lawsuits' => 'uil-file-edit-alt', 'measures' => 'uil-ruler',
                                                'reservations' => 'uil-calender', 'hosting-reservations' => 'uil-calendar-alt',
                                                'client-agendas' => 'uil-schedule',
                                            ];
                                            $icon = $moduleIcons[$groupTransKey] ?? 'uil-folder';
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-25">
                                            <div class="card border h-100">
                                                <div class="card-header bg-primary text-white py-15 d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="uil {{ $icon }} me-2"></i>{{ $translatedGroupName }}
                                                    </span>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-light btn-xs px-10" onclick="toggleModule('{{ $groupKey }}', true)">
                                                            {{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}
                                                        </button>
                                                        <button type="button" class="btn btn-light btn-xs px-10" onclick="toggleModule('{{ $groupKey }}', false)">
                                                            {{ app()->getLocale() == 'ar' ? 'لا شيء' : 'None' }}
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body p-15">
                                                    @foreach($permissions as $item)
                                                    @php
                                                        $permission = $item['permission'];
                                                        $action = $item['action'];
                                                        $isChecked = isset($role) && $role->permessions->contains($permission->id);
                                                        $label = __('roles.actions.'.$action) !== 'roles.actions.'.$action 
                                                            ? __('roles.actions.'.$action) : ucfirst($action);
                                                    @endphp
                                                    <div class="form-check mb-10">
                                                        <input type="checkbox" 
                                                               class="form-check-input permission-checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}" 
                                                               id="perm_{{ $permission->id }}"
                                                               data-module="{{ $groupKey }}"
                                                               {{ $isChecked ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                            {{ $label }}
                                                            <small class="text-muted d-block">{{ $permission->key }}</small>
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Form Actions --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="button-group d-flex pt-25 justify-content-end">
                                        <a href="{{ route('admin.admin-management.roles.index') }}" class="btn btn-light btn-default btn-squared me-15 text-capitalize">
                                            {{ __('common.cancel') }}
                                        </a>
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-default btn-squared text-capitalize" style="display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="uil uil-check"></i>
                                            <span class="ms-2">{{ isset($role) ? __('roles.update_role') : __('roles.create_role') }}</span>
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
            const roleForm = document.getElementById('roleForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const deselectAllBtn = document.getElementById('deselectAllBtn');
            const allCheckboxes = document.querySelectorAll('.permission-checkbox');
            const selectedCountEl = document.getElementById('selectedCount');

            // Update count function
            function updateCount() {
                const checked = document.querySelectorAll('.permission-checkbox:checked').length;
                selectedCountEl.textContent = checked;
            }

            // Select/Deselect all
            selectAllBtn.addEventListener('click', function() {
                allCheckboxes.forEach(cb => cb.checked = true);
                updateCount();
            });

            deselectAllBtn.addEventListener('click', function() {
                allCheckboxes.forEach(cb => cb.checked = false);
                updateCount();
            });

            // Update count on checkbox change
            allCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateCount);
            });

            // Initial count
            updateCount();

            // AJAX Form Submission
            roleForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Disable submit button and show loading
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                // Show loading overlay
                if (typeof LoadingOverlay !== 'undefined') {
                    LoadingOverlay.show();
                }

                // Clear previous alerts
                alertContainer.innerHTML = '';

                // Remove previous validation errors
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                // Prepare form data
                const formData = new FormData(roleForm);

                // Send AJAX request
                fetch(roleForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Show success
                    if (typeof LoadingOverlay !== 'undefined') {
                        LoadingOverlay.showSuccess(data.message || '{{ __("common.success") }}', '');
                    }
                    showAlert('success', data.message || '{{ __("common.success") }}');

                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("admin.admin-management.roles.index") }}';
                    }, 1500);
                })
                .catch(error => {
                    // Hide loading overlay
                    if (typeof LoadingOverlay !== 'undefined') {
                        LoadingOverlay.hide();
                    }

                    // Handle validation errors
                    if (error.errors) {
                        Object.keys(error.errors).forEach(key => {
                            let input = document.querySelector(`[name="${key}"]`);

                            if (input) {
                                input.classList.add('is-invalid');

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
                        showAlert('danger', error.message || '{{ __("common.error") }}');
                    } else {
                        showAlert('danger', error.message || '{{ __("common.error") }}');
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

        // Toggle module permissions
        function toggleModule(module, checked) {
            document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`).forEach(cb => cb.checked = checked);
            document.getElementById('selectedCount').textContent = document.querySelectorAll('.permission-checkbox:checked').length;
        }
    </script>
@endpush
