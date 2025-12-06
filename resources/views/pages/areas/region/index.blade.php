@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('areas/region.regions_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ __('areas/region.regions_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.area-settings.regions.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ __('areas/region.add_region') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $cityId ?? false || $active ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.area-settings.regions.index') }}" class="mb-25">
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
                                                       placeholder="{{ __('areas/region.search_by_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="city_id" class="il-gray fs-14 fw-500 mb-10">{{ __('areas/region.city') }}</label>
                                                <div class="dm-select">
                                                    <select class="form-control select2-city-filter" 
                                                            id="city_id" 
                                                            name="city_id">
                                                        <option value="">{{ __('areas/region.all_cities') }}</option>
                                                        @foreach($cities as $city)
                                                            <option value="{{ $city['id'] }}" {{ ($cityId ?? '') == $city['id'] ? 'selected' : '' }}>
                                                                {{ $city['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ __('areas/region.activation') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ __('areas/region.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ __('areas/region.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ __('areas/region.inactive') }}</option>
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="created_date_to" class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_date_to') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="created_date_to" 
                                                       name="created_date_to" 
                                                       value="{{ $dateTo ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.area-settings.regions.index') }}" class="btn btn-light btn-default btn-squared">
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
                                                {{ __('areas/region.name') }} ({{ $language->name }})
                                            </span>
                                        </th>
                                    @endforeach
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/region.city') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/region.subregions_count') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/region.activation') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/region.created_at') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('common.actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($regions ?? [] as $index => $region)
                                    <tr>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $regions->firstItem() + $index }}
                                            </div>
                                        </td>
                                        @foreach($languages as $language)
                                            <td>
                                                <div class="userDatatable-content" @if($language->rtl) dir="rtl" @endif>
                                                    <strong>{{ $region->getTranslation('name', $language->code) ?? '-' }}</strong>
                                                </div>
                                            </td>
                                        @endforeach
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $region->city->getTranslation('name', app()->getLocale()) ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                <a href="{{ route('admin.area-settings.subregions.index', ['region_id' => $region->id]) }}" class="text-decoration-none">
                                                    <span class="badge badge-primary" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-location-point me-1"></i>{{ $region->subRegions()->count() }}
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                @if($region->active)
                                                    <span class="badge badge-success" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-check me-1"></i>{{ __('areas/region.active') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-times me-1"></i>{{ __('areas/region.inactive') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $region->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-start">
                                                <li>
                                                    <a href="{{ route('admin.area-settings.regions.show', $region->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.area-settings.regions.edit', $region->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-region"
                                                       data-item-id="{{ $region->id }}"
                                                       data-item-name="{{ $region->getTranslation('name', 'en') }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-map-marker" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ __('areas/region.no_regions_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($regions->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $regions->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-region"
        :title="__('areas/region.confirm_delete')"
        :message="__('areas/region.delete_confirmation')"
        itemNameId="delete-region-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.area-settings.regions.index')"
        :cancelText="__('areas/region.cancel')"
        :deleteText="__('areas/region.delete_region')"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 1px solid #e3e6ef;
        border-radius: 6px;
        padding: 8px 15px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        color: #525768;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
        right: 10px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #5f63f2;
    }
    .select2-dropdown {
        border: 1px solid #e3e6ef;
        border-radius: 6px;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5f63f2;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e3e6ef;
        border-radius: 6px;
        padding: 8px 15px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for city filter
        $('.select2-city-filter').select2({
            placeholder: '{{ __('areas/region.all_cities') }}',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
