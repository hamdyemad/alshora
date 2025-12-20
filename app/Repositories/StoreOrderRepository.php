<?php

namespace App\Repositories;

use App\Interfaces\StoreOrderRepositoryInterface;
use App\Models\StoreOrder;
use App\Models\StoreOrderItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StoreOrderRepository implements StoreOrderRepositoryInterface
{
    public function getAllWithFilters(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = StoreOrder::with(['user', 'lawyer', 'items.product']);

        if (isset($filters['user_id']) && $filters['user_id']) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['lawyer_id']) && $filters['lawyer_id']) {
            $query->where('lawyer_id', $filters['lawyer_id']);
        }

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('lawyer', function ($q) use ($search) {
                      $q->whereHas('translations', function($a) use($search) {
                        $a->where('lang_value', 'like', "%$search%");
                      });
                  });
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getOrderById(int $id): StoreOrder
    {
        return StoreOrder::with(['user', 'lawyer', 'items.product'])->findOrFail($id);
    }

    public function create(array $data): StoreOrder
    {
        return DB::transaction(function () use ($data) {
            $order = StoreOrder::create([
                'user_id' => $data['user_id'] ?? auth()->user()?->id,
                'lawyer_id' => $data['lawyer_id'] ?? null,
                'status' => $data['status'] ?? 'new',
                'subtotal' => $data['subtotal'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'total' => $data['total'] ?? 0,
                'notes' => $data['notes'] ?? null,
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $product = \App\Models\StoreProduct::find($item['id']);
                    if($product) {
                        StoreOrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['id'],
                            'quantity' => $item['quantity'],
                            'price' => $product['price'],
                            'subtotal' => $item['quantity'] * $product['price'],
                        ]);
                    }
                }
            }

            return $order->fresh(['user', 'lawyer', 'items.product']);
        });
    }

    public function update(StoreOrder $order, array $data): StoreOrder
    {
        \Log::info('Updating order with data:', $data);
        return DB::transaction(function () use ($order, $data) {
            $updatedData = [];
            (isset($data['lawyer_id'])) ? $updatedData['lawyer_id'] = $data['lawyer_id'] : null;
            (isset($data['notes'])) ? $updatedData['notes'] = $data['notes'] : null;
            (isset($data['status'])) ? $updatedData['status'] = $data['status'] : null;
            (isset($data['subtotal'])) ? $updatedData['subtotal'] = $data['subtotal'] : null;
            (isset($data['tax'])) ? $updatedData['tax'] = $data['tax'] : null;
            (isset($data['discount'])) ? $updatedData['discount'] = $data['discount'] : null;
            (isset($data['total'])) ? $updatedData['total'] = $data['total'] : null;
            $order->update($updatedData);
            if (isset($data['products'])) {
                $productIds = collect($data['products'])->pluck('id')->all();
                // Delete items not in the new list
                $order->items()->whereNotIn('product_id', $productIds)->delete();

                foreach ($data['products'] as $productData) {
                    $product = \App\Models\StoreProduct::find($productData['id']);
                    if ($product) {
                        $order->items()->updateOrCreate(
                            ['product_id' => $productData['id']],
                            [
                                'quantity' => $productData['quantity'],
                                'price' => $product->price,
                                'subtotal' => $productData['quantity'] * $product->price,
                            ]
                        );
                    }
                }
            }

            return $order;
        });
    }

    public function delete(StoreOrder $order): bool
    {
        return DB::transaction(function () use ($order) {
            $order->items()->delete();
            return $order->delete();
        });
    }
}

