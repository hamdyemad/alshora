<?php

namespace App\Interfaces;

use App\Models\Lawyer;
use App\Models\LawyerTransaction;
use Illuminate\Support\Collection;

interface LawyerTransactionRepositoryInterface
{
    /**
     * Get all transactions with filters
     */
    public function getAll(array $filters = [], int $perPage = 20);

    /**
     * Get transaction by ID
     */
    public function getById(int $id): ?LawyerTransaction;

    /**
     * Get transactions for a specific lawyer
     */
    public function getByLawyer(int $lawyerId, array $filters = [], int $perPage = 20);

    /**
     * Create a new transaction
     */
    public function create(array $data): LawyerTransaction;

    /**
     * Update a transaction
     */
    public function update(LawyerTransaction $transaction, array $data): LawyerTransaction;

    /**
     * Delete a transaction
     */
    public function delete(LawyerTransaction $transaction): bool;

    /**
     * Get financial stats for a lawyer
     */
    public function getLawyerStats(int $lawyerId, string $dateFrom, string $dateTo): array;

    /**
     * Get financial stats for all lawyers
     */
    public function getAllLawyersStats(string $dateFrom, string $dateTo, ?int $lawyerId = null): Collection;

    /**
     * Get overall financial stats
     */
    public function getOverallStats(string $dateFrom, string $dateTo, ?int $lawyerId = null): array;
}
