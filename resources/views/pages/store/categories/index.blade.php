@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('store.categories')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('store.categories') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.store.categories.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('store.add_category') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $active ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.store.categories.index') }}" class="mb-25">
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
                                                       value="{{ $search ?? '' }}"
                                                       placeholder="{{ trans('store.search_placeholder') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ trans('common.status') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ trans('common.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ trans('common.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ trans('common.inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.store.categories.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th width="25%"><span class="userDatatable-title">{{ trans('store.category_name_en') }}</span></th>
                                    <th width="25%"><span class="userDatatable-title">{{ trans('store.category_name_ar') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('store.category_image') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('common.status') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('common.created_at') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories ?? [] as $index => $category)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $categories->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $category->getTranslation('name', 'en') ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $category->getTranslation('name', 'ar') ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($category->image)
                                                <img src="{{ asset('storage/'.$category->image) }}" alt="" class="rounded" style="height:50px; width:50px; object-fit:cover;">
                                            @else
                                                <span class="text-muted">{{ trans('common.no_file') }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if($category->active)
                                                <span class="badge badge-success" style="border-radius: 6px; padding: 5px 10px;">
                                                    <i class="uil uil-check me-1"></i>{{ trans('common.active') }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger" style="border-radius: 6px; padding: 5px 10px;">
                                                    <i class="uil uil-times me-1"></i>{{ trans('common.inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                <small class="text-muted">{{ $category->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.store.categories.show', $category->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.store.categories.edit', $category->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-category"
                                                       data-item-id="{{ $category->id }}"
                                                       data-item-name="{{ $category->getTranslation('name', 'en') ?? 'Category' }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-shopping-bag" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('store.no_categories_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($categories->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $categories->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-category"
        title="{{ trans('store.confirm_delete') }}"
        message="{{ trans('store.delete_confirmation_message') }}"
        itemNameId="delete-category-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.store.categories.index')"
        cancelText="{{ trans('common.cancel') }}"
        deleteText="{{ trans('common.delete') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
