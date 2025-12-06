<?php

namespace App\Interfaces;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface
{
    /**
     * Get all customers with filters
     */
    public function getAll(array $filters = [], int $perPage = 10);

    /**
     * Get customer by ID
     */
    public function getCustomerById(int $id);

    /**
     * Create a new customer
     */
    public function create(array $data);

    /**
     * Update customer
     */
    public function update(Customer $customer, array $data);

    /**
     * Delete customer
     */
    public function delete(Customer $customer): bool;

    /**
     * Toggle customer active status
     */
    public function toggleActive(Customer $customer): bool;

    /**
     * Delete all attachments for a customer
     */
    public function deleteAllAttachments(Customer $customer): void;
}
