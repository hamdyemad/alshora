@extends('layout.app')
@section('title', trans('store.order'))
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('store.order') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="las la-home"></i>{{ trans('common.dashboard') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.store.orders.index') }}">{{ trans('store.orders') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('store.order') }}</li>
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
                        <h4>{{ trans('store.order') }}</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.store.orders.index') }}" class="btn btn-secondary">
                                <i class="las la-arrow-left"></i> {{ trans('common.back') }}
                            </a>
                            <a href="{{ route('admin.store.orders.edit', $order->id) }}" class="btn btn-primary">
                                <i class="las la-edit"></i> {{ trans('common.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">{{ trans('store.lawyer_information') }}</h5>
                            @if($order->lawyer)
                                <p class="mb-1"><strong>{{ trans('lawyer.name') }}:</strong> {{ $order->lawyer->name }}</p>
                            @else
                                <p>{{ trans('store.no_lawyer_associated') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">{{ trans('store.order_details') }}</h5>
                            <table class="table table-borderless">
                                <tr><td><strong>{{ trans('store.order_number') }}:</strong></td><td>{{ $order->order_number }}</td></tr>
                                <tr><td><strong>{{ trans('store.order_status') }}:</strong></td><td>{{ trans('store.order_statuses.'.$order->status) }}</td></tr>
                                <tr><td><strong>{{ trans('store.order_notes') }}:</strong></td><td>{{ $order->notes ?: '-' }}</td></tr>
                                <tr><td><strong>{{ trans('common.created_at') }}:</strong></td><td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td></tr>
                            </table>
                        </div>
                    </div>

                    <h5 class="mb-3">{{ trans('store.products') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('common.id') }}</th>
                                    <th>{{ trans('store.product') }}</th>
                                    <th>{{ trans('store.quantity') }}</th>
                                    <th>{{ trans('store.product_price') }}</th>
                                    <th>{{ trans('store.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->product?->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price,2) }}</td>
                                        <td>{{ number_format($item->subtotal,2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end mt-4">
                        <div class="col-md-5">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">{{ trans('store.order_summary') }}</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ trans('store.subtotal') }}:</span>
                                        <span>{{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ trans('store.tax') }}:</span>
                                        <span>{{ number_format($order->tax, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>{{ trans('store.discount') }}:</span>
                                        <span>-{{ number_format($order->discount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold fs-5">
                                        <span>{{ trans('store.order_total') }}:</span>
                                        <span>{{ number_format($order->total, 2) }}</span>
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

