@extends('layout.app')
@section('title', trans('contracts.add_contract'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.add_contract') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.drafting-contracts.index') }}">{{ trans('contracts.drafting_contracts') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.add_contract') }}</li>
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
                    <h4>{{ trans('contracts.add_contract') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.drafting-contracts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_en" class="form-label">{{ trans('contracts.name_en') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                           id="name_en" name="name_en" value="{{ old('name_en') }}" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_ar" class="form-label">{{ trans('contracts.name_ar') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                                           id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="file_en" class="form-label">{{ trans('contracts.file_en') }}</label>
                                    <input type="file" class="form-control @error('file_en') is-invalid @enderror"
                                           id="file_en" name="file_en" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">{{ trans('contracts.file_types_allowed') }}: PDF, DOC, DOCX ({{ trans('contracts.max_size') }}: 10MB)</small>
                                    @error('file_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="file_ar" class="form-label">{{ trans('contracts.file_ar') }}</label>
                                    <input type="file" class="form-control @error('file_ar') is-invalid @enderror"
                                           id="file_ar" name="file_ar" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">{{ trans('contracts.file_types_allowed') }}: PDF, DOC, DOCX ({{ trans('contracts.max_size') }}: 10MB)</small>
                                    @error('file_ar')
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
                            <a href="{{ route('admin.drafting-contracts.index') }}" class="btn btn-secondary">
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
