@extends('layout.app')
@section('title', trans('laws.law_details'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('laws.law_details') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.branches-of-laws.index') }}">{{ trans('branches_of_laws.branches_of_laws_management') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.branches-of-laws.laws.index', $branchOfLaw) }}">{{ trans('laws.laws_management') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('laws.law_details') }}</li>
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
                        <h4>{{ trans('laws.law_details') }}</h4>
                        <div>
                            <a href="{{ route('admin.branches-of-laws.laws.edit', ['branches_of_law' => $branchOfLaw->id, 'law' => $law->id]) }}" class="btn btn-warning me-2">
                                <i class="las la-edit"></i> {{ trans('common.edit') }}
                            </a>
                            <a href="{{ route('admin.branches-of-laws.laws.index', ['branches_of_law' => $branchOfLaw->id]) }}" class="btn btn-secondary">
                                <i class="las la-arrow-left"></i> {{ trans('common.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ trans('laws.basic_information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ trans('common.id') }}:</strong></td>
                                            <td>{{ $law->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('laws.title_en') }}:</strong></td>
                                            <td>{{ $law->getTranslation('title', 'en') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('laws.title_ar') }}:</strong></td>
                                            <td>{{ $law->getTranslation('title', 'ar') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.status') }}:</strong></td>
                                            <td>
                                                @if($law->active)
                                                    <span class="badge badge-success">{{ trans('common.active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.created_at') }}:</strong></td>
                                            <td>{{ $law->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.updated_at') }}:</strong></td>
                                            <td>{{ $law->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ trans('laws.descriptions') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6>{{ trans('laws.description_en') }}</h6>
                                        <p>{{ $law->getTranslation('description', 'en') ?? trans('common.no_data_available') }}</p>
                                    </div>
                                    <div class="mb-4">
                                        <h6>{{ trans('laws.description_ar') }}</h6>
                                        <p>{{ $law->getTranslation('description', 'ar') ?? trans('common.no_data_available') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
