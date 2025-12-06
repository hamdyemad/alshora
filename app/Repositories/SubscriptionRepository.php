<?php

namespace App\Repositories;

use App\Interfaces\SubscriptionRepositoryInterface;
use App\Models\Subscription;
use App\Models\Translation as TranslationModel;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    /**
     * Get all subscriptions with filters and pagination
     */
    public function getAllSubscriptions(array $filters = [], int $perPage = 15)
    {
        $query = Subscription::with('translations')
            ->filter($filters)
            ->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Get subscription by ID
     */
    public function getSubscriptionById(int $id): ?Subscription
    {
        return Subscription::with('translations')->findOrFail($id);
    }

    /**
     * Create a new subscription
     */
    public function createSubscription(array $data): Subscription
    {
        $subscription = Subscription::create([
            'number_of_months' => $data['number_of_months'],
            'active' => $data['active'] ?? 1,
        ]);

        // Save translations
        if (isset($data['translations'])) {
            $this->saveTranslations($subscription, $data['translations']);
        }

        return $subscription->load('translations');
    }

    /**
     * Update subscription
     */
    public function updateSubscription(int $id, array $data): Subscription
    {
        $subscription = $this->getSubscriptionById($id);

        $subscription->update([
            'number_of_months' => $data['number_of_months'],
            'active' => $data['active'] ?? 0,
        ]);

        // Update translations
        if (isset($data['translations'])) {
            // Delete old translations
            $subscription->translations()->delete();
            // Save new translations
            $this->saveTranslations($subscription, $data['translations']);
        }

        return $subscription->load('translations');
    }

    /**
     * Delete subscription
     */
    public function deleteSubscription(int $id): bool
    {
        $subscription = $this->getSubscriptionById($id);
        return $subscription->delete();
    }

    /**
     * Get active subscriptions
     */
    public function getActiveSubscriptions()
    {
        return Subscription::with('translations')
            ->active()
            ->orderBy('number_of_months', 'asc')
            ->get();
    }

    /**
     * Save translations for a subscription
     */
    private function saveTranslations(Subscription $subscription, array $translations): void
    {
        foreach ($translations as $langId => $fields) {
            foreach ($fields as $key => $value) {
                TranslationModel::create([
                    'translatable_type' => Subscription::class,
                    'translatable_id' => $subscription->id,
                    'lang_id' => $langId,
                    'lang_key' => $key,
                    'lang_value' => $value,
                ]);
            }
        }
    }
}
