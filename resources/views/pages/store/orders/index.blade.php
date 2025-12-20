@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('common.dashboard'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('store.orders')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                    <div class="d-flex justify-content-between align-items-center mb-25">
                        <h4 class="mb-0 fw-500">{{ trans('store.orders') }}</h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-default btn-squared" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                <i class="uil uil-filter"></i> {{ __('common.filter') }}
                            </button>
                            <a href="{{ route('admin.store.orders.create') }}" class="btn btn-primary btn-default btn-squared text-capitalize">
                                <i class="uil uil-plus"></i> {{ trans('store.create_order') }}
                            </a>
                        </div>
                    </div>

                    {{-- Search and Filter Form --}}
                    <div class="collapse {{ request()->has('search') || request()->has('status') || request()->has('lawyer_id') ? 'show' : '' }}" id="filterCollapse">
                        <form method="GET" action="{{ route('admin.store.orders.index') }}" class="mb-25">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search" class="il-gray fs-14 fw-500 mb-10">{{ __('common.search') }}</label>
                                                <input type="text"
                                                       class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                       id="search"
                                                       name="search"
                                                       value="{{ request('search') }}"
                                                       placeholder="{{ trans('store.search_placeholder_order') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="lawyer_id" class="il-gray fs-14 fw-500 mb-10">{{ trans('store.lawyer') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                        id="lawyer_id"
                                                        name="lawyer_id">
                                                    <option value="">{{ trans('common.all') }}</option>
                                                    @foreach(\App\Models\Lawyer::all() as $lawyer)
                                                        <option value="{{ $lawyer->id }}" {{ request('lawyer_id') == $lawyer->id ? 'selected' : '' }}>
                                                            {{ $lawyer->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="status" class="il-gray fs-14 fw-500 mb-10">{{ trans('common.status') }}</label>
                                                <select class="form-control ih-medium ip-gray radius-xs b-light px-15"
                                                        id="status"
                                                        name="status">
                                                    <option value="">{{ trans('common.all') }}</option>
                                                    @foreach(config('statuses.store_orders') as $status)
                                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ trans('store.order_statuses.'.$status) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="il-gray fs-14 fw-500 mb-10">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared">
                                                        <i class="uil uil-search"></i> {{ __('common.search') }}
                                                    </button>
                                                    <a href="{{ route('admin.store.orders.index') }}" class="btn btn-light btn-default btn-squared">
                                                        <i class="uil uil-redo"></i> {{ __('common.reset') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered table-striped">
                            <thead>
                                <tr class="userDatatable-header">
                                    <th width="5%"><span class="userDatatable-title">#</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('store.order_number') }}</span></th>
                                    <th width="15%"><span class="userDatatable-title">{{ trans('store.lawyer') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('store.order_total') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('common.status') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ trans('common.created_at') }}</span></th>
                                    <th width="10%"><span class="userDatatable-title">{{ __('common.actions') }}</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $index => $order)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <div class="userDatatable-content">
                                                <strong>{{ $orders->firstItem() + $index }}</strong>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $order->order_number }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                {{ $order->lawyer?->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                <strong style="color: #27ae60;">{{ number_format($order->total, 2) }} EGP</strong>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <select class="form-select form-select-sm order-status-select" data-order-id="{{ $order->id }}" style="width: 150px;">
                                                @foreach(config('statuses.store_orders') as $status)
                                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                        {{ trans('store.order_statuses.'.$status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="align-middle">
                                            <div class="userDatatable-content">
                                                <small class="text-muted">{{ $order->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.store.orders.show', $order->id) }}" class="view" title="{{ trans('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.store.orders.edit', $order->id) }}" class="edit" title="{{ trans('common.edit') }}">
                                                        <i class="uil uil-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                       class="remove"
                                                       title="{{ trans('common.delete') }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modal-delete-order"
                                                       data-item-id="{{ $order->id }}"
                                                       data-item-name="{{ $order->order_number }}">
                                                        <i class="uil uil-trash-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="uil uil-shopping-cart" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">{{ trans('store.no_orders_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($orders->hasPages())
                        <div class="d-flex justify-content-end mt-25">
                            {{ $orders->appends(request()->all())->links('vendor.pagination.custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal
        modalId="modal-delete-order"
        title="{{ trans('store.confirm_delete_order') }}"
        message="{{ trans('store.delete_confirmation_message_order') }}"
        itemNameId="delete-order-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.store.orders.index')"
        cancelText="{{ trans('common.cancel') }}"
        deleteText="{{ trans('common.delete') }}"
    />
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.order-status-select').on('change', function() {
            let orderId = $(this).data('order-id');
            let status = $(this).val();
            let url = "{{ route('admin.store.orders.update-status', ':id') }}".replace(':id', orderId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.message, 'check-circle');
                    } else {
                        showMessage('danger', response.message, 'check-circle');
                    }
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An error occurred.';
                    toastr.error(errorMessage);
                }
            });
        });
    });
</script>
@endpush

