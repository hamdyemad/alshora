@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('news.news_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('news.news_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('news.add_news') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $active ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.news.index') }}" class="mb-25">
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
                                                       placeholder="{{ trans('news.search_placeholder') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ trans('news.status') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                        id="active" 
                                                        name="active">
                                                    <option value="">{{ trans('news.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ trans('news.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ trans('news.inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="date_from" class="il-gray fs-14 fw-500 mb-10">{{ __('common.date_from') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="date_from" 
                                                       name="date_from" 
                                                       value="{{ $dateFrom ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="date_to" class="il-gray fs-14 fw-500 mb-10">{{ __('common.date_to') }}</label>
                                                <input type="date" 
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                                       id="date_to" 
                                                       name="date_to" 
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
                                                    <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th width="40%"><span class="userDatatable-title">{{ trans('news.title') }}</span></th>
                                    <th width="25%"><span class="userDatatable-title">{{ trans('news.source') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('news.publish_date') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($news ?? [] as $index => $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $news->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        
                                        {{-- Title Column --}}
                                        <td class="align-middle">
                                            <div class="p-2">
                                                <strong class="d-block mb-1" style="font-size: 15px; color: #272b41;">
                                                    {{ $item->getTranslation('title', app()->getLocale()) ?? '-' }}
                                                </strong>
                                                
                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                    @if($item->active)
                                                        <span class="badge badge-success" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil uil-check me-1"></i>{{ trans('news.active') }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger" style="border-radius: 6px; padding: 5px 10px;">
                                                            <i class="uil uil-times me-1"></i>{{ trans('news.inactive') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Source Column --}}
                                        <td class="align-middle">
                                            <div class="p-2">
                                                @if($item->getTranslation('source', app()->getLocale()))
                                                    <strong>{{ $item->getTranslation('source', app()->getLocale()) }}</strong>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                                
                                                @if($item->source_link)
                                                    <div class="mt-1">
                                                        <a href="{{ $item->source_link }}" target="_blank" class="text-primary">
                                                            <i class="uil uil-external-link-alt"></i> {{ __('common.view_source') }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Date Column --}}
                                        <td class="align-middle text-center">
                                            <div>
                                                <i class="uil uil-calendar-alt"></i> {{ $item->date->format('Y-m-d') }}
                                            </div>
                                        </td>

                                        {{-- Actions Column --}}
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.news.show', $item->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-news"
                                                       data-item-id="{{ $item->id }}"
                                                       data-item-name="{{ $item->getTranslation('title', 'en') ?? 'News' }}">
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
                                                <i class="uil uil-newspaper" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('news.no_news_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($news->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $news->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-news"
        title="{{ trans('news.confirm_delete') }}"
        message="{{ trans('news.delete_confirmation_message') }}"
        itemNameId="delete-news-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.news.index')"
        cancelText="{{ trans('news.cancel') }}"
        deleteText="{{ trans('news.delete_news') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
