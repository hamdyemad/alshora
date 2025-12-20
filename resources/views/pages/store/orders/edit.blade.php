@extends('layout.app')
@section('title', trans('store.edit_order'))

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ trans('store.edit_order') }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ trans('common.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.store.orders.index') }}">{{ trans('store.orders') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('store.edit_order') }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.store.orders.index') }}" class="btn btn-light">
            <i class="las la-arrow-left"></i> {{ trans('common.back') }}
        </a>
    </div>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 d-block" role="alert">
                <strong class=" d-block">{{ trans('validation.error') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

    <form method="POST" action="{{ route('admin.store.orders.update', $order) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- LEFT SIDE --}}
            <div class="col-lg-8">

                {{-- Customer --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">{{ trans('store.lawyer_information') }}</h6>

                        <select name="lawyer_id" class="form-select">
                            <option value="">{{ trans('common.select_lawyer') }}</option>
                            @foreach(\App\Models\Lawyer::all() as $lawyer)
                                <option value="{{ $lawyer->id }}" {{ $order->lawyer_id == $lawyer->id ? 'selected' : '' }}>
                                    {{ $lawyer->name }} — {{ $lawyer->email }}
                                </option>
                            @endforeach
                        </select>

                        @error('lawyer_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- Product Search --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">{{ trans('store.add_products') }}</h6>

                        <input type="text"
                               id="product_search"
                               class="form-control form-control-lg"
                               placeholder="{{ trans('store.search_product_placeholder') }}">

                        <div class="table-responsive mt-4">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ trans('store.product') }}</th>
                                        <th width="120">{{ trans('store.quantity') }}</th>
                                        <th width="120">{{ trans('store.price') }}</th>
                                        <th width="120">{{ trans('store.total') }}</th>
                                        <th width="60"></th>
                                    </tr>
                                </thead>
                                <tbody id="order-items">
                                    @foreach($order->items as $item)
                                        <tr data-id="{{ $item->product_id }}">
                                            <td>
                                                <img src="{{ $item->product->image_url }}" width="40" class="rounded me-2">
                                                {{ $item->product->name }}
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" value="{{ $item->quantity }}" min="1">
                                            </td>
                                            <td class="price">{{ $item->price }}</td>
                                            <td class="line-total">{{ $item->subtotal }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove">
                                                    <i class="las la-trash m-0"></i>
                                                </button>
                                            </td>
                                            <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                            <input type="hidden" name="products[{{ $item->product_id }}][quantity]" class="hidden-qty" value="{{ $item->quantity }}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">{{ trans('store.order_notes') }}</h6>
                        <textarea name="notes"
                                  class="form-control"
                                  rows="4"
                                  placeholder="{{ trans('store.order_notes') }}">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="col-lg-4">

                <div class="card shadow-sm position-sticky" style="top:20px">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">{{ trans('store.order_summary') }}</h5>

                        <div class="mb-3">
                            <label for="status" class="form-label">{{ trans('store.order_status') }}</label>
                            <select class="form-select" id="status" name="status" required>
                                @foreach(config('statuses.store_orders') as $status)
                                    <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ trans('store.order_statuses.'.$status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ trans('store.subtotal') }}</span>
                            <input type="text" id="subtotal" name="subtotal" class="form-control text-end w-50" readonly>
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ trans('store.tax') }}</span>
                            <input type="number" step="0.01" id="tax" name="tax" class="form-control text-end w-50" value="{{ $order->tax }}">
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ trans('store.discount') }}</span>
                            <input type="number" step="0.01" id="discount" name="discount" class="form-control text-end w-50" value="{{ $order->discount }}">
                        </div>

                        <hr>

                        <div class="mb-4 d-flex justify-content-between align-items-center fw-bold fs-5">
                            <span>{{ trans('store.order_total') }}</span>
                            <input type="text" id="total" name="total" class="form-control text-end fw-bold w-50" readonly>
                        </div>

                        <div class="d-grid gap-2 d-flex justify-content-between">
                            <button class="btn btn-primary btn-lg btn-block">
                                <i class="las la-check"></i> {{ trans('common.update') }}
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    calculate();

    $('#product_search').autocomplete({
        source: "{{ route('admin.store.products.search') }}",
        minLength: 2,
        select: function (event, ui) {
            addProduct(ui.item);
            $(this).val('');
            return false;
        }
    }).data('ui-autocomplete')._renderItem = function(ul, item) {
        return $('<li class="list-group-item">')
            .append(`
                <div class="d-flex align-items-center">
                    <img src="${item.image_url}" class="me-2 rounded" width="45">
                    <div>
                        <div class="fw-bold">${item.name}</div>
                        <small class="text-muted">${item.price}</small>
                    </div>
                </div>
            `)
            .appendTo(ul);
    };

    function addProduct(product) {

        if ($(`tr[data-id="${product.id}"]`).length) {
            let qty = $(`tr[data-id="${product.id}"] .quantity`);
            qty.val(parseInt(qty.val()) + 1).trigger('change');
            return;
        }

        $('#order-items').append(`
            <tr data-id="${product.id}">
                <td>
                    <img src="${product.image_url}" width="40" class="rounded me-2">
                    ${product.name}
                </td>
                <td>
                    <input type="number" class="form-control quantity" value="1" min="1">
                </td>
                <td class="price">${product.price}</td>
                <td class="line-total">${product.price}</td>
                <td>
                    <button type="button" class="btn btn-danger remove">
                        <i class="las la-trash m-0"></i>
                    </button>
                </td>
                <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                <input type="hidden" name="products[${product.id}][quantity]" class="hidden-qty" value="1">
            </tr>
        `);

        calculate();
    }

    $(document).on('change', '.quantity', function () {
        let tr = $(this).closest('tr');
        let qty = parseInt($(this).val());
        let price = parseFloat(tr.find('.price').text());

        tr.find('.line-total').text((qty * price).toFixed(2));
        tr.find('.hidden-qty').val(qty);

        calculate();
    });

    $(document).on('click', '.remove', function () {
        $(this).closest('tr').remove();
        calculate();
    });

    $('#tax,#discount').on('input', calculate);

    function calculate() {
        let subtotal = 0;

        $('.line-total').each(function () {
            subtotal += parseFloat($(this).text());
        });

        let tax = parseFloat($('#tax').val()) || 0;
        let discount = parseFloat($('#discount').val()) || 0;

        $('#subtotal').val(subtotal.toFixed(2));
        $('#total').val((subtotal + tax - discount).toFixed(2));
    }

});
</script>
@endpush
