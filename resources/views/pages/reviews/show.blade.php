@extends('layout.app')
@section('title', trans('reviews.review'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('reviews.review') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index', $lawyer->id) }}">{{ trans('reviews.reviews') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('reviews.review') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header color-dark fw-500">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h4>{{ trans('reviews.review') }}</h4>
                        <div>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                                <i class="las la-arrow-left"></i> {{ trans('common.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-500">{{ trans('reviews.customer_name') }}</h6>
                        <p>{{ $review->customer->name ?? 'Anonymous' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-500">{{ trans('reviews.rating') }}</h6>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="las la-star" style="color: #ffc107; font-size: 1.5rem;"></i>
                                @else
                                    <i class="las la-star" style="color: #ddd; font-size: 1.5rem;"></i>
                                @endif
                            @endfor
                            <span class="ms-2 fw-500">{{ $review->rating }}/5</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-500">{{ trans('reviews.comment') }}</h6>
                        <p>{{ $review->comment ?? trans('common.no_data_available') }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-500">{{ trans('common.created_at') }}</h6>
                        <p>{{ $review->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-500">{{ trans('common.status') }}</h6>
                        <p>
                            @if($review->approved)
                                <span class="badge badge-round badge-lg badge-success">{{ trans('common.approved') }}</span>
                            @else
                                <span class="badge badge-round badge-lg badge-warning">{{ trans('common.pending') }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="form-group mt-4 d-flex gap-2">
                        @if(!$review->approved)
                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="las la-check"></i> {{ trans('reviews.approve_review') }}
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="las la-times"></i> {{ trans('reviews.reject_review') }}
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="las la-times"></i> {{ trans('reviews.reject_review') }}
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                            <i class="las la-arrow-left"></i> {{ trans('common.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
