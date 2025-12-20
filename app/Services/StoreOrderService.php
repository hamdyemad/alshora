<?php

namespace App\Services;

use App\Interfaces\StoreOrderRepositoryInterface;
use App\Models\StoreOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StoreOrderService
{
    protected $orderRepository;

    public function __construct(StoreOrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->orderRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getOrderById(int $id)
    {
        try {
            return $this->orderRepository->getOrderById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createOrder(array $data)
    {
        Log::info('Creating order with data:', $data);
        try {
            $orderData = [
                'lawyer_id' => $data['lawyer_id'] ?? null,
                'status' => $data['status'] ?? 'new',
                'subtotal' => $data['subtotal'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'total' => $data['total'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'items' => $data['products'],
            ];

            $order = $this->orderRepository->create($orderData);
            return $order;
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateOrder(StoreOrder $order, array $data)
    {
        try {
            $order = $this->orderRepository->update($order, $data);
            return $order;
        } catch (\Exception $e) {
            Log::error('Error creating order: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteOrder(StoreOrder $order)
    {
        try {
            return $this->orderRepository->delete($order);
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            throw $e;
        }
    }
}

