@extends('layout.app')
@section('title', trans('laws.laws_management'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('laws.laws_management') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.branches-of-laws.index') }}">{{ trans('branches_of_laws.branches_of_laws_management') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('laws.laws_management') }}</li>
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
                        <div>
                            <h4>{{ trans('laws.laws_for_branch') }}: <strong>{{ $branchOfLaw->getTranslation('title', app()->getLocale()) }}</strong></h4>
                        </div>
                        <a href="{{ route('admin.branches-of-laws.laws.create', ['branches_of_law' => $branchOfLaw->id]) }}" class="btn btn-primary">
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
                                    <th>{{ trans('laws.title_en') }}</th>
                                    <th>{{ trans('laws.title_ar') }}</th>
                                    <th>{{ trans('common.status') }}</th>
                                    <th>{{ trans('common.created_at') }}</th>
                                    <th>{{ trans('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laws as $law)
                                    <tr>
                                        <td>{{ $law->id }}</td>
                                        <td>{{ $law->getTranslation('title', 'en') }}</td>
                                        <td>{{ $law->getTranslation('title', 'ar') }}</td>
                                        <td>
                                            @if($law->active)
                                                <span class="badge badge-success">{{ trans('common.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $law->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.branches-of-laws.laws.show', ['branches_of_law' => $branchOfLaw->id, 'law' => $law->id]) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.branches-of-laws.laws.edit', ['branches_of_law' => $branchOfLaw->id, 'law' => $law->id]) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-law"
                                                       data-item-id="{{ $law->id }}"
                                                       data-item-name="{{ $law->getTranslation('title', 'en') }}">
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
                                                <i class="las la-file-alt" style="font-size: 3rem; color: #ddd;"></i>
                                                <p class="text-muted mt-2">{{ trans('laws.no_laws_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($laws->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $laws->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal Component --}}
<x-delete-modal
    modalId="modal-delete-law"
    title="{{ trans('laws.confirm_delete') }}"
    message="{{ trans('laws.delete_confirmation_message') }}"
    itemNameId="delete-law-name"
    confirmBtnId="confirmDeleteBtn"
    deleteRoute="{{ route('admin.branches-of-laws.laws.index', ['branches_of_law' => $branchOfLaw->id]) }}"
    cancelText="{{ trans('common.cancel') }}"
    deleteText="{{ trans('common.delete') }}"
/>
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
