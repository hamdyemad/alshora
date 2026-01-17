<?php

namespace App\Services;

use App\Interfaces\LawyerTransactionRepositoryInterface;
use App\Models\LawyerTransaction;

class LawyerTransactionService
{
    public function __construct(
        protected LawyerTransactionRepositoryInterface $transactionRepository
    ) {
    }

    /**
     * Get all transactions with filters
     */
    public function getAll(array $filters = [], int $perPage = 20)
    {
        return $this->transactionRepository->getAll($filters, $perPage);
    }

    /**
     * Get transaction by ID
     */
    public function getById(int $id): ?LawyerTransaction
    {
        return $this->transactionRepository->getById($id);
    }

    /**
     * Get transactions for a specific lawyer
     */
    public function getByLawyer(int $lawyerId, array $filters = [], int $perPage = 20)
    {
        return $this->transactionRepository->getByLawyer($lawyerId, $filters, $perPage);
    }

    /**
     * Create a new transaction
     */
    public function create(array $data): LawyerTransaction
    {
        return $this->transactionRepository->create($data);
    }

    /**
     * Update a transaction
     */
    public function update(LawyerTransaction $transaction, array $data): LawyerTransaction
    {
        // Don't allow updating transactions linked to appointments
        if ($transaction->appointment_id) {
            throw new \Exception(__('accounting.cannot_update_appointment_transaction'));
        }

        return $this->transactionRepository->update($transaction, $data);
    }

    /**
     * Delete a transaction
     */
    public function delete(LawyerTransaction $transaction): bool
    {
        // Don't allow deleting transactions linked to appointments
        if ($transaction->appointment_id) {
            throw new \Exception(__('accounting.cannot_delete_appointment_transaction'));
        }

        return $this->transactionRepository->delete($transaction);
    }

    /**
     * Get financial stats for a lawyer
     */
    public function getLawyerStats(int $lawyerId, string $dateFrom, string $dateTo): array
    {
        return $this->transactionRepository->getLawyerStats($lawyerId, $dateFrom, $dateTo);
    }

    /**
     * Get financial stats for all lawyers
     */
    public function getAllLawyersStats(string $dateFrom, string $dateTo, ?int $lawyerId = null)
    {
        return $this->transactionRepository->getAllLawyersStats($dateFrom, $dateTo, $lawyerId);
    }

    /**
     * Get overall financial stats
     */
    public function getOverallStats(string $dateFrom, string $dateTo, ?int $lawyerId = null): array
    {
        return $this->transactionRepository->getOverallStats($dateFrom, $dateTo, $lawyerId);
    }
}
