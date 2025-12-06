@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => __('sections_of_laws.sections_of_laws_management'), 'url' => route('admin.sections-of-laws.index')],
                    ['title' => __('sections_of_laws.view_section_of_law')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-500">{{ __('sections_of_laws.section_of_law_details') }}</h5>
                        <div class="d-flex gap-10">
                            <a href="{{ route('admin.sections-of-laws.edit', $sectionOfLaw->id) }}" class="btn btn-primary btn-sm">
                                <i class="uil uil-edit me-2"></i>{{ __('common.edit') }}
                            </a>
                            <a href="{{ route('admin.sections-of-laws.index') }}" class="btn btn-light btn-sm">
                                <i class="uil uil-arrow-left me-2"></i>{{ __('common.back_to_list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Names Section --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-text me-2"></i>{{ __('sections_of_laws.name') }}
                                </h6>
                            </div>

                            @foreach($languages as $language)
                                <div class="col-md-6 mb-25">
                                    <div class="view-item">
                                        <label class="il-gray fs-14 fw-500 mb-10">{{ __('sections_of_laws.name') }} ({{ $language->name }})</label>
                                        <p class="fs-15 color-dark fw-500" @if($language->rtl) dir="rtl" @endif>
                                            {{ $sectionOfLaw->getTranslation('name', $language->code) ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Details Section --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-file-alt me-2"></i>{{ __('sections_of_laws.details') }}
                                </h6>
                            </div>

                            @foreach($languages as $language)
                                <div class="col-md-6 mb-25">
                                    <div class="view-item">
                                        <label class="il-gray fs-14 fw-500 mb-10">{{ __('sections_of_laws.details') }} ({{ $language->name }})</label>
                                        <p class="fs-15 color-dark" @if($language->rtl) dir="rtl" @endif>
                                            {{ $sectionOfLaw->getTranslation('details', $language->code) ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Image and Status --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-image me-2"></i>{{ __('common.basic_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('sections_of_laws.image') }}</label>
                                    <div class="mt-10">
                                        @if($sectionOfLaw->image)
                                            <img src="{{ asset('storage/' . $sectionOfLaw->image->path) }}" 
                                                 alt="{{ $sectionOfLaw->getTranslation('name', app()->getLocale()) }}" 
                                                 class="img-fluid rounded shadow-sm" 
                                                 style="max-width: 400px; max-height: 400px; object-fit: cover;">
                                        @else
                                            <div class="text-center p-4 bg-light rounded" style="max-width: 400px;">
                                                <i class="uil uil-image-slash" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="text-muted mt-2 mb-0">{{ __('sections_of_laws.no_image') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('sections_of_laws.activation') }}</label>
                                    <p class="fs-15">
                                        @if($sectionOfLaw->active)
                                            <span class="badge bg-success">{{ __('sections_of_laws.active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('sections_of_laws.inactive') }}</span>
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
                                    <p class="fs-15 color-dark">{{ $sectionOfLaw->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('common.updated_at') }}</label>
                                    <p class="fs-15 color-dark">{{ $sectionOfLaw->updated_at->format('Y-m-d H:i:s') }}</p>
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
