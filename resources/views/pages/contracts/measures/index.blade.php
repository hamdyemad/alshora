@extends('layout.app')
@section('title', trans('contracts.measures'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.measures') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.measures') }}</li>
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
                        <h4>{{ trans('contracts.measures') }}</h4>
                        <a href="{{ route('admin.measures.create') }}" class="btn btn-primary btn-default btn-squared">
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
                                    <th>{{ trans('contracts.title_en') }}</th>
                                    <th>{{ trans('contracts.title_ar') }}</th>
                                    <th>{{ trans('contracts.description_en') }}</th>
                                    <th>{{ trans('contracts.description_ar') }}</th>
                                    <th>{{ trans('common.status') }}</th>
                                    <th>{{ trans('common.created_at') }}</th>
                                    <th>{{ trans('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($measures as $measure)
                                    <tr>
                                        <td>{{ $measure->id }}</td>
                                        <td>{{ $measure->getTranslation('title', 'en') }}</td>
                                        <td>{{ $measure->getTranslation('title', 'ar') }}</td>
                                        <td>{{ $measure->getTranslation('description', 'en') ? Str::limit($measure->getTranslation('description', 'en'), 50) : '-' }}</td>
                                        <td>{{ $measure->getTranslation('description', 'ar') ? Str::limit($measure->getTranslation('description', 'ar'), 50) : '-' }}</td>
                                        <td>
                                            @if($measure->active)
                                                <span class="badge badge-success">{{ trans('common.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $measure->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.measures.show', $measure) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.measures.edit', $measure) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-measure"
                                                       data-item-id="{{ $measure->id }}"
                                                       data-item-name="{{ $measure->getTranslation('title', 'en') }}">
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
                                                <i class="las la-ruler" style="font-size: 3rem; color: #ddd;"></i>
                                                <p class="text-muted mt-2">{{ trans('contracts.no_measures_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($measures->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $measures->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal Component --}}
<x-delete-modal
    modalId="modal-delete-measure"
    title="{{ trans('contracts.confirm_delete') }}"
    message="{{ trans('contracts.delete_confirmation_message_measure') }}"
    itemNameId="delete-measure-name"
    confirmBtnId="confirmDeleteBtn"
    deleteRoute="{{ url('/') }}/{{ app()->getLocale() }}/admin/measures"
    cancelText="{{ trans('common.cancel') }}"
    deleteText="{{ trans('common.delete') }}"
/>
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush

