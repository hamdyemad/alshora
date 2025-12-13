@extends('layout.app')
@section('title', trans('contracts.drafting_lawsuits'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.drafting_lawsuits') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.drafting_lawsuits') }}</li>
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
                        <h4>{{ trans('contracts.drafting_lawsuits') }}</h4>
                        <a href="{{ route('admin.drafting-lawsuits.create') }}" class="btn btn-primary btn-default btn-squared">
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
                                    <th>{{ trans('contracts.name') }}</th>
                                    <th>{{ trans('contracts.file') }}</th>
                                    <th>{{ trans('common.status') }}</th>
                                    <th>{{ trans('common.created_at') }}</th>
                                    <th>{{ trans('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lawsuits as $lawsuit)
                                    <tr>
                                        <td>{{ $lawsuit->id }}</td>
                                        <td>{{ $lawsuit->name }}</td>
                                        <td>
                                            @if($lawsuit->file)
                                                <a href="{{ $lawsuit->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-download"></i> {{ trans('common.download') }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ trans('common.no_file') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lawsuit->active)
                                                <span class="badge badge-success">{{ trans('common.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $lawsuit->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.drafting-lawsuits.show', $lawsuit) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.drafting-lawsuits.edit', $lawsuit) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-lawsuit"
                                                       data-item-id="{{ $lawsuit->id }}"
                                                       data-item-name="{{ $lawsuit->name }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="las la-gavel" style="font-size: 3rem; color: #ddd;"></i>
                                                <p class="text-muted mt-2">{{ trans('contracts.no_lawsuits_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($lawsuits->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $lawsuits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal Component --}}
<x-delete-modal
    modalId="modal-delete-lawsuit"
    title="{{ trans('contracts.confirm_delete') }}"
    message="{{ trans('contracts.delete_confirmation_message') }}"
    itemNameId="delete-lawsuit-name"
    confirmBtnId="confirmDeleteBtn"
    deleteRoute="{{ url('/') }}/{{ app()->getLocale() }}/admin/drafting-lawsuits"
    cancelText="{{ trans('common.cancel') }}"
    deleteText="{{ trans('common.delete') }}"
/>
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
