@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('branches_of_laws.branches_of_laws_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">

                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ __('branches_of_laws.branches_of_laws_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.branches-of-laws.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ __('branches_of_laws.add_branch_of_law') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $active ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.branches-of-laws.index') }}" class="mb-25">
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
                                                       placeholder="{{ __('branches_of_laws.search_by_title') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="active" class="il-gray fs-14 fw-500 mb-10">{{ __('branches_of_laws.activation') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                        id="active"
                                                        name="active">
                                                    <option value="">{{ __('branches_of_laws.all') }}</option>
                                                    <option value="1" {{ ($active ?? '') == '1' ? 'selected' : '' }}>{{ __('branches_of_laws.active') }}</option>
                                                    <option value="0" {{ ($active ?? '') == '0' ? 'selected' : '' }}>{{ __('branches_of_laws.inactive') }}</option>
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
                                                    <a href="{{ route('admin.branches-of-laws.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th width="5%">
                                        <span class="userDatatable-title">#</span>
                                    </th>
                                    @foreach($languages as $language)
                                        <th width="25%">
                                            <span class="userDatatable-title" @if($language->rtl) dir="rtl" @endif>
                                                {{ __('branches_of_laws.title') }} ({{ $language->name }})
                                            </span>
                                        </th>
                                    @endforeach
                                    <th width="10%">
                                        <span class="userDatatable-title">{{ __('branches_of_laws.image') }}</span>
                                    </th>
                                    <th width="10%">
                                        <span class="userDatatable-title">{{ __('branches_of_laws.laws_count') }}</span>
                                    </th>
                                    <th width="10%">
                                        <span class="userDatatable-title">{{ __('branches_of_laws.activation') }}</span>
                                    </th>
                                    <th width="15%">
                                        <span class="userDatatable-title">{{ __('common.created_at') }}</span>
                                    </th>
                                    <th width="10%">
                                        <span class="userDatatable-title">{{ __('common.actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branchesOfLaws ?? [] as $index => $branchOfLaw)
                                    <tr>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $branchesOfLaws->firstItem() + $index }}
                                            </div>
                                        </td>
                                        @foreach($languages as $language)
                                            <td>
                                                <div class="userDatatable-content" @if($language->rtl) dir="rtl" @endif>
                                                    <strong>{{ $branchOfLaw->getTranslation('title', $language->code) ?? '-' }}</strong>
                                                </div>
                                            </td>
                                        @endforeach
                                        <td>
                                            <div class="userDatatable-content text-center">
                                                @if($branchOfLaw->image)
                                                    <img src="{{ asset('storage/' . $branchOfLaw->image->path) }}"
                                                         alt="{{ $branchOfLaw->getTranslation('title', app()->getLocale()) }}"
                                                         class="rounded"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <i class="uil uil-image-slash" style="font-size: 24px; color: #ccc;"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content text-center">
                                                <a href="{{ route('admin.branches-of-laws.laws.index', $branchOfLaw) }}"
                                                   class="badge badge-info"
                                                   style="border-radius: 6px; padding: 8px 14px; text-decoration: none; font-size: 14px;">
                                                    <i class="uil uil-file-check-alt me-1"></i>{{ $branchOfLaw->laws_count }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                @if($branchOfLaw->active)
                                                    <span class="badge badge-success" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-check me-1"></i>{{ __('branches_of_laws.active') }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger" style="border-radius: 6px; padding: 6px 12px;">
                                                        <i class="uil uil-times me-1"></i>{{ __('branches_of_laws.inactive') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $branchOfLaw->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-start">
                                                <li>
                                                    <a href="{{ route('admin.branches-of-laws.show', $branchOfLaw->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.branches-of-laws.edit', $branchOfLaw->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-branch"
                                                       data-item-id="{{ $branchOfLaw->id }}"
                                                       data-item-name="{{ $branchOfLaw->getTranslation('title', 'en') ?? 'Branch' }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 6 + count($languages) }}" class="text-center">
                                            <div class="d-flex flex-column align-items-center py-5">
                                                <i class="uil uil-file-slash" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ __('branches_of_laws.no_branches_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($branchesOfLaws->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $branchesOfLaws->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal
        modalId="modal-delete-branch"
        :title="__('branches_of_laws.confirm_delete')"
        :message="__('branches_of_laws.delete_confirmation')"
        itemNameId="delete-branch-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.branches-of-laws.index')"
        :cancelText="__('branches_of_laws.cancel')"
        :deleteText="__('branches_of_laws.delete_branch_of_law')"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
