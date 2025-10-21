@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                            class="uil uil-estate"></i>{{ trans('dashboard.title') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.admin-management.roles.index') }}">{{ trans('menu.admin managment.roles managment') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ __('View Role') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 bg-white radius-xl w-100 mb-30">
                    <div class="card-header py-20 px-25 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('Role Details') }}</h6>
                        <div class="button-group">
                            <a href="{{ route('admin.admin-management.roles.edit', $role->id) }}" 
                               class="btn btn-primary btn-sm btn-squared text-capitalize">
                                <i class="uil uil-edit"></i> {{ __('Edit Role') }}
                            </a>
                            <a href="{{ route('admin.admin-management.roles.index') }}" 
                               class="btn btn-light btn-sm btn-squared text-capitalize">
                                <i class="uil uil-angle-left"></i> {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-25">
                        <!-- Role Information -->
                        <div class="row mb-30">
                            <div class="col-12">
                                <h6 class="mb-20 fw-500 color-dark border-bottom pb-15">
                                    <i class="uil uil-info-circle me-2"></i>{{ __('Role Information') }}
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Role ID') }}</label>
                                    <p class="fs-15 fw-400 color-dark">#{{ $role->id }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Created At') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $role->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Updated At') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $role->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Translations -->
                        <div class="row mb-30">
                            <div class="col-12">
                                <h6 class="mb-20 fw-500 color-dark border-bottom pb-15">
                                    <i class="uil uil-globe me-2"></i>{{ __('Translations') }}
                                </h6>
                            </div>
                            
                            @php
                                $languages = \App\Models\Language::all();
                            @endphp

                            @foreach($languages as $language)
                                <div class="col-md-6 mb-20">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-20">
                                            <div class="d-flex align-items-center mb-10">
                                                <span class="badge badge-light-info me-10">{{ strtoupper($language->code) }}</span>
                                                <strong class="fs-14">{{ $language->name }}</strong>
                                            </div>
                                            <p class="fs-15 fw-400 color-dark mb-0" {{ $language->rtl ? 'dir=rtl' : '' }}>
                                                {{ $role->getTranslation('name', $language->code) ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Permissions -->
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-20 fw-500 color-dark border-bottom pb-15">
                                    <i class="uil uil-shield-check me-2"></i>{{ __('Assigned Permissions') }}
                                    <span class="badge badge-light-primary ms-2">{{ $role->permessions->count() }}</span>
                                </h6>
                            </div>

                            @if($role->permessions->count() > 0)
                                @php
                                    $groupedPermissions = $role->permessions->groupBy(function($permission) {
                                        return $permission->getTranslation('group_by', app()->getLocale()) ?? 'Other';
                                    });
                                @endphp

                                @foreach($groupedPermissions as $groupName => $permissions)
                                    <div class="col-12 mb-20">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-normal py-15 px-20 border-bottom">
                                                <h6 class="mb-0 fw-500">
                                                    {{ $groupName }} 
                                                    <span class="badge badge-light-info badge-sm ms-2">{{ $permissions->count() }}</span>
                                                </h6>
                                            </div>
                                            <div class="card-body p-20">
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        <div class="col-md-4 col-lg-3 mb-15">
                                                            <div class="d-flex align-items-center">
                                                                <i class="uil uil-check-circle text-success me-2"></i>
                                                                <span class="fs-14">{{ $permission->getTranslation('name', app()->getLocale()) ?? $permission->key }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="uil uil-exclamation-triangle me-2 fs-20"></i>
                                        <div>{{ __('No permissions assigned to this role') }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="row mt-30">
                            <div class="col-12">
                                <div class="button-group d-flex justify-content-between">
                                    <button type="button" 
                                            class="btn btn-danger btn-default btn-squared text-capitalize"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-delete-role"
                                            data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->getTranslation('name', app()->getLocale()) ?? $role->name }}">
                                        <i class="uil uil-trash-alt"></i> {{ __('roles.delete_role') }}
                                    </button>
                                    <a href="{{ route('admin.admin-management.roles.edit', $role->id) }}" 
                                       class="btn btn-primary btn-default btn-squared text-capitalize">
                                        <i class="uil uil-edit"></i> {{ __('roles.edit_role') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-info-delete modal fade" id="modal-delete-role" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-info" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-info-body d-flex">
                        <div class="modal-info-icon warning">
                            <img src="{{ asset('assets/img/svg/alert-circle.svg') }}" alt="alert-circle" class="svg">
                        </div>
                        <div class="modal-info-text">
                            <h6>{{ __('roles.confirm_delete') }}</h6>
                            <p id="delete-role-name" class="fw-500"></p>
                            <p class="text-muted fs-13">{{ __('roles.delete_warning') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-outlined btn-sm" data-bs-dismiss="modal">
                        <i class="uil uil-times"></i> {{ __('roles.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteBtn">
                        <i class="uil uil-trash-alt"></i> {{ __('roles.delete_role') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('modal-delete-role');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteRoleNameElement = document.getElementById('delete-role-name');
            let currentRoleId = null;

            // When modal is triggered, store role data
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                currentRoleId = button.getAttribute('data-role-id');
                const roleName = button.getAttribute('data-role-name');
                
                // Update modal content
                deleteRoleNameElement.textContent = roleName;
            });

            // Handle delete confirmation
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentRoleId) {
                    // Create form dynamically
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('admin.admin-management.roles.index') }}/${currentRoleId}`;
                    
                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    
                    // Add DELETE method
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    // Append form to body and submit
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
    @endpush
@endsection
