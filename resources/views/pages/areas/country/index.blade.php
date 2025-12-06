@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('areas/country.countries_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ __('areas/country.countries_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.area-settings.countries.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ __('areas/country.add_country') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $active ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.area-settings.countries.index') }}" class="mb-25">
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
                                                       placeholder="{{ __('areas/country.search_by_name_or_code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ __('areas/country.activation') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ __('areas/country.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ __('areas/country.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ __('areas/country.inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="default" class="il-gray fs-14 fw-500 mb-10">{{ __('areas/country.default') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="default" 
                                                        name="default">
                                                    <option value="">{{ __('areas/country.default_country') }}</option>
                                                    <option value="1" {{ ($default ?? '') == '1' ? 'selected' : '' }}>{{ __('common.yes') }}</option>
                                                    <option value="0" {{ ($default ?? '') == '0' ? 'selected' : '' }}>{{ __('common.no') }}</option>
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
                                                    <a href="{{ route('admin.area-settings.countries.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th>
                                        <span class="userDatatable-title">#</span>
                                    </th>
                                    @foreach($languages as $language)
                                        <th>
                                            <span class="userDatatable-title" @if($language->rtl) dir="rtl" @endif>
                                                {{ __('areas/country.name') }} ({{ $language->name }})
                                            </span>
                                        </th>
                                    @endforeach
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/country.country_code') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/country.phone_code') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/country.activation') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/country.default_country') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/country.created_at') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('common.actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($countries ?? [] as $index => $country)
                                    <tr>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $countries->firstItem() + $index }}
                                            </div>
                                        </td>
                                        @foreach($languages as $language)
                                            <td>
                                                <div class="userDatatable-content" @if($language->rtl) dir="rtl" @endif>
                                                    <strong>{{ $country->getTranslation('name', $language->code) ?? '-' }}</strong>
                                                </div>
                                            </td>
                                        @endforeach
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $country->code }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $country->phone_code ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                @if($country->active)
                                                    <span class="badge badge-success" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-check me-1"></i>{{ __('areas/country.active') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-times me-1"></i>{{ __('areas/country.inactive') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                @if($country->default_country)
                                                    <span class="badge badge-primary" >
                                                        <i class="uil uil-star me-1"></i>yes
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="uil uil-minus-circle me-1"></i>no
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $country->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-start">
                                                <li>
                                                    <a href="{{ route('admin.area-settings.countries.show', $country->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.area-settings.countries.edit', $country->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-country"
                                                       data-item-id="{{ $country->id }}"
                                                       data-item-name="{{ $country->name_en }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-map-marker" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ __('areas/country.no_countries_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($countries->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $countries->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-country"
        :title="__('areas/country.confirm_delete')"
        :message="__('areas/country.delete_confirmation')"
        itemNameId="delete-country-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.area-settings.countries.index')"
        :cancelText="__('areas/country.cancel')"
        :deleteText="__('areas/country.delete_country')"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
