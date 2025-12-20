@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('store.products')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('store.products') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.store.products.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('store.add_product') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $active ?? false || $category_id ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.store.products.index') }}" class="mb-25">
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
                                                       placeholder="{{ trans('store.search_placeholder') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="category_id" class="il-gray fs-14 fw-500 mb-10">{{ trans('store.category') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="category_id" 
                                                        name="category_id">
                                                    <option value="">{{ trans('common.all') }}</option>
                                                    @foreach($categories ?? [] as $category)
                                                        <option value="{{ $category->id }}" {{ ($category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->getTranslation('name', app()->getLocale()) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.store.products.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th width="20%"><span class="userDatatable-title">{{ trans('store.product_name_en') }}</span></th>
                                    <th width="20%"><span class="userDatatable-title">{{ trans('store.product_name_ar') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('store.product_price') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('store.category') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('common.status') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('common.created_at') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products ?? [] as $index => $product)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $products->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $product->getTranslation('name', 'en') ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $product->getTranslation('name', 'ar') ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                <strong style="color: #27ae60;">{{ number_format($product->price, 2) }} EGP</strong>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $product->category?->getTranslation('name', app()->getLocale()) ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($product->active)
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
                                                <small class="text-muted">{{ $product->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.store.products.show', $product->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.store.products.edit', $product->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-product"
                                                       data-item-id="{{ $product->id }}"
                                                       data-item-name="{{ $product->getTranslation('name', 'en') ?? 'Product' }}">
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
                                                <i class="uil uil-shopping-bag" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('store.no_products_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($products->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $products->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-product"
        title="{{ trans('store.confirm_delete') }}"
        message="{{ trans('store.delete_confirmation_message_product') }}"
        itemNameId="delete-product-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.store.products.index')"
        cancelText="{{ trans('common.cancel') }}"
        deleteText="{{ trans('common.delete') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
