@extends('layout.app')
@section('title', trans('store.product'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('store.product') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.store.products.index') }}">{{ trans('store.products') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('store.product') }}</li>
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
                        <h4>{{ trans('store.product') }}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.store.products.edit', $product) }}" class="btn btn-warning me-2"><i class="las la-edit"></i> {{ trans('common.edit') }}</a>
                            <a href="{{ route('admin.store.products.index') }}" class="btn btn-secondary"><i class="las la-arrow-left"></i> {{ trans('common.back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><td><strong>{{ trans('common.id') }}:</strong></td><td>{{ $product->id }}</td></tr>
                        <tr><td><strong>{{ trans('store.product_name_en') }}:</strong></td><td>{{ $product->getTranslation('name','en') }}</td></tr>
                        <tr><td><strong>{{ trans('store.product_name_ar') }}:</strong></td><td>{{ $product->getTranslation('name','ar') }}</td></tr>
                        <tr><td><strong>{{ trans('store.product_description_en') }}:</strong></td><td>{{ $product->getTranslation('description','en') ?: '-' }}</td></tr>
                        <tr><td><strong>{{ trans('store.product_description_ar') }}:</strong></td><td>{{ $product->getTranslation('description','ar') ?: '-' }}</td></tr>
                        <tr><td><strong>{{ trans('store.product_price') }}:</strong></td><td>{{ number_format($product->price,2) }}</td></tr>
                        <tr><td><strong>{{ trans('store.category') }}:</strong></td><td>{{ $product->category?->name }}</td></tr>
                        <tr><td><strong>{{ trans('store.product_image') }}:</strong></td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="" style="height:80px;">
                                @else
                                    <span class="text-muted">{{ trans('common.no_file') }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr><td><strong>{{ trans('common.status') }}:</strong></td><td>{{ $product->active ? trans('common.active') : trans('common.inactive') }}</td></tr>
                        <tr><td><strong>{{ trans('common.created_at') }}:</strong></td><td>{{ $product->created_at->format('Y-m-d H:i:s') }}</td></tr>
                        <tr><td><strong>{{ trans('common.updated_at') }}:</strong></td><td>{{ $product->updated_at->format('Y-m-d H:i:s') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

