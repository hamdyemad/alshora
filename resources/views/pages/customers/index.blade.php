@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('customer.customers_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('customer.customers_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('customer.add_customer') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($filters['search'] ?? false || $filters['active'] ?? false || $filters['city_id'] ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-25">
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
                                                       value="{{ $filters['search'] ?? '' }}"
                                                       placeholder="{{ trans('customer.search') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.status') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ trans('customer.all_customers') }}</option>
                                                    <option value="1" {{ ($filters['active'] ?? '') == '1' ? 'selected' : '' }}>{{ trans('customer.active') }}</option>
                                                    <option value="0" {{ ($filters['active'] ?? '') == '0' ? 'selected' : '' }}>{{ trans('customer.inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="city_id" class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.city') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="city_id" 
                                                        name="city_id">
                                                    <option value="">{{ trans('customer.select_city') }}</option>
                                                    @foreach($cities as $city)
                                                        <option value="{{ $city->id }}" {{ ($filters['city_id'] ?? '') == $city->id ? 'selected' : '' }}>
                                                            {{ $city->getTranslation('name', app()->getLocale()) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="created_date_from" class="il-gray fs-14 fw-500 mb-10">{{ trans('customer.created_date_from') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="created_date_from" 
                                                       name="created_date_from" 
                                                       value="{{ $filters['created_date_from'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-default btn-squared">
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
                        <table class="table mb-0 table-bordered">
                            <thead>
                                <tr class="userDatatable-header">
                                    <th width="5%"><span class="userDatatable-title">#</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('customer.logo') }}</span></th>
                                    <th width="35%"><span class="userDatatable-title">{{ trans('customer.customer_information') }}</span></th>
                                    <th width="35%"><span class="userDatatable-title">{{ trans('customer.contact_information') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers ?? [] as $index => $customer)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $customers->firstItem() + $index }}</strong>
                                            </div>
                                        </td>

                                        {{-- Logo Column --}}
                                        <td class="align-middle text-center">
                                            @if($customer->attachments->where('type', 'logo')->first())
                                                <img src="{{ asset('storage/' . $customer->attachments->where('type', 'logo')->first()->path) }}" 
                                                     alt="Logo" 
                                                     class="rounded"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center rounded bg-light" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="uil uil-user" style="font-size: 32px; color: #ccc;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        {{-- Customer Information Column --}}
                                        <td class="align-middle">
                                            <div class="p-2">
                                                <div class="mb-2">
                                                    <strong class="d-block mb-1" style="font-size: 15px; color: #272b41;">
                                                        <i class="uil uil-user me-1"></i>{{ $customer->getTranslation('name', app()->getLocale()) ?? '-' }}
                                                    </strong>
                                                </div>
                                                
                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    @if($customer->active)
                                                        <span class="badge badge-success" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil uil-check me-1"></i>{{ trans('customer.active') }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil uil-times me-1"></i>{{ trans('customer.inactive') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="mb-1">
                                                    <small class="text-muted"><i class="uil uil-building text-info me-1"></i>{{ trans('customer.city') }}:</small>
                                                    <span class="ms-1">{{ $customer->city?->getTranslation('name', app()->getLocale()) ?? '-' }}</span>
                                                </div>

                                                <div class="mb-1">
                                                    <small class="text-muted"><i class="uil uil-location-point text-warning me-1"></i>{{ trans('customer.region') }}:</small>
                                                    <span class="ms-1">{{ $customer->region?->getTranslation('name', app()->getLocale()) ?? '-' }}</span>
                                                </div>

                                                <div>
                                                    <small class="text-muted"><i class="uil uil-calendar-alt"></i> {{ $customer->created_at->format('Y-m-d H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Contact Information Column --}}
                                        <td class="align-middle">
                                            <div class="p-2">
                                                <div class="mb-2">
                                                    <i class="uil uil-envelope text-primary me-1"></i>
                                                    <strong>{{ trans('customer.email') }}:</strong>
                                                    <div class="ms-4">{{ $customer->user?->email ?? '-' }}</div>
                                                </div>

                                                <div class="mb-2">
                                                    <i class="uil uil-phone text-success me-1"></i>
                                                    <strong>{{ trans('customer.phone') }}:</strong>
                                                    <div class="ms-4">
                                                        {{ $customer->phoneCountry?->phone_code ?? '' }} {{ $customer->phone ?? '-' }}
                                                    </div>
                                                </div>

                                                @if($customer->address)
                                                    <div class="mb-2">
                                                        <i class="uil uil-map-marker text-danger me-1"></i>
                                                        <strong>{{ trans('customer.address') }}:</strong>
                                                        <div class="ms-4">{{ $customer->address }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Actions Column --}}
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-customer"
                                                       data-item-id="{{ $customer->id }}"
                                                       data-item-name="{{ $customer->getTranslation('name', 'en') ?? 'Customer' }}">
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
                                                <i class="uil uil-users-alt" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('customer.no_customers_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($customers->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $customers->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-customer"
        title="{{ trans('customer.confirm_delete') }}"
        message="{{ trans('customer.this_action_cannot_be_undone') }}"
        itemNameId="delete-customer-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.customers.index')"
        cancelText="{{ trans('customer.cancel') }}"
        deleteText="{{ trans('customer.delete') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
