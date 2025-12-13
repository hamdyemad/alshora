@extends('layout.app')
@section('title', trans('contracts.drafting_contracts'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.drafting_contracts') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.drafting_contracts') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header color-dark fw-500">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h4>{{ trans('contracts.drafting_contracts') }}</h4>
                        <a href="{{ route('admin.drafting-contracts.create') }}" class="btn btn-primary btn-default btn-squared">
                            <i class="las la-plus"></i> {{ trans('common.add_new') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('common.id') }}</th>
                                    <th>{{ trans('contracts.name_en') }}</th>
                                    <th>{{ trans('contracts.name_ar') }}</th>
                                    <th>{{ trans('contracts.file_en') }}</th>
                                    <th>{{ trans('contracts.file_ar') }}</th>
                                    <th>{{ trans('common.status') }}</th>
                                    <th>{{ trans('common.created_at') }}</th>
                                    <th>{{ trans('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                    <tr>
                                        <td>{{ $contract->id }}</td>
                                        <td>{{ $contract->name_en }}</td>
                                        <td>{{ $contract->name_ar }}</td>
                                        <td>
                                            @if($contract->file_en)
                                                <a href="{{ $contract->file_en_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-download"></i> {{ trans('common.download') }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ trans('common.no_file') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($contract->file_ar)
                                                <a href="{{ $contract->file_ar_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-download"></i> {{ trans('common.download') }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ trans('common.no_file') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($contract->active)
                                                <span class="badge badge-success">{{ trans('common.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $contract->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.drafting-contracts.show', $contract) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.drafting-contracts.edit', $contract) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-contract"
                                                       data-item-id="{{ $contract->id }}"
                                                       data-item-name="{{ $contract->name_en }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="las la-file-contract" style="font-size: 3rem; color: #ddd;"></i>
                                                <p class="text-muted mt-2">{{ trans('contracts.no_contracts_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($contracts->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $contracts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal Component --}}
<x-delete-modal
    modalId="modal-delete-contract"
    title="{{ trans('contracts.confirm_delete') }}"
    message="{{ trans('contracts.delete_confirmation_message') }}"
    itemNameId="delete-contract-name"
    confirmBtnId="confirmDeleteBtn"
    deleteRoute="{{ url('/') }}/{{ app()->getLocale() }}/admin/drafting-contracts"
    cancelText="{{ trans('common.cancel') }}"
    deleteText="{{ trans('common.delete') }}"
/>
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
