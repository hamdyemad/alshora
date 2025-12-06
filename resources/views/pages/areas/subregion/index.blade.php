@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('areas/subregion.subregions_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ __('areas/subregion.subregions_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.area-settings.subregions.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ __('areas/subregion.add_subregion') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $regionId ?? false || $active ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.area-settings.subregions.index') }}" class="mb-25">
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
                                                       placeholder="{{ __('areas/subregion.search_by_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="region_id" class="il-gray fs-14 fw-500 mb-10">{{ __('areas/subregion.region') }}</label>
                                                <div class="dm-select">
                                                    <select class="form-control select2-region-filter" 
                                                            id="region_id" 
                                                            name="region_id">
                                                        <option value="">{{ __('areas/subregion.all_regions') }}</option>
                                                        @foreach($regions as $region)
                                                            <option value="{{ $region['id'] }}" {{ ($regionId ?? '') == $region['id'] ? 'selected' : '' }}>
                                                                {{ $region['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ __('areas/subregion.activation') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ __('areas/subregion.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ __('areas/subregion.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ __('areas/subregion.inactive') }}</option>
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
                                                    <a href="{{ route('admin.area-settings.subregions.index') }}" class="btn btn-light btn-default btn-squared">
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
                                                {{ __('areas/subregion.name') }} ({{ $language->name }})
                                            </span>
                                        </th>
                                    @endforeach
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/subregion.region') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/subregion.activation') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('areas/subregion.created_at') }}</span>
                                    </th>
                                    <th>
                                        <span class="userDatatable-title">{{ __('common.actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subregions ?? [] as $index => $subregion)
                                    <tr>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $subregions->firstItem() + $index }}
                                            </div>
                                        </td>
                                        @foreach($languages as $language)
                                            <td>
                                                <div class="userDatatable-content" @if($language->rtl) dir="rtl" @endif>
                                                    <strong>{{ $subregion->getTranslation('name', $language->code) ?? '-' }}</strong>
                                                </div>
                                            </td>
                                        @endforeach
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $subregion->region->getTranslation('name', app()->getLocale()) ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                @if($subregion->active)
                                                    <span class="badge badge-success" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-check me-1"></i>{{ __('areas/subregion.active') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-times me-1"></i>{{ __('areas/subregion.inactive') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $subregion->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-start">
                                                <li>
                                                    <a href="{{ route('admin.area-settings.subregions.show', $subregion->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.area-settings.subregions.edit', $subregion->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-subregion"
                                                       data-item-id="{{ $subregion->id }}"
                                                       data-item-name="{{ $subregion->getTranslation('name', 'en') }}">
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
                                                <p class="mt-3 text-muted">{{ __('areas/subregion.no_subregions_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($subregions->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $subregions->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-subregion"
        :title="__('areas/subregion.confirm_delete')"
        :message="__('areas/subregion.delete_confirmation')"
        itemNameId="delete-subregion-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.area-settings.subregions.index')"
        :cancelText="__('areas/subregion.cancel')"
        :deleteText="__('areas/subregion.delete_subregion')"
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
        // Initialize Select2 for region filter
        $('.select2-region-filter').select2({
            placeholder: '{{ __('areas/subregion.all_regions') }}',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
