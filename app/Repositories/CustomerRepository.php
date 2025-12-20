<?php

namespace App\Repositories;

use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Models\User;
use App\Models\UserType;
use App\Models\Attachment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Get all customers with filters and pagination
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = Customer::with(['user', 'phoneCountry', 'city', 'region', 'attachments'])
        ->filter($filters)->latest();
        return ($perPage == 0) ? $query->get() : $query->paginate($perPage);
    }

    /**
     * Get customer by ID with relations
     */
    public function getCustomerById(int $id)
    {
        return Customer::with(['user', 'phoneCountry', 'city', 'region', 'attachments'])->findOrFail($id);
    }

    /**
     * Create a new customer
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create user first
            $user = User::create([
                'uuid' => Str::uuid(),
                'user_type_id' => UserType::CUSTOMER_TYPE,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Prepare customer data
            $customerData = [
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'phone_country_id' => $data['phone_country_id'],
                'address' => $data['address'] ?? null,
                'city_id' => $data['city_id'] ?? null,
                'region_id' => $data['region_id'] ?? null,
                'active' => $data['active'] ?? true,
            ];

            $customer = Customer::create($customerData);

            // Set name translations
            if (isset($data['name_en'])) {
                $customer->setTranslation('name', 'en', $data['name_en']);
            }

            if (isset($data['name_ar'])) {
                $customer->setTranslation('name', 'ar', $data['name_ar']);
            }
            // For simple name field (non-API usage)
            if (isset($data['name']) && !isset($data['name_en'])) {
                $customer->name = $data['name'];
                $customer->save();
            }

            // Handle logo upload
            if (isset($data['logo'])) {
                $this->handleLogoUpload($customer, $data['logo']);
            }

            return $customer->load(['user', 'phoneCountry', 'city', 'region', 'attachments']);
        });
    }

    /**
     * Update customer
     */
    public function update(Customer $customer, array $data)
    {
        return DB::transaction(function () use ($customer, $data) {
            // Update user email if provided
            if (isset($data['email']) && $customer->user) {
                $customer->user->update([
                    'email' => $data['email'],
                ]);
            }

            (isset($data['phone'])) ? $customer->phone = $data['phone'] : null;
            (isset($data['phone_country_id'])) ? $customer->phone_country_id = $data['phone_country_id'] : null;
            (isset($data['address'])) ? $customer->address = $data['address'] : null;
            (isset($data['city_id'])) ? $customer->city_id = $data['city_id'] : null;
            (isset($data['region_id'])) ? $customer->region_id = $data['region_id'] : null;
            (isset($data['active'])) ? $customer->active = true : $customer->active = false;

            // Update name translations
            if (isset($data['name_en'])) {
                $customer->setTranslation('name', 'en', $data['name_en']);
            }
            if (isset($data['name_ar'])) {
                $customer->setTranslation('name', 'ar', $data['name_ar']);
            }
            $customer->save();

            // Handle logo upload
            if (isset($data['logo'])) {
                // Delete old logo
                $this->deleteAttachmentsByType($customer, 'logo');
                // Upload new logo
                $this->handleLogoUpload($customer, $data['logo']);
            }

            return $customer->load(['user', 'phoneCountry', 'city', 'region', 'attachments']);
        });
    }

    /**
     * Delete customer
     */
    public function delete(Customer $customer): bool
    {
        return DB::transaction(function () use ($customer) {
            // Store user reference before deleting customer
            $user = $customer->user;

            // Delete all attachments
            $this->deleteAllAttachments($customer);

            // Delete customer first
            $customer->delete();

            // Then explicitly delete the user
            if ($user) {
                $user->delete();
            }

            return true;
        });
    }

    /**
     * Toggle customer active status
     */
    public function toggleActive(Customer $customer): bool
    {
        $customer->active = !$customer->active;
        return $customer->save();
    }

    /**
     * Delete all attachments for a customer
     */
    public function deleteAllAttachments(Customer $customer): void
    {
        foreach ($customer->attachments as $attachment) {
            $attachment->deleteFile();
            $attachment->delete();
        }
    }

    /**
     * Delete attachments by type
     */
    private function deleteAttachmentsByType(Customer $customer, string $type): void
    {
        $attachments = $customer->attachments()->where('type', $type)->get();
        foreach ($attachments as $attachment) {
            $attachment->deleteFile();
            $attachment->delete();
        }
    }

    /**
     * Handle logo upload
     */
    private function handleLogoUpload(Customer $customer, $file): void
    {
        $path = $file->store('customers/logos', 'public');

        Attachment::create([
            'path' => $path,
            'type' => 'logo',
            'attachable_type' => Customer::class,
            'attachable_id' => $customer->id,
        ]);
    }
}
