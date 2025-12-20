<?php

namespace App\Services;

use App\Interfaces\StoreCartRepositoryInterface;
use App\Models\StoreCart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class StoreCartService
{
    protected $cartRepository;

    public function __construct(StoreCartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getCart(int $userId = null, int $customerId = null): Collection
    {
        try {
            if ($userId) {
                return $this->cartRepository->getCartByUserId($userId);
            }
            if ($customerId) {
                return $this->cartRepository->getCartByCustomerId($customerId);
            }
            return collect();
        } catch (\Exception $e) {
            Log::error('Error fetching cart: ' . $e->getMessage());
            throw $e;
        }
    }

    public function addToCart(array $data)
    {
        try {
            return $this->cartRepository->addToCart($data);
        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateCartItem(StoreCart $cart, array $data)
    {
        try {
            return $this->cartRepository->updateCartItem($cart, $data);
        } catch (\Exception $e) {
            Log::error('Error updating cart item: ' . $e->getMessage());
            throw $e;
        }
    }

    public function removeFromCart(StoreCart $cart)
    {
        try {
            return $this->cartRepository->removeFromCart($cart);
        } catch (\Exception $e) {
            Log::error('Error removing from cart: ' . $e->getMessage());
            throw $e;
        }
    }

    public function clearCart(int $userId = null, int $customerId = null)
    {
        try {
            return $this->cartRepository->clearCart($userId, $customerId);
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            throw $e;
        }
    }
}

