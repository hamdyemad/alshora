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
                        'title' => trans('menu.admin managment.roles managment')
                    ]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 bg-white radius-xl w-100 mb-30">
                    <div class="card-header py-20 px-25 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('roles.roles_list') }}</h6>
                        @can('roles.create')
                            <a href="{{ route('admin.admin-management.roles.create') }}" class="btn btn-primary btn-default btn-squared">
                                <i class="uil uil-plus"></i> {{ __('roles.create_role') }}
                            </a>
                        @endcan
                    </div>

                    <div class="card-body p-25">
                        <!-- Filters -->
                        <div class="row mb-25">
                            <div class="col-md-12">
                                <form action="{{ route('admin.admin-management.roles.index') }}" method="GET" class="d-flex gap-3 flex-wrap">
                                    <div class="flex-grow-1">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                               placeholder="{{ __('common.search') }}..."
                                               value="{{ $search ?? '' }}">
                                    </div>
                                    <div>
                                        <input type="date" 
                                               name="date_from" 
                                               class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                               value="{{ $dateFrom ?? '' }}"
                                               placeholder="{{ __('common.from_date') }}">
                                    </div>
                                    <div>
                                        <input type="date" 
                                               name="date_to" 
                                               class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                               value="{{ $dateTo ?? '' }}"
                                               placeholder="{{ __('common.to_date') }}">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-default btn-squared">
                                            <i class="uil uil-search"></i> {{ __('common.search') }}
                                        </button>
                                    </div>
                                    @if($search || $dateFrom || $dateTo)
                                        <div>
                                            <a href="{{ route('admin.admin-management.roles.index') }}" class="btn btn-light btn-default btn-squared">
                                                <i class="uil uil-redo"></i> {{ __('common.reset') }}
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- Roles Table -->
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="userDatatable-header">
                                        <th>
                                            <span class="userDatatable-title">#</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('roles.name') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('roles.permissions_count') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('common.created_at') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title justify-content-center d-flex">{{ __('common.actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roles as $role)
                                        <tr>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2">
                                                            <div class="avatar-circle bg-primary text-white" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 600;">
                                                                {{ strtoupper(substr($role->name, 0, 1)) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $role->name }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    <span class="badge badge-primary badge-lg" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-shield-check me-1"></i>
                                                        {{ $role->permessions->count() }} {{ __('roles.permissions') }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $role->created_at->format('Y-m-d') }}
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="orderDatatable_actions mb-0 d-flex flex-wrap gap-1 justify-content-center">
                                                    @can('roles.view')
                                                        <li>
                                                            <a href="{{ route('admin.admin-management.roles.show', $role->id) }}" 
                                                               class="view" 
                                                               title="{{ __('common.view') }}">
                                                                <i class="uil uil-eye"></i>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('roles.edit')
                                                        <li>
                                                            <a href="{{ route('admin.admin-management.roles.edit', $role->id) }}" 
                                                               class="edit" 
                                                               title="{{ __('common.edit') }}">
                                                                <i class="uil uil-edit"></i>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('roles.delete')
                                                        <li>
                                                            <a href="javascript:void(0);" 
                                                               class="remove" 
                                                               title="{{ __('common.delete') }}"
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#modal-delete-role"
                                                               data-item-id="{{ $role->id }}"
                                                               data-item-name="{{ $role->name }}">
                                                                <i class="uil uil-trash-alt"></i>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="uil uil-folder-open" style="font-size: 48px; color: #ccc;"></i>
                                                    <p class="mt-2 text-muted">{{ __('common.no_data_found') }}</p>
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
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-role"
        title="{{ __('roles.confirm_delete') }}"
        message="{{ __('roles.delete_role_confirmation') }}"
        itemNameId="delete-role-name"
        confirmBtnId="confirmDeleteRoleBtn"
        :deleteRoute="route('admin.admin-management.roles.index')"
        cancelText="{{ __('common.cancel') }}"
        deleteText="{{ __('common.delete') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay 
        :loadingText="trans('loading.processing')" 
        :loadingSubtext="trans('loading.please_wait')" 
    />
@endpush
