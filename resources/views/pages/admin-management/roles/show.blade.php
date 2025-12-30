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
                        'title' => __('roles.view_role')
                    ]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 bg-white radius-xl w-100 mb-30">
                    <div class="card-header py-20 px-25 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('roles.role_details') }}</h6>
                        <div class="d-flex gap-2">
                            @can('edit-roles')
                                <a href="{{ route('admin.admin-management.roles.edit', $role->id) }}" class="btn btn-warning btn-default btn-squared">
                                    <i class="uil uil-edit"></i> {{ __('common.edit') }}
                                </a>
                            @endcan
                            <a href="{{ route('admin.admin-management.roles.index') }}" class="btn btn-light btn-default btn-squared">
                                <i class="uil uil-angle-left"></i> {{ __('common.back') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-25">
                        <!-- Role Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="uil uil-info-circle me-2"></i>
                                            {{ __('roles.role_information') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($languages as $language)
                                                <div class="col-md-6 mb-3">
                                                    <div class="info-item">
                                                        <label class="text-muted mb-1" @if($language->code == 'ar') dir="rtl" @endif>
                                                            @if($language->code == 'ar')
                                                                اسم الدور ({{ $language->name }})
                                                            @else
                                                                {{ __('roles.name') }} ({{ $language->name }})
                                                            @endif
                                                        </label>
                                                        <p class="fw-600 mb-0" @if($language->code == 'ar') dir="rtl" @endif>
                                                            {{ $role->getTranslation('name', $language->code) ?? '-' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="col-md-6 mb-3">
                                                <div class="info-item">
                                                    <label class="text-muted mb-1">{{ __('common.created_at') }}</label>
                                                    <p class="fw-600 mb-0">{{ $role->created_at->format('Y-m-d H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="info-item">
                                                    <label class="text-muted mb-1">{{ __('common.updated_at') }}</label>
                                                    <p class="fw-600 mb-0">{{ $role->updated_at->format('Y-m-d H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="uil uil-shield-check me-2"></i>
                                            {{ __('roles.assigned_permissions') }}
                                        </h6>
                                        <span class="badge badge-primary badge-lg" style="border-radius: 6px; padding: 6px 12px;">
                                            {{ $role->permessions->count() }} {{ __('roles.permissions') }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        @if($role->permessions->count() > 0)
                                            @php
                                                // Group permissions by resource
                                                $groupedPermissions = $role->permessions->groupBy(function($permission) {
                                                    $key = $permission->key;
                                                    if (strpos($key, '.') !== false) {
                                                        $parts = explode('.', $key);
                                                        array_pop($parts);
                                                        return implode('.', $parts);
                                                    } elseif (strpos($key, '-') !== false) {
                                                        $parts = explode('-', $key, 2);
                                                        return $parts[1] ?? $key;
                                                    }
                                                    return $key;
                                                });
                                            @endphp

                                            <div class="row g-3">
                                                @foreach($groupedPermissions as $groupName => $permissions)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="permission-group-card">
                                                            <div class="permission-group-header">
                                                                <i class="uil uil-apps me-2"></i>
                                                                <span class="group-title">{{ ucwords(str_replace(['.', '-', '_'], ' ', $groupName)) }}</span>
                                                                <span class="badge bg-white text-primary ms-auto">{{ $permissions->count() }}</span>
                                                            </div>
                                                            <div class="permission-group-body">
                                                                @foreach($permissions as $permission)
                                                                    @php
                                                                        $key = $permission->key;
                                                                        $action = '';
                                                                        if (strpos($key, '.') !== false) {
                                                                            $parts = explode('.', $key);
                                                                            $action = end($parts);
                                                                        } elseif (strpos($key, '-') !== false) {
                                                                            $parts = explode('-', $key, 2);
                                                                            $action = $parts[0];
                                                                        }
                                                                        
                                                                        $badgeColor = match($action) {
                                                                            'view' => 'info',
                                                                            'create' => 'success',
                                                                            'edit' => 'warning',
                                                                            'delete' => 'danger',
                                                                            'manage' => 'primary',
                                                                            'approve', 'accept' => 'success',
                                                                            'reject' => 'danger',
                                                                            'send' => 'primary',
                                                                            default => 'secondary'
                                                                        };
                                                                        
                                                                        $actionDisplay = match($action) {
                                                                            'view' => 'Read',
                                                                            'create' => 'Create',
                                                                            'edit' => 'Edit',
                                                                            'delete' => 'Delete',
                                                                            'manage' => 'Manage',
                                                                            'approve' => 'Approve',
                                                                            'accept' => 'Accept',
                                                                            'reject' => 'Reject',
                                                                            'send' => 'Send',
                                                                            default => ucfirst($action)
                                                                        };
                                                                    @endphp
                                                                    <div class="permission-badge-item">
                                                                        <span class="badge badge-{{ $badgeColor }}">
                                                                            {{ $actionDisplay }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="uil uil-shield-slash" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-2 text-muted">{{ __('roles.no_permissions_assigned') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .info-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .permission-group-card {
            background: #ffffff;
            border: 2px solid #e8ecf1;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .permission-group-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.12);
            transform: translateY(-2px);
        }

        .permission-group-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
        }

        .permission-group-header .badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 700;
        }

        .permission-group-body {
            padding: 14px;
            background: #fafbfc;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .permission-badge-item .badge {
            font-size: 11px;
            padding: 6px 12px;
            font-weight: 700;
            border-radius: 16px;
            text-transform: uppercase;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge-info {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%) !important;
            color: #fff !important;
        }

        .badge-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
            color: #fff !important;
        }

        .badge-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            color: #fff !important;
        }

        .badge-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
            color: #fff !important;
        }

        .badge-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: #fff !important;
        }

        .badge-secondary {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%) !important;
            color: #fff !important;
        }
    </style>
    @endpush
@endsection
