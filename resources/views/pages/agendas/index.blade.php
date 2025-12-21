@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('agenda.agenda_management')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('agenda.agenda_management') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ ($search ?? false || $dateFrom ?? false || $dateTo ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.agendas.index') }}" class="mb-25">
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
                                                       placeholder="{{ trans('agenda.search_placeholder') }}">
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
                                                    <a href="{{ route('admin.agendas.index') }}" class="btn btn-light btn-default btn-squared">
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
                                    <th width="15%"><span class="userDatatable-title">{{ trans('agenda.user') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('agenda.action_number') }}</span></th>
                                    <th width="20%"><span class="userDatatable-title">{{ trans('agenda.action_subject') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('agenda.court') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('agenda.datetime') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agendas ?? [] as $index => $item)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $agendas->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $item->user->name ?? '-' }}<br>
                                                <small class="text-muted">{{ $item->user->email ?? '' }}</small>
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $item->action_number }} / {{ $item->years }}
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $item->action_subject }}
                                            </div>
                                        </td>

                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $item->court }}
                                            </div>
                                        </td>

                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                {{ $item->datetime->format('Y-m-d H:i') }}
                                            </div>
                                        </td>

                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.agendas.show', $item->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" 
                                                       class="remove" 
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#modal-delete-agenda"
                                                       data-item-id="{{ $item->id }}"
                                                       data-item-name="{{ $item->action_number }}">
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
                                                <i class="uil uil-clipboard-notes" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('agenda.no_agendas_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($agendas->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $agendas->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-agenda"
        title="{{ trans('agenda.confirm_delete') }}"
        message="{{ trans('agenda.delete_confirmation_message') }}"
        itemNameId="delete-agenda-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.agendas.index')"
        cancelText="{{ trans('agenda.cancel') }}"
        deleteText="{{ trans('agenda.delete_agenda') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
