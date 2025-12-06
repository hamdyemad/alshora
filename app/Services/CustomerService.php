<?php

namespace App\Services;

use App\Actions\UserAction;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository,
        protected UserAction $userAction
    ) {
    }

    /**
     * Get all customers with filters
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        try {
            return $this->customerRepository->getAll($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get customer by ID
     */
    public function getCustomerById(int $id)
    {
        try {
            return $this->customerRepository->getCustomerById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching customer by ID: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new customer
     */
    public function createCustomer(array $data)
    {
        try {
            return $this->customerRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update customer
     */
    public function updateCustomer(Customer $customer, array $data)
    {
        try {
            return $this->customerRepository->update($customer, $data);
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete customer
     */
    public function deleteCustomer(Customer $customer): bool
    {
        try {
            return $this->customerRepository->delete($customer);
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Toggle customer active status
     */
    public function toggleActive(Customer $customer): bool
    {
        try {
            return $this->customerRepository->toggleActive($customer);
        } catch (\Exception $e) {
            Log::error('Error toggling customer active status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Register a new customer (API registration)
     */
    public function register(array $data)
    {
        try {
            return $this->customerRepository->create($data);
        } catch (\Exception $e) {
            Log::error('Error registering customer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Change customer password
     */
    public function changePassword($user, $data)
    {
        return $this->userAction->changePassword($user, $data);
    }
}
