@extends('layout.app')
@section('title', trans('reviews.reviews'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('reviews.reviews') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('reviews.reviews') }}</li>
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
                        <h4>{{ trans('reviews.reviews') }}</h4>
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
                                    <th>{{ trans('reviews.customer_name') }}</th>
                                    <th>{{ trans('menu.lawyers.title') }}</th>
                                    <th>{{ trans('reviews.rating') }}</th>
                                    <th>{{ trans('reviews.comment') }}</th>
                                    <th>{{ trans('common.status') }}</th>
                                    <th>{{ trans('common.created_at') }}</th>
                                    <th>{{ trans('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>{{ $review->customer->name ?? 'Anonymous' }}</td>
                                        <td>{{ $review->lawyer->getTranslation('name', app()->getLocale()) ?? '-' }}</td>
                                        <td>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="las la-star" style="color: #ffc107;"></i>
                                                    @else
                                                        <i class="las la-star" style="color: #ddd;"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2">{{ $review->rating }}/5</span>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0">{{ Str::limit($review->comment, 100) }}</p>
                                        </td>
                                        <td>
                                            @if($review->approved)
                                                <span class="badge badge-round badge-lg badge-success">{{ trans('common.approved') }}</span>
                                            @else
                                                <span class="badge badge-round badge-lg badge-warning">{{ trans('common.pending') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-review"
                                                       data-item-id="{{ $review->id }}"
                                                       data-item-name="{{ $review->customer->name ?? 'Review' }}">
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
                                                <i class="las la-star" style="font-size: 3rem; color: #ddd;"></i>
                                                <p class="text-muted mt-2">{{ trans('reviews.no_reviews_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reviews->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal Component --}}
<x-delete-modal
    modalId="modal-delete-review"
    title="{{ trans('reviews.confirm_delete') }}"
    message="{{ trans('reviews.delete_confirmation') }}"
    itemNameId="delete-review-name"
    confirmBtnId="confirmDeleteBtn"
    deleteRoute="{{ url('/') }}/{{ app()->getLocale() }}/admin/reviews"
    cancelText="{{ trans('common.cancel') }}"
    deleteText="{{ trans('common.delete') }}"
/>
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
