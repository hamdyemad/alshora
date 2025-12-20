@extends('layout.app')
@section('title', trans('contracts.measure_details'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.measure_details') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.measures.index') }}">{{ trans('contracts.measures') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.measure_details') }}</li>
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
                        <h4>{{ trans('contracts.measure_details') }}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.measures.edit', $measure) }}" class="btn btn-warning me-2">
                                <i class="las la-edit"></i> {{ trans('common.edit') }}
                            </a>
                            <a href="{{ route('admin.measures.index') }}" class="btn btn-secondary">
                                <i class="las la-arrow-left"></i> {{ trans('common.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ trans('contracts.basic_information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ trans('common.id') }}:</strong></td>
                                            <td>{{ $measure->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('contracts.title_en') }}:</strong></td>
                                            <td>{{ $measure->getTranslation('title', 'en') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('contracts.title_ar') }}:</strong></td>
                                            <td>{{ $measure->getTranslation('title', 'ar') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('contracts.description_en') }}:</strong></td>
                                            <td>{{ $measure->getTranslation('description', 'en') ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('contracts.description_ar') }}:</strong></td>
                                            <td>{{ $measure->getTranslation('description', 'ar') ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.status') }}:</strong></td>
                                            <td>
                                                @if($measure->active)
                                                    <span class="badge badge-success">{{ trans('common.active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.created_at') }}:</strong></td>
                                            <td>{{ $measure->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.updated_at') }}:</strong></td>
                                            <td>{{ $measure->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
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

