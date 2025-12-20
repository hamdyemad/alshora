<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreCartResource;
use App\Models\StoreCart;
use App\Services\StoreCartService;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreCartController extends Controller
{
    use Res;

    public function __construct(
        protected StoreCartService $cartService
    ) {
    }

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $userId = $user ? $user->id : null;
            $customerId = $user && $user->customer ? $user->customer->id : null;

            $cart = $this->cartService->getCart($userId, $customerId);

            $total = $cart->sum(function($item) {
                return $item->subtotal;
            });

            return $this->sendRes(
                __('common.success'),
                true,
                [
                    'items' => StoreCartResource::collection($cart),
                    'total' => $total,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error fetching cart: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:store_products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $user = $request->user();
            $userId = $user ? $user->id : null;
            $customerId = $user && $user->customer ? $user->customer->id : null;

            if (!$userId && !$customerId) {
                return $this->sendRes(__('store.user_required'), false, [], [], 401);
            }

            $cart = $this->cartService->addToCart([
                'user_id' => $userId,
                'customer_id' => $customerId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);

            return $this->sendRes(
                __('store.added_to_cart'),
                true,
                [],
                [],
                201
            );
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = StoreCart::findOrFail($id);
            $cart = $this->cartService->updateCartItem($cart, [
                'quantity' => $request->quantity,
            ]);

            return $this->sendRes(
                __('store.cart_updated'),
                true,
                new StoreCartResource($cart)
            );
        } catch (\Exception $e) {
            Log::error('Error updating cart: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cart = StoreCart::findOrFail($id);
            $this->cartService->removeFromCart($cart);

            return $this->sendRes(
                __('store.removed_from_cart'),
                true,
                []
            );
        } catch (\Exception $e) {
            Log::error('Error removing from cart: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }

    public function clear(Request $request)
    {
        try {
            $user = $request->user();
            $userId = $user ? $user->id : null;
            $customerId = $user && $user->customer ? $user->customer->id : null;

            $this->cartService->clearCart($userId, $customerId);

            return $this->sendRes(
                __('store.cart_cleared'),
                true,
                []
            );
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return $this->sendRes($e->getMessage(), false, [], [], 500);
        }
    }
}

