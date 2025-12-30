@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('client_agenda.client_agenda_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('client_agenda.client_agenda_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.client-agendas.index') }}" class="mb-25">
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
                                                       placeholder="{{ trans('client_agenda.search_placeholder') }}">
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.client-agendas.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th width="15%"><span class="userDatatable-title">{{ trans('client_agenda.user') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('client_agenda.client_name') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('client_agenda.client_phone') }}</span></th>
                                    <th width="20%"><span class="userDatatable-title">{{ trans('client_agenda.client_inquiry') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('client_agenda.follow_up_date') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientAgendas ?? [] as $index => $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $clientAgendas->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $item->user->name ?? '-' }}<br>
                                                <small class="text-muted">{{ $item->user->email ?? '' }}</small>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="userDatatable-content fw-500">
                                                {{ $item->client_name }}
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                @if($item->client_phone)
                                                    <i class="uil uil-phone me-1"></i>{{ $item->client_phone }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ Str::limit($item->client_inquiry, 50) ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <i class="uil uil-calendar-alt me-1"></i>{{ $item->follow_up_date->format('Y-m-d') }}
                                            </div>
                                        </td>

                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.client-agendas.show', $item->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                @can('client-agendas.delete')
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-client-agenda"
                                                       data-item-id="{{ $item->id }}"
                                                       data-item-name="{{ $item->client_name }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                                @endcan
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-users-alt" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('client_agenda.no_items_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($clientAgendas->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $clientAgendas->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-client-agenda"
        title="{{ trans('client_agenda.confirm_delete') }}"
        message="{{ trans('client_agenda.delete_confirmation_message') }}"
        itemNameId="delete-client-agenda-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.client-agendas.index')"
        cancelText="{{ __('common.cancel') }}"
        deleteText="{{ __('common.delete') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
