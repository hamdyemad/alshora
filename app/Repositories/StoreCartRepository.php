<?php

namespace App\Repositories;

use App\Interfaces\StoreCartRepositoryInterface;
use App\Models\StoreCart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StoreCartRepository implements StoreCartRepositoryInterface
{
    public function getCartByUserId(int $userId): Collection
    {
        return StoreCart::with(['product.category', 'product.translations'])
            ->where('user_id', $userId)
            ->get();
    }

    public function getCartByCustomerId(int $customerId): Collection
    {
        return StoreCart::with(['product.category', 'product.translations'])
            ->where('customer_id', $customerId)
            ->get();
    }

    public function addToCart(array $data): StoreCart
    {
        return DB::transaction(function () use ($data) {
            // Check if item already exists in cart
            $existingCart = StoreCart::where('product_id', $data['product_id'])
                ->where(function($query) use ($data) {
                    if (isset($data['user_id'])) {
                        $query->where('user_id', $data['user_id']);
                    }
                    if (isset($data['customer_id'])) {
                        $query->where('customer_id', $data['customer_id']);
                    }
                })
                ->first();

            if ($existingCart) {
                // Update quantity
                $existingCart->quantity += $data['quantity'] ?? 1;
                $existingCart->save();
                return $existingCart->fresh(['product']);
            }

            // Create new cart item
            return StoreCart::create([
                'user_id' => $data['user_id'] ?? null,
                'customer_id' => $data['customer_id'] ?? null,
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'] ?? 1,
            ])->load('product');
        });
    }

    public function updateCartItem(StoreCart $cart, array $data): StoreCart
    {
        $cart->update([
            'quantity' => $data['quantity'] ?? $cart->quantity,
        ]);

        return $cart->fresh(['product']);
    }

    public function removeFromCart(StoreCart $cart): bool
    {
        return $cart->delete();
    }

    public function clearCart(int $userId = null, int $customerId = null): bool
    {
        $query = StoreCart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        return $query->delete();
    }
}

