<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreOrderResource;
use App\Services\StoreOrderService;
use App\Services\StoreProductService;
use App\Services\StoreCartService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreOrderController extends Controller
{
    use Res;

    public function __construct(
        protected StoreOrderService $orderService,
        protected StoreProductService $productService,
        protected StoreCartService $cartService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->input('per_page', 15);
            $filters = [
                'user_id' => $user->id,
                'customer_id' => $user->customer?->id,
                'status' => $request->input('status'),
                'search' => $request->input('search'),
            ];

            $orders = $this->orderService->getAllOrders($filters, $perPage);

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => StoreOrderResource::collection($orders->items()),
                    'pagination' => [
                        'total' => $orders->total(),
                        'per_page' => $orders->perPage(),
                        'current_page' => $orders->currentPage(),
                        'last_page' => $orders->lastPage(),
                        'from' => $orders->firstItem(),
                        'to' => $orders->lastItem(),
                    ]
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return $this->sendRes(
                __('common.success'),
                true,
                new StoreOrderResource($order)
            );
        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();
            $cartItems = $this->cartService->getCart($user->id, $user->customer?->id);

            if ($cartItems->isEmpty()) {
                return $this->sendRes(__('store.cart_empty'), false, [], [], 400);
            }

            $items = [];
            $total = 0;

            foreach ($cartItems as $cartItem) {
                $items[] = [
                    'id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                ];
                $total += $cartItem->subtotal;
            }
            $order = $this->orderService->createOrder([
                'user_id' => $user->id,
                'customer_id' => $user->customer?->id,
                'products' => $items,
                'total' => $total,
                'notes' => $request->notes,
            ]);

            $this->cartService->clearCart($user->id, $user->customer?->id);

            return $this->sendRes(
                __('store.order_created'),
                true,
                new StoreOrderResource($order),
                [],
                201
            );
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}

