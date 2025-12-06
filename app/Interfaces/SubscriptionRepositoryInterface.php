<?php

namespace App\Interfaces;

use App\Models\Subscription;

interface SubscriptionRepositoryInterface
{
    public function getAllSubscriptions(array $filters = [], int $perPage = 15);
    public function getSubscriptionById(int $id): ?Subscription;
    public function createSubscription(array $data): Subscription;
    public function updateSubscription(int $id, array $data): Subscription;
    public function deleteSubscription(int $id): bool;
    public function getActiveSubscriptions();
}
