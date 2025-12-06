@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('news.news_management'), 'url' => route('admin.news.index')],
                    ['title' => trans('news.view_news')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-500">{{ trans('news.news_details') }}</h5>
                        <div class="d-flex gap-10">
                            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary btn-sm">
                                <i class="uil uil-edit me-2"></i>{{ __('common.edit') }}
                            </a>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-sm">
                                <i class="uil uil-arrow-left me-2"></i>{{ __('common.back_to_list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-newspaper me-2"></i>{{ trans('news.basic_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.title_en') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        {{ $news->getTranslation('title', 'en') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.title_ar') }}</label>
                                    <p class="fs-15 color-dark fw-500" dir="rtl">
                                        {{ $news->getTranslation('title', 'ar') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.details_en') }}</label>
                                    <p class="fs-15 color-dark">
                                        {{ $news->getTranslation('details', 'en') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.details_ar') }}</label>
                                    <p class="fs-15 color-dark" dir="rtl">
                                        {{ $news->getTranslation('details', 'ar') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.date') }}</label>
                                    <p class="fs-15 color-dark">
                                        <i class="uil uil-calendar-alt me-2"></i>{{ $news->date->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.status') }}</label>
                                    <p class="fs-15">
                                        @if($news->active)
                                            <span class="badge bg-success">{{ trans('news.active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ trans('news.inactive') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Source Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-link me-2"></i>{{ trans('news.source_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.source_en') }}</label>
                                    <p class="fs-15 color-dark">
                                        {{ $news->getTranslation('source', 'en') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.source_ar') }}</label>
                                    <p class="fs-15 color-dark" dir="rtl">
                                        {{ $news->getTranslation('source', 'ar') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('news.source_link') }}</label>
                                    <p class="fs-15 color-dark">
                                        @if($news->source_link)
                                            <a href="{{ $news->source_link }}" target="_blank" class="text-primary">
                                                <i class="uil uil-external-link-alt me-1"></i>{{ $news->source_link }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- Timestamps --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-clock me-2"></i>{{ __('common.timestamps') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.created_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $news->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $news->updated_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .view-item label {
        color: #9299b8;
        margin-bottom: 8px;
    }
    .view-item p {
        margin-bottom: 0;
        font-weight: 500;
    }
</style>
@endpush
