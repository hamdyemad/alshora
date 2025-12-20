@extends('layout.app')
@section('title', trans('contracts.add_measure'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.add_measure') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.measures.index') }}">{{ trans('contracts.measures') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.add_measure') }}</li>
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
                    <h4>{{ trans('contracts.add_measure') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.measures.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">{{ trans('contracts.title_en') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title_en') is-invalid @enderror"
                                           id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">{{ trans('contracts.title_ar') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror"
                                           id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">{{ trans('contracts.description_en') }}</label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror"
                                              id="description_en" name="description_en" rows="4">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">{{ trans('contracts.description_ar') }}</label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror"
                                              id="description_ar" name="description_ar" rows="4">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            {{ trans('common.active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4 d-flex">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="las la-save"></i> {{ trans('common.save') }}
                            </button>
                            <a href="{{ route('admin.measures.index') }}" class="btn btn-secondary">
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

