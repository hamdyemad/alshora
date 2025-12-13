@extends('layout.app')
@section('title', trans('contracts.lawsuit_details'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('contracts.lawsuit_details') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.drafting-lawsuits.index') }}">{{ trans('contracts.drafting_lawsuits') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('contracts.lawsuit_details') }}</li>
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
                        <h4>{{ trans('contracts.lawsuit_details') }}</h4>
                        <div class="d-flex">
                            <a href="{{ route('admin.drafting-lawsuits.edit', $draftingLawsuit) }}" class="btn btn-warning me-2">
                                <i class="las la-edit"></i> {{ trans('common.edit') }}
                            </a>
                            <a href="{{ route('admin.drafting-lawsuits.index') }}" class="btn btn-secondary">
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
                                    <h5>{{ trans('contracts.basic_information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ trans('common.id') }}:</strong></td>
                                            <td>{{ $draftingLawsuit->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('contracts.name') }}:</strong></td>
                                            <td>{{ $draftingLawsuit->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.status') }}:</strong></td>
                                            <td>
                                                @if($draftingLawsuit->active)
                                                    <span class="badge badge-success">{{ trans('common.active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ trans('common.inactive') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.created_at') }}:</strong></td>
                                            <td>{{ $draftingLawsuit->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ trans('common.updated_at') }}:</strong></td>
                                            <td>{{ $draftingLawsuit->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ trans('contracts.files') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h6>{{ trans('contracts.file') }}</h6>
                                        @if($draftingLawsuit->file)
                                            <div class="d-flex align-items-center">
                                                <i class="las la-file-pdf text-danger me-2" style="font-size: 2rem;"></i>
                                                <div>
                                                    <p class="mb-1">{{ basename($draftingLawsuit->file) }}</p>
                                                    <a href="{{ $draftingLawsuit->file_url }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="las la-download"></i> {{ trans('common.download') }}
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-muted">{{ trans('common.no_file') }}</p>
                                        @endif
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
