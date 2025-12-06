<?php

namespace App\Services;

use App\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SubscriptionService
{
    protected $subscriptionRepository;

    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Get all subscriptions with filters and pagination
     */
    public function getAllSubscriptions(array $filters = [], int $perPage = 15)
    {
        try {
            return $this->subscriptionRepository->getAllSubscriptions($filters, $perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching subscriptions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get subscription by ID
     */
    public function getSubscriptionById(int $id)
    {
        try {
            return $this->subscriptionRepository->getSubscriptionById($id);
        } catch (\Exception $e) {
            Log::error('Error fetching subscription: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a new subscription with translations
     */
    public function createSubscription(array $data)
    {
        try {
            // Prepare data
            $preparedData = $this->prepareSubscriptionData($data);
            
            return $this->subscriptionRepository->createSubscription($preparedData);
        } catch (\Exception $e) {
            Log::error('Error creating subscription: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update subscription with translations
     */
    public function updateSubscription(int $id, array $data)
    {
        try {
            // Prepare data
            $preparedData = $this->prepareSubscriptionData($data);
            
            return $this->subscriptionRepository->updateSubscription($id, $preparedData);
        } catch (\Exception $e) {
            Log::error('Error updating subscription: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete subscription
     */
    public function deleteSubscription(int $id)
    {
        try {
            return $this->subscriptionRepository->deleteSubscription($id);
        } catch (\Exception $e) {
            Log::error('Error deleting subscription: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get active subscriptions
     */
    public function getActiveSubscriptions()
    {
        try {
            return $this->subscriptionRepository->getActiveSubscriptions();
        } catch (\Exception $e) {
            Log::error('Error fetching active subscriptions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Prepare subscription data for storage
     */
    private function prepareSubscriptionData(array $data): array
    {
        return [
            'translations' => $data['translations'] ?? [],
            'number_of_months' => $data['number_of_months'],
            'active' => $data['active'] ?? 0,
        ];
    }
}
