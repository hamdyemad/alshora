<?php

namespace App\Http\Controllers;

use App\Models\StoreOrder;
use App\Services\StoreOrderService;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    protected $orderService;

    public function __construct(StoreOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'lawyer_id', 'status']);
        $orders = $this->orderService->getAllOrders($filters, 10);
        return view('pages.store.orders.index', compact('orders'));
    }

    public function show(StoreOrder $order)
    {
        return view('pages.store.orders.show', compact('order'));
    }

    public function create()
    {
        return view('pages.store.orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'nullable|exists:lawyers,id',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:store_products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        $data['status'] = 'new';

        $order = $this->orderService->createOrder($data);

        return redirect()->route('admin.store.orders.show', $order)
            ->with('success', trans('common.created_successfully'));
    }

    public function edit(StoreOrder $order)
    {
        return view('pages.store.orders.edit', compact('order'));
    }

    public function update(Request $request, StoreOrder $order)
    {
        $request->validate([
            'lawyer_id' => 'nullable|exists:lawyers,id',
            'status' => 'required|in:new,inprogress,delivered,canceled,returned',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:store_products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $this->orderService->updateOrder($order, $request->all());

        return redirect()->route('admin.store.orders.show', $order)
            ->with('success', trans('common.updated_successfully'));
    }

    public function destroy(StoreOrder $order)
    {
        $this->orderService->deleteOrder($order);

        return response()->json([
            'success' => true,
            'message' => trans('common.deleted_successfully'),
            'redirect' => route('admin.store.orders.index')
        ]);
    }

    public function updateStatus(Request $request, StoreOrder $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', config('statuses.store_orders'))
        ]);

        try {
            $this->orderService->updateOrder($order, ['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => trans('common.updated_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

