@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('lawyer.lawyers_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('lawyer.lawyers_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            @can('lawyers.create')
                            <a href="{{ route('admin.lawyers.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('lawyer.add_lawyer') }}
                            </a>
                            @endcan
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $active ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.lawyers.index') }}" class="mb-25">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search" class="il-gray fs-14 fw-500 mb-10">{{ __('common.search') }}</label>
                                                <input type="text" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="search" 
                                                       name="search" 
                                                       value="{{ $search ?? '' }}"
                                                       placeholder="{{ trans('lawyer.search_placeholder') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ trans('lawyer.status') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ trans('lawyer.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ trans('lawyer.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ trans('lawyer.inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="created_date_from" class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_date_from') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="created_date_from" 
                                                       name="created_date_from" 
                                                       value="{{ $dateFrom ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="created_date_to" class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_date_to') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="created_date_to" 
                                                       name="created_date_to" 
                                                       value="{{ $dateTo ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.lawyers.index') }}" class="btn btn-light btn-default btn-squared">
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

                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered table-striped">
                            <thead>
                                <tr class="userDatatable-header">
                                    <th width="5%"><span class="userDatatable-title">#</span></th>
                                    <th width="40%"><span class="userDatatable-title">{{ trans('lawyer.basic_data') }}</span></th>
                                    <th width="40%"><span class="userDatatable-title">{{ trans('lawyer.contact_information') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lawyers ?? [] as $index => $lawyer)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $lawyers->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        
                                        {{-- Basic Data Column --}}
                                        <td class="align-middle">
                                            <div class="p-2">
                                                <div class="mb-2">
                                                    <strong class="d-block mb-1" style="font-size: 15px; color: #272b41;">
                                                        <i class="uil uil-user me-1"></i>{{ $lawyer->getTranslation('name', app()->getLocale()) ?? '-' }}
                                                    </strong>
                                                </div>
                                                
                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    @if($lawyer->gender)
                                                        <span class="badge {{ $lawyer->gender == 'male' ? 'badge-primary' : 'badge-info' }}" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil {{ $lawyer->gender == 'male' ? 'uil-mars' : 'uil-venus' }} me-1"></i>{{ trans('lawyer.' . $lawyer->gender) }}
                                                        </span>
                                                    @endif
                                                    @if($lawyer->active)
                                                        <span class="badge badge-success" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil uil-check me-1"></i>{{ trans('lawyer.active') }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil uil-times me-1"></i>{{ trans('lawyer.inactive') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mb-1">
                                                    <small class="text-muted">{{ trans('lawyer.registration_number') }}:</small>
                                                    <strong class="ms-1">{{ $lawyer->registration_number }}</strong>
                                                </div>

                                                @if($lawyer->degree_of_registration)
                                                    <div class="mb-1">
                                                        <small class="text-muted">{{ trans('lawyer.degree') }}:</small>
                                                        <span class="badge badge-secondary ms-1" style="border-radius: 6px;">
                                                            {{ ucwords(str_replace('_', ' ', $lawyer->degree_of_registration)) }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <div class="mb-1">
                                                    <small class="text-muted">{{ trans('lawyer.consultation_price') }}:</small>
                                                    <strong class="ms-1" style="color: #27ae60;">{{ number_format($lawyer->consultation_price, 2) }} EGP</strong>
                                                </div>

                                                <div>
                                                    <small class="text-muted"><i class="uil uil-calendar-alt"></i> {{ $lawyer->created_at->format('Y-m-d H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Contact Information Column --}}
                                        <td class="align-middle">
                                            <div class="p-2">
                                                <div class="mb-2">
                                                    <i class="uil uil-envelope text-primary me-1"></i>
                                                    <strong>{{ trans('lawyer.email') }}:</strong>
                                                    <div class="ms-4">{{ $lawyer->user?->email ?? '-' }}</div>
                                                </div>

                                                <div class="mb-2">
                                                    <i class="uil uil-phone text-success me-1"></i>
                                                    <strong>{{ trans('lawyer.phone') }}:</strong>
                                                    <div class="ms-4">{{ $lawyer->phone ?? '-' }}</div>
                                                </div>

                                                @if($lawyer->address)
                                                    <div class="mb-2">
                                                        <i class="uil uil-map-marker text-danger me-1"></i>
                                                        <strong>{{ trans('lawyer.address') }}:</strong>
                                                        <div class="ms-4">{{ $lawyer->address }}</div>
                                                    </div>
                                                @endif

                                                <div class="mb-1">
                                                    <i class="uil uil-building text-info me-1"></i>
                                                    <strong>{{ trans('lawyer.city') }}:</strong>
                                                    <span class="ms-1">{{ $lawyer->city?->getTranslation('name', app()->getLocale()) ?? '-' }}</span>
                                                </div>

                                                <div>
                                                    <i class="uil uil-location-point text-warning me-1"></i>
                                                    <strong>{{ trans('lawyer.region') }}:</strong>
                                                    <span class="ms-1">{{ $lawyer->region?->getTranslation('name', app()->getLocale()) ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Actions Column --}}
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                @can('lawyers.view')
                                                <li>
                                                    <a href="{{ route('admin.lawyers.show', $lawyer->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                @endcan
                                                @can('lawyers.edit')
                                                <li>
                                                    <a href="{{ route('admin.lawyers.edit', $lawyer->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                @endcan
                                                @can('lawyers.delete')
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-lawyer"
                                                       data-item-id="{{ $lawyer->id }}"
                                                       data-item-name="{{ $lawyer->getTranslation('name', 'en') ?? 'Lawyer' }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                                @endcan
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-balance-scale" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('lawyer.no_lawyers_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($lawyers->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $lawyers->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-lawyer"
        title="{{ trans('lawyer.confirm_delete') }}"
        message="{{ trans('lawyer.delete_confirmation_message') }}"
        itemNameId="delete-lawyer-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.lawyers.index')"
        cancelText="{{ trans('lawyer.cancel') }}"
        deleteText="{{ trans('lawyer.delete_lawyer') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
