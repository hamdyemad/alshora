@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    [
                        'title' => trans('dashboard.title'),
                        'url' => route('admin.dashboard'),
                        'icon' => 'uil uil-estate'
                    ],
                    [
                        'title' => trans('menu.admin managment.roles managment'),
                        'url' => route('admin.admin-management.roles.index')
                    ],
                    [
                        'title' => isset($role) ? __('roles.edit_role') : __('roles.create_role')
                    ]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 bg-white radius-xl w-100 mb-30">
                    <div class="card-header py-20 px-25 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ isset($role) ? __('roles.edit_role') : __('roles.create_role') }}</h6>
                        <span class="badge badge-primary badge-lg" style="border-radius: 6px; padding: 6px 12px;">
                            <i class="uil uil-shield-check me-1"></i>
                            {{ $groupedPermissions->sum(function($group) { return $group->count(); }) }} {{ trans('roles.permissions') }} {{ __('common.available') }}
                        </span>
                    </div>
                    <div class="card-body p-25">
                        <form id="roleForm" 
                              action="{{ isset($role) ? route('admin.admin-management.roles.update', $role->id) : route('admin.admin-management.roles.store') }}" 
                              method="POST">
                            @csrf
                            @if(isset($role))
                                @method('PUT')
                            @endif
                            
                            <!-- Alert Container -->
                            <div id="alertContainer"></div>

                            <div class="row">
                                @foreach($languages as $language)
                                    <div class="col-md-6 mb-25">
                                        <div class="form-group">
                                            <label for="name_{{ $language->code }}" class="il-gray fs-14 fw-500 align-center mb-10" @if($language->code == 'ar') dir="rtl" @endif>
                                                @if($language->code == 'ar')
                                                    اسم الدور ({{ $language->name }}) <span class="text-danger">*</span>
                                                @else
                                                    {{ __('roles.name') }} ({{ $language->name }}) <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="text" 
                                                   class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_' . $language->code) is-invalid @enderror" 
                                                   id="name_{{ $language->code }}" 
                                                   name="name_{{ $language->code }}"  
                                                   value="{{ isset($role) ? ($role->getTranslation('name', $language->code) ?? '') : old('name_' . $language->code) }}"
                                                   placeholder="@if($language->code == 'ar')أدخل اسم الدور بالعربية@else{{ __('roles.enter_role_name_in') }} {{ $language->name }}@endif"
                                                   @if($language->code == 'ar') dir="rtl" @endif
                                                   required>
                                            @error('name_' . $language->code)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Permissions Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="permissions-section">
                                        <div class="permissions-header">
                                            <h5 class="mb-0 text-white">
                                                <i class="uil uil-key-skeleton me-2"></i>
                                                Assign Permissions
                                            </h5>
                                        </div>
                                        
                                        <div class="permissions-body">
                                            <!-- Select/Deselect All Buttons -->
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h6 class="mb-0 fw-600">Select Permissions</h6>
                                                <div class="d-flex gap-2">
                                                    <button type="button" id="select_all_btn" class="btn btn-primary btn-sm">
                                                        <i class="uil uil-check-square me-1"></i> Select All
                                                    </button>
                                                    <button type="button" id="deselect_all_btn" class="btn btn-danger btn-sm">
                                                        <i class="uil uil-times-square me-1"></i> Deselect All
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Permissions Grid -->
                                            <div class="row g-3">
                                                @foreach($groupedPermissions as $groupName => $permissions)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="permission-group-box">
                                                            <!-- Group Header -->
                                                            <div class="permission-group-header">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="uil uil-setting me-2"></i>
                                                                    <span class="group-title">{{ $groupName }}</span>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input group-checkbox" 
                                                                           type="checkbox" 
                                                                           id="group_{{ Str::slug($groupName) }}"
                                                                           data-group="{{ Str::slug($groupName) }}">
                                                                    <label class="form-check-label fw-600" for="group_{{ Str::slug($groupName) }}">
                                                                        All
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Permissions List -->
                                                            <div class="permission-group-list">
                                                                @foreach($permissions as $item)
                                                                    @php
                                                                        $permission = $item['permission'];
                                                                        $action = $item['action'];
                                                                        $badgeColor = app(\App\Services\RoleService::class)->getActionBadgeColor($action);
                                                                        
                                                                        $actionDisplayMap = [
                                                                            'view' => 'Read',
                                                                            'create' => 'Create',
                                                                            'edit' => 'Edit',
                                                                            'delete' => 'Delete',
                                                                            'manage' => 'Manage',
                                                                            'approve' => 'Approve',
                                                                            'accept' => 'Accept',
                                                                            'reject' => 'Reject',
                                                                            'send' => 'Send',
                                                                        ];
                                                                        $displayName = $actionDisplayMap[$action] ?? ucfirst($action);
                                                                    @endphp
                                                                    <div class="permission-list-item">
                                                                        <input class="form-check-input permission-checkbox" 
                                                                               type="checkbox" 
                                                                               name="permissions[]" 
                                                                               value="{{ $permission->id }}" 
                                                                               id="permission_{{ $permission->id }}"
                                                                               data-group="{{ Str::slug($groupName) }}"
                                                                               {{ isset($role) && $role->permessions->contains($permission->id) ? 'checked' : '' }}>
                                                                        <label class="permission-list-label" for="permission_{{ $permission->id }}">
                                                                            <span class="badge badge-round badge-lg badge-{{ $badgeColor }}">
                                                                                {{ $displayName }}
                                                                            </span>
                                                                            <span class="permission-name">{{ $groupName }}</span>
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
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="row mt-4">
                                <div class="col-12">    
                                    <div class="button-group d-flex pt-25 justify-content-end" style="gap: 10px;">
                                        <a href="{{ route('admin.admin-management.roles.index') }}" 
                                           class="btn btn-light btn-default btn-squared fw-400 text-capitalize">
                                            <i class="uil uil-angle-left"></i> {{ __('common.cancel') }}
                                        </a>
                                        <button type="submit" id="submitBtn" 
                                                class="btn btn-primary btn-default btn-squared text-capitalize">
                                            <i class="uil uil-check"></i> 
                                            <span>{{ isset($role) ? __('roles.update_role') : __('roles.create_role') }}</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleForm = document.getElementById('roleForm');
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

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

            roleForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitBtn.disabled = true;
                const btnIcon = submitBtn.querySelector('i');
                const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                if (btnIcon) btnIcon.classList.add('d-none');
                if (btnText) btnText.classList.add('d-none');
                submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                const loadingText = @json(isset($role) ? trans('loading.updating') : trans('loading.creating'));
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    const loadingTextEl = overlay.querySelector('.loading-text');
                    const loadingSubtextEl = overlay.querySelector('.loading-subtext');
                    if (loadingTextEl) loadingTextEl.textContent = loadingText;
                    if (loadingSubtextEl) loadingSubtextEl.textContent = '{{ trans("loading.please_wait") }}';
                }
                
                if (window.LoadingOverlay) LoadingOverlay.show();

                alertContainer.innerHTML = '';
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                LoadingOverlay.animateProgressBar(30, 300).then(() => {
                    const formData = new FormData(roleForm);
                    return fetch(roleForm.action, {
                        method: roleForm.method,
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
                .then(data => LoadingOverlay.animateProgressBar(90, 200).then(() => data))
                .then(data => {
                    return LoadingOverlay.animateProgressBar(100, 200).then(() => {
                        const successMessage = @json(isset($role) ? trans('loading.updated_successfully') : trans('loading.created_successfully'));
                        LoadingOverlay.showSuccess(successMessage, '{{ trans("loading.redirecting") }}');
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route("admin.admin-management.roles.index") }}';
                        }, 1500);
                    });
                })
                .catch(error => {
                    LoadingOverlay.hide();
                    if (error.errors) {
                        Object.keys(error.errors).forEach(key => {
                            const input = document.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                feedback.textContent = error.errors[key][0];
                                input.parentNode.appendChild(feedback);
                            }
                        });
                        showAlert('danger', error.message || '{{ __("Please check the form for errors") }}');
                    } else {
                        showAlert('danger', error.message || '{{ __("An error occurred") }}');
                    }
                    submitBtn.disabled = false;
                    if (btnIcon) btnIcon.classList.remove('d-none');
                    if (btnText) btnText.classList.remove('d-none');
                    submitBtn.querySelector('.spinner-border').classList.add('d-none');
                });
            });

            // Permission Checkboxes Logic
            const allPermissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            const allGroupCheckboxes = document.querySelectorAll('.group-checkbox');
            const selectAllBtn = document.getElementById('select_all_btn');
            const deselectAllBtn = document.getElementById('deselect_all_btn');

            // Select All Button
            selectAllBtn.addEventListener('click', function() {
                allPermissionCheckboxes.forEach(checkbox => checkbox.checked = true);
                allGroupCheckboxes.forEach(checkbox => checkbox.checked = true);
            });

            // Deselect All Button
            deselectAllBtn.addEventListener('click', function() {
                allPermissionCheckboxes.forEach(checkbox => checkbox.checked = false);
                allGroupCheckboxes.forEach(checkbox => checkbox.checked = false);
            });

            // Group Checkboxes
            allGroupCheckboxes.forEach(groupCheckbox => {
                groupCheckbox.addEventListener('change', function() {
                    const groupName = this.dataset.group;
                    const isChecked = this.checked;
                    const groupPermissions = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`);
                    groupPermissions.forEach(checkbox => checkbox.checked = isChecked);
                });
            });

            // Individual Permission Checkboxes
            allPermissionCheckboxes.forEach(permissionCheckbox => {
                permissionCheckbox.addEventListener('change', function() {
                    const groupName = this.dataset.group;
                    updateGroupCheckboxState(groupName);
                });
            });

            function updateGroupCheckboxState(groupName) {
                const groupCheckbox = document.querySelector(`.group-checkbox[data-group="${groupName}"]`);
                const groupPermissions = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`);
                const checkedPermissions = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]:checked`);

                if (checkedPermissions.length === 0) {
                    groupCheckbox.checked = false;
                    groupCheckbox.indeterminate = false;
                } else if (checkedPermissions.length === groupPermissions.length) {
                    groupCheckbox.checked = true;
                    groupCheckbox.indeterminate = false;
                } else {
                    groupCheckbox.checked = false;
                    groupCheckbox.indeterminate = true;
                }
            }

            // Initialize states
            allGroupCheckboxes.forEach(groupCheckbox => {
                const groupName = groupCheckbox.dataset.group;
                updateGroupCheckboxState(groupName);
            });
        });
    </script>
    @endpush
@endsection

@push('after-body')
    <x-loading-overlay 
        :loadingText="trans('loading.processing')" 
        :loadingSubtext="trans('loading.please_wait')" 
    />
@endpush
