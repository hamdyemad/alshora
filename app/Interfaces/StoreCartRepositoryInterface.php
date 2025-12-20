<?php

namespace App\Interfaces;

use App\Models\StoreCart;
use Illuminate\Support\Collection;

interface StoreCartRepositoryInterface
{
    public function getCartByUserId(int $userId): Collection;
    public function getCartByCustomerId(int $customerId): Collection;
    public function addToCart(array $data): StoreCart;
    public function updateCartItem(StoreCart $cart, array $data): StoreCart;
    public function removeFromCart(StoreCart $cart): bool;
    public function clearCart(int $userId = null, int $customerId = null): bool;
}

