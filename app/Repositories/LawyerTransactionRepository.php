<?php

namespace App\Repositories;

use App\Interfaces\LawyerTransactionRepositoryInterface;
use App\Models\Lawyer;
use App\Models\LawyerTransaction;
use Illuminate\Support\Collection;

class LawyerTransactionRepository implements LawyerTransactionRepositoryInterface
{
    /**
     * Get all transactions with filters
     */
    public function getAll(array $filters = [], int $perPage = 20)
    {
        $query = LawyerTransaction::with(['lawyer.user', 'appointment'])
            ->filter($filters);

        return $perPage > 0 ? $query->latest('transaction_date')->paginate($perPage) : $query->latest('transaction_date')->get();
    }

    /**
     * Get transaction by ID
     */
    public function getById(int $id): ?LawyerTransaction
    {
        return LawyerTransaction::with(['lawyer.user', 'appointment'])->find($id);
    }

    /**
     * Get transactions for a specific lawyer
     */
    public function getByLawyer(int $lawyerId, array $filters = [], int $perPage = 20)
    {
        $filters['lawyer_id'] = $lawyerId;
        return $this->getAll($filters, $perPage);
    }

    /**
     * Create a new transaction
     */
    public function create(array $data): LawyerTransaction
    {
        return LawyerTransaction::create($data);
    }

    /**
     * Update a transaction
     */
    public function update(LawyerTransaction $transaction, array $data): LawyerTransaction
    {
        $transaction->update($data);
        return $transaction->fresh();
    }

    /**
     * Delete a transaction
     */
    public function delete(LawyerTransaction $transaction): bool
    {
        return $transaction->delete();
    }

    /**
     * Get financial stats for a lawyer
     */
    public function getLawyerStats(int $lawyerId, string $dateFrom, string $dateTo): array
    {
        $income = LawyerTransaction::where('lawyer_id', $lawyerId)
            ->income()
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $expenses = LawyerTransaction::where('lawyer_id', $lawyerId)
            ->expense()
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->sum('amount');

        $profit = $income - $expenses;

        $appointmentsCount = \App\Models\Appointment::where('lawyer_id', $lawyerId)
            ->whereBetween('appointment_date', [$dateFrom, $dateTo])
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();

        return [
            'income' => $income,
            'expenses' => $expenses,
            'profit' => $profit,
            'appointments_count' => $appointmentsCount,
        ];
    }

    /**
     * Get financial stats for all lawyers
     */
    public function getAllLawyersStats(string $dateFrom, string $dateTo, ?int $lawyerId = null): Collection
    {
        $lawyersQuery = Lawyer::with('user')->where('active', true);

        if ($lawyerId) {
            $lawyersQuery->where('id', $lawyerId);
        }

        return $lawyersQuery->get()->map(function($lawyer) use ($dateFrom, $dateTo) {
            $stats = $this->getLawyerStats($lawyer->id, $dateFrom, $dateTo);

            return [
                'id' => $lawyer->id,
                'name' => $lawyer->getTranslation('name', app()->getLocale()),
                'email' => $lawyer->user?->email,
                'income' => $stats['income'],
                'expenses' => $stats['expenses'],
                'profit' => $stats['profit'],
                'appointments_count' => $stats['appointments_count'],
            ];
        });
    }

    /**
     * Get overall financial stats
     */
    public function getOverallStats(string $dateFrom, string $dateTo, ?int $lawyerId = null): array
    {
        $incomeQuery = LawyerTransaction::income()
            ->whereBetween('transaction_date', [$dateFrom, $dateTo]);
        
        $expensesQuery = LawyerTransaction::expense()
            ->whereBetween('transaction_date', [$dateFrom, $dateTo]);

        // Filter by lawyer if provided
        if ($lawyerId) {
            $incomeQuery->where('lawyer_id', $lawyerId);
            $expensesQuery->where('lawyer_id', $lawyerId);
        }

        $income = $incomeQuery->sum('amount');
        $expenses = $expensesQuery->sum('amount');
        $profit = $income - $expenses;

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'total_profit' => $profit,
        ];
    }
}
