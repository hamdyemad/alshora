<?php

namespace App\Services;

use App\Interfaces\StoreProductRepositoryInterface;
use App\Models\StoreProduct;
use Illuminate\Support\Facades\Log;

class StoreProductService
{
    protected $productRepository;

    public function __construct(StoreProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->productRepository->getAllWithFilters($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getProductById(int $id)
    {
        try {
            return $this->productRepository->getProductById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createProduct(array $data)
    {
        try {
            return $this->productRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateProduct(StoreProduct $product, array $data)
    {
        try {
            return $this->productRepository->update($product, $data);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProduct(StoreProduct $product)
    {
        try {
            return $this->productRepository->delete($product);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            throw $e;
        }
    }
}

