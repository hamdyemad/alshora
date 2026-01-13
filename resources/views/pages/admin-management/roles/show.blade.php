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
                            @can('roles.edit')
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
                                <div class="permissions-view-container">
                                    <div class="permissions-view-header">
                                        <div class="d-flex align-items-center">
                                            <div class="permissions-view-icon">
                                                <i class="uil uil-shield-check"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">{{ __('roles.assigned_permissions') }}</h5>
                                                <small>{{ $role->permessions->count() }} {{ __('roles.permissions') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($role->permessions->count() > 0)
                                        @php
                                            $locale = app()->getLocale();
                                            $groupedPermissions = $role->permessions->groupBy(function($permission) use ($locale) {
                                                $groupName = $permission->getTranslation('group_by', $locale);
                                                if (empty($groupName)) {
                                                    $key = $permission->key;
                                                    if (strpos($key, '.') !== false) {
                                                        $parts = explode('.', $key);
                                                        array_pop($parts);
                                                        return ucwords(str_replace(['-', '_'], ' ', end($parts)));
                                                    }
                                                    return ucwords(str_replace(['-', '_'], ' ', $key));
                                                }
                                                return $groupName;
                                            });
                                        @endphp

                                        <div class="permissions-view-grid">
                                            @foreach($groupedPermissions as $groupName => $permissions)
                                                <div class="permission-view-card">
                                                    <div class="permission-view-card-header">
                                                        <i class="uil uil-folder-open"></i>
                                                        <span>{{ $groupName }}</span>
                                                        <span class="permission-count">{{ $permissions->count() }}</span>
                                                    </div>
                                                    <div class="permission-view-card-body">
                                                        @foreach($permissions as $permission)
                                                            @php
                                                                $key = $permission->key;
                                                                $action = '';
                                                                if (strpos($key, '.') !== false) {
                                                                    $parts = explode('.', $key);
                                                                    $action = end($parts);
                                                                }
                                                                
                                                                $actionConfig = [
                                                                    'view' => ['icon' => 'uil-eye', 'color' => 'info', 'label' => __('roles.actions.view')],
                                                                    'create' => ['icon' => 'uil-plus-circle', 'color' => 'success', 'label' => __('roles.actions.create')],
                                                                    'edit' => ['icon' => 'uil-edit', 'color' => 'warning', 'label' => __('roles.actions.edit')],
                                                                    'delete' => ['icon' => 'uil-trash-alt', 'color' => 'danger', 'label' => __('roles.actions.delete')],
                                                                    'manage' => ['icon' => 'uil-cog', 'color' => 'primary', 'label' => __('roles.actions.manage')],
                                                                    'approve' => ['icon' => 'uil-check-circle', 'color' => 'success', 'label' => __('roles.actions.approve')],
                                                                    'accept' => ['icon' => 'uil-check', 'color' => 'success', 'label' => __('roles.actions.accept')],
                                                                    'reject' => ['icon' => 'uil-times-circle', 'color' => 'danger', 'label' => __('roles.actions.reject')],
                                                                    'send' => ['icon' => 'uil-message', 'color' => 'primary', 'label' => __('roles.actions.send')],
                                                                    'settings' => ['icon' => 'uil-setting', 'color' => 'secondary', 'label' => __('roles.actions.settings')],
                                                                ];
                                                                
                                                                $config = $actionConfig[$action] ?? ['icon' => 'uil-check', 'color' => 'secondary', 'label' => ucfirst($action)];
                                                            @endphp
                                                            <span class="permission-badge permission-badge-{{ $config['color'] }}">
                                                                <i class="uil {{ $config['icon'] }}"></i>
                                                                {{ $config['label'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="permissions-empty">
                                            <i class="uil uil-shield-slash"></i>
                                            <p>{{ __('roles.no_permissions_assigned') }}</p>
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

    @push('styles')
    <style>
        .info-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        /* Permissions View Container */
        .permissions-view-container {
            background: #f8fafc;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .permissions-view-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 24px;
            color: #fff;
        }

        .permissions-view-header h5 {
            color: #fff;
            font-weight: 600;
            font-size: 18px;
        }

        .permissions-view-header small {
            color: rgba(255, 255, 255, 0.8);
        }

        .permissions-view-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }

        .permissions-view-icon i {
            font-size: 24px;
            color: #fff;
        }

        /* Permissions Grid */
        .permissions-view-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            padding: 20px;
        }

        /* Permission View Card */
        .permission-view-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .permission-view-card:hover {
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .permission-view-card-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        .permission-view-card-header i {
            color: #667eea;
            font-size: 18px;
        }

        .permission-count {
            margin-left: auto;
            background: #667eea;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .permission-view-card-body {
            padding: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* Permission Badges */
        .permission-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .permission-badge i {
            font-size: 14px;
        }

        .permission-badge-info {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: #0369a1;
        }

        .permission-badge-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #047857;
        }

        .permission-badge-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #b45309;
        }

        .permission-badge-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #b91c1c;
        }

        .permission-badge-primary {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
        }

        .permission-badge-secondary {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #475569;
        }

        /* Empty State */
        .permissions-empty {
            text-align: center;
            padding: 48px 24px;
        }

        .permissions-empty i {
            font-size: 64px;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .permissions-empty p {
            color: #64748b;
            font-size: 16px;
            margin: 0;
        }

        /* RTL Support */
        [dir="rtl"] .permissions-view-icon {
            margin-right: 0;
            margin-left: 16px;
        }

        [dir="rtl"] .permission-count {
            margin-left: 0;
            margin-right: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .permissions-view-grid {
                grid-template-columns: 1fr;
                padding: 16px;
            }
        }
    </style>
    @endpush
@endsection
