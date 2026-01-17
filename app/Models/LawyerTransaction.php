<?php

namespace App\Models;

use App\Enums\TransactionCategory;
use Illuminate\Database\Eloquent\Model;

class LawyerTransaction extends Model
{
    protected $fillable = [
        'lawyer_id',
        'appointment_id',
        'type',
        'amount',
        'category',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * Get all available categories
     */
    public static function getCategories(): array
    {
        return TransactionCategory::toArray();
    }

    /**
     * Get category values only
     */
    public static function getCategoryValues(): array
    {
        return TransactionCategory::values();
    }

    /**
     * Get the lawyer that owns the transaction
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * Get the appointment associated with the transaction
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Scope for income transactions
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope for expense transactions
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('transaction_date', [$from, $to]);
    }

    /**
     * Scope for applying filters
     */
    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['lawyer_id'])) {
            $query->where('lawyer_id', $filters['lawyer_id']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $filters['date_to']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query;
    }
}
