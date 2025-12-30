@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('admin.admins_management'), 'url' => route('admin.admin-management.admins.index')],
                    ['title' => trans('admin.view_admin')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 p-30 bg-white radius-xl mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('admin.admin_details') }}</h4>
                        <div class="d-flex gap-2">
                            @can('edit-admins')
                            <a href="{{ route('admin.admin-management.admins.edit', $admin->id) }}" class="btn btn-warning btn-default btn-squared">
                                <i class="uil uil-edit"></i> {{ __('common.edit') }}
                            </a>
                            @endcan
                            <a href="{{ route('admin.admin-management.admins.index') }}" class="btn btn-light btn-default btn-squared">
                                <i class="uil uil-arrow-left"></i> {{ __('common.back') }}
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border mb-25">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ trans('admin.admin_info') }}</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <th width="30%">{{ trans('admin.name') }}:</th>
                                            <td>{{ $admin->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('admin.email') }}:</th>
                                            <td>{{ $admin->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('admin.status') }}:</th>
                                            <td>
                                                <span class="badge {{ !$admin->is_blocked ? 'badge-success' : 'badge-danger' }}">
                                                    {{ !$admin->is_blocked ? trans('admin.active') : trans('admin.blocked') }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('admin.created_at') }}:</th>
                                            <td>{{ $admin->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border mb-25">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ trans('admin.assigned_roles') }}</h6>
                                </div>
                                <div class="card-body">
                                    @if($admin->roles->count() > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($admin->roles as $role)
                                                <span class="badge badge-lg badge-primary">
                                                    {{ $role->getTranslation('name', app()->getLocale()) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">{{ trans('admin.no_roles_assigned') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
