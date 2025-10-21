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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.admin managment.roles managment') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ __('Roles Management') }}</h4>
                        <a href="{{ route('admin.admin-management.roles.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                            <i class="uil uil-plus"></i> {{ __('Create New Role') }}
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 table-borderless">
                            <thead>
                                <tr class="userDatatable-header">
                                    <th>
                                        <span class="userDatatable-title">#</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('Role Name') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('Permissions') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('Created At') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title float-end">{{ __('Actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $index => $role)
                                    <tr>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                <strong>{{ $role->getTranslation('name', app()->getLocale()) ?? $role->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                @if($role->permessions->count() > 0)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge badge-primary badge-lg" style="border-radius: 6px; padding: 6px 12px;">
                                                            <i class="uil uil-shield-check me-1"></i>
                                                            {{ $role->permessions->count() }} {{ __('Permissions') }}
                                                        </span>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($role->permessions->take(3) as $permission)
                                                                <span class="badge badge-light-info badge-sm" style="border-radius: 4px;">
                                                                    {{ $permission->getTranslation('name', app()->getLocale()) ?? $permission->key }}
                                                                </span>
                                                            @endforeach
                                                            @if($role->permessions->count() > 3)
                                                                <span class="badge badge-light-secondary badge-sm" style="border-radius: 4px;">
                                                                    +{{ $role->permessions->count() - 3 }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge badge-light-warning" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-exclamation-triangle me-1"></i>
                                                        {{ __('roles.no_permissions') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $role->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap float-end">
                                                <li>
                                                    <a href="{{ route('admin.admin-management.roles.show', $role->id) }}" class="view" title="{{ __('View') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.admin-management.roles.edit', $role->id) }}" class="edit" title="{{ __('Edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ __('Delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-role"
                                                       data-role-id="{{ $role->id }}"
                                                       data-role-name="{{ $role->getTranslation('name', app()->getLocale()) ?? $role->name }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-folder-open" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ __('No roles found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
