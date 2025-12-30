@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('admin.admins_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('admin.admins_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            @can('admins.create')
                            <a href="{{ route('admin.admin-management.admins.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('admin.add_admin') }}
                            </a>
                            @endcan
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($filters['search'] ?? false || isset($filters['is_blocked'])) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.admin-management.admins.index') }}" class="mb-25">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="search" class="il-gray fs-14 fw-500 mb-10">{{ __('common.search') }}</label>
                                                <input type="text" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="search" 
                                                       name="search" 
                                                       value="{{ $filters['search'] ?? '' }}"
                                                       placeholder="{{ trans('admin.search') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="is_blocked" class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.status') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="is_blocked" 
                                                        name="is_blocked">
                                                    <option value="">{{ trans('admin.all_admins') }}</option>
                                                    <option value="0" {{ ($filters['is_blocked'] ?? '') === '0' ? 'selected' : '' }}>{{ trans('admin.active') }}</option>
                                                    <option value="1" {{ ($filters['is_blocked'] ?? '') === '1' ? 'selected' : '' }}>{{ trans('admin.blocked') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="created_date_from" class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.created_date_from') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="created_date_from" 
                                                       name="created_date_from" 
                                                       value="{{ $filters['created_date_from'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="created_date_to" class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.created_date_to') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="created_date_to" 
                                                       name="created_date_to" 
                                                       value="{{ $filters['created_date_to'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.admin-management.admins.index') }}" class="btn btn-light btn-default btn-squared">
                                                        <i class="uil uil-redo"></i> {{ __('common.reset') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="userDatatable-header">
                                    <th><span class="userDatatable-title">#</span></th>
                                    <th><span class="userDatatable-title">{{ trans('admin.name') }}</span></th>
                                    <th><span class="userDatatable-title">{{ trans('admin.email') }}</span></th>
                                    <th><span class="userDatatable-title">{{ trans('admin.roles') }}</span></th>
                                    <th><span class="userDatatable-title">{{ trans('admin.status') }}</span></th>
                                    <th><span class="userDatatable-title">{{ trans('admin.created_at') }}</span></th>
                                    <th><span class="userDatatable-title">{{ trans('admin.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                <tr>
                                    <td>{{ $loop->iteration + ($admins->currentPage() - 1) * $admins->perPage() }}</td>
                                    <td>
                                        <div class="userDatatable-content">{{ $admin->name }}</div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">{{ $admin->email }}</div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">
                                            @foreach($admin->roles as $role)
                                                <span class="badge badge-sm badge-primary">{{ $role->getTranslation('name', app()->getLocale()) }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">
                                            @can('admins.edit')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-status" 
                                                       type="checkbox" 
                                                       data-id="{{ $admin->id }}"
                                                       {{ !$admin->is_blocked ? 'checked' : '' }}>
                                            </div>
                                            @else
                                                <span class="badge badge-sm {{ !$admin->is_blocked ? 'badge-success' : 'badge-danger' }}">
                                                    {{ !$admin->is_blocked ? trans('admin.active') : trans('admin.blocked') }}
                                                </span>
                                            @endcan
                                        </div>
                                    </td>
                                    <td>
                                        <div class="userDatatable-content">{{ $admin->created_at->format('Y-m-d') }}</div>
                                    </td>
                                    <td>
                                        <ul class="orderDatatable_actions mb-0 d-flex flex-wrap gap-1">
                                            @can('admins.view')
                                            <li>
                                                <a href="{{ route('admin.admin-management.admins.show', $admin->id) }}" 
                                                   class="view" 
                                                   title="{{ trans('admin.view_admin') }}">
                                                    <i class="uil uil-eye"></i>
                                                </a>
                                            </li>
                                            @endcan
                                            @can('admins.edit')
                                            <li>
                                                <a href="{{ route('admin.admin-management.admins.edit', $admin->id) }}" 
                                                   class="edit" 
                                                   title="{{ trans('admin.edit_admin') }}">
                                                    <i class="uil uil-edit"></i>
                                                </a>
                                            </li>
                                            @endcan
                                            @can('admins.delete')
                                            <li>
                                                <a href="javascript:void(0);" 
                                                   class="remove" 
                                                   title="{{ __('common.delete') }}"
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#modal-delete-admin"
                                                   data-item-id="{{ $admin->id }}"
                                                   data-item-name="{{ $admin->name }}">
                                                    <i class="uil uil-trash-alt"></i>
                                                </a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">{{ trans('admin.no_admins_found') }}</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($admins->hasPages())
                    <div class="d-flex justify-content-end mt-25">
                        {{ $admins->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-admin"
        title="{{ trans('admin.confirm_delete') }}"
        message="{{ trans('admin.delete_confirmation_message') }}"
        itemNameId="delete-admin-name"
        confirmBtnId="confirmDeleteAdminBtn"
        :deleteRoute="route('admin.admin-management.admins.index')"
        cancelText="{{ __('common.cancel') }}"
        deleteText="{{ __('common.delete') }}"
    />
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle Status
    $('.toggle-status').on('change', function() {
        const adminId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        
        $.ajax({
            url: `/{{ app()->getLocale() }}/admin/admin-management/admins/${adminId}/toggle-blocked`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error updating status');
                // Revert checkbox
                $(this).prop('checked', !isChecked);
            }
        });
    });
});
</script>
@endpush

@push('after-body')
    <x-loading-overlay />
@endpush
