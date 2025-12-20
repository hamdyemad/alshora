@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('store.categories'), 'url' => route('admin.store.categories.index')],
                    ['title' => trans('store.category_details')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-500">{{ trans('store.category_details') }}</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.store.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                    <i class="uil uil-edit"></i> {{ __('common.edit') }}
                                </a>
                                <a href="{{ route('admin.store.categories.index') }}" class="btn btn-sm btn-light">
                                    <i class="uil uil-arrow-left"></i> {{ __('common.back_to_list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('store.category_name_en') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ $category->getTranslation('name', 'en') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('store.category_name_ar') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ $category->getTranslation('name', 'ar') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('store.category_image') }}</label>
                                    <div class="mt-10">
                                        @if($category->image)
                                            <img src="{{ asset('storage/'.$category->image) }}"
                                                 alt="Category Image"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                        @else
                                            <div class="text-center p-4 bg-light rounded" style="max-width: 300px;">
                                                <i class="uil uil-image" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="text-muted mt-2 mb-0">{{ trans('common.no_file') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('common.status') }}</label>
                                    <p class="fs-15">
                                        @if($category->active)
                                            <span class="badge badge-round badge-lg bg-success">{{ trans('common.active') }}</span>
                                        @else
                                            <span class="badge badge-round badge-lg bg-danger">{{ trans('common.inactive') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $category->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $category->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
