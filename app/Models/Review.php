<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lawyer_id',
        'customer_id',
        'rating',
        'comment',
        'approved',
    ];

    protected $casts = [
        'rating' => 'integer',
        'approved' => 'boolean',
    ];

    /**
     * Get the lawyer that owns the review
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * Get the customer that created the review
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope to get only approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope to get only pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Get average rating for a lawyer
     */
    public static function getAverageRating($lawyerId)
    {
        return self::where('lawyer_id', $lawyerId)
            ->approved()
            ->avg('rating');
    }

    /**
     * Get total reviews count for a lawyer
     */
    public static function getTotalReviews($lawyerId)
    {
        return self::where('lawyer_id', $lawyerId)
            ->approved()
            ->count();
    }
}
