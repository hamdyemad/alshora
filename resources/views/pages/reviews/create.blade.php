@extends('layout.app')
@section('title', trans('reviews.add_review'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('reviews.add_review') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('reviews.index', $lawyer->id) }}">{{ trans('reviews.reviews') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('reviews.add_review') }}</li>
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
                    <h4>{{ trans('reviews.rate_lawyer') }} - {{ $lawyer->getTranslation('name', app()->getLocale()) }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.store', $lawyer->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="form-label">{{ trans('reviews.rating') }} <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                <div class="d-flex gap-3">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="rating" id="rating_{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="rating_{{ $i }}">
                                                <div class="d-flex align-items-center">
                                                    @for($j = 1; $j <= $i; $j++)
                                                        <i class="las la-star" style="color: #ffc107; font-size: 1.5rem;"></i>
                                                    @endfor
                                                    <span class="ms-2">
                                                        @if($i == 5) {{ trans('reviews.excellent') }}
                                                        @elseif($i == 4) {{ trans('reviews.good') }}
                                                        @elseif($i == 3) {{ trans('reviews.average') }}
                                                        @elseif($i == 2) {{ trans('reviews.poor') }}
                                                        @else {{ trans('reviews.very_poor') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="comment" class="form-label">{{ trans('reviews.comment') }}</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror"
                                      id="comment" name="comment" rows="5" placeholder="{{ trans('reviews.comment') }}">{{ old('comment') }}</textarea>
                            <small class="form-text text-muted">{{ trans('common.max_characters', ['max' => 1000]) }}</small>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4 d-flex">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="las la-save"></i> {{ trans('common.save') }}
                            </button>
                            <a href="{{ route('reviews.index', $lawyer->id) }}" class="btn btn-secondary">
                                <i class="las la-arrow-left"></i> {{ trans('common.back') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
