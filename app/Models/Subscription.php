<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'subscriptions';
    protected $guarded = [];

    protected $casts = [
        'number_of_months' => 'integer',
        'active' => 'boolean',
    ];

    public function scopeActive(Builder $query)
    {
        $query->where('active', 1);
    }

    public function scopeFilter(Builder $query, $filters)
    {
        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('translations', function($q) use ($search) {
                    $q->where('lang_value', 'like', "%{$search}%");
                })
                ->orWhere('number_of_months', 'like', "%{$search}%");
            });
        }

        // Status filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_date_from']);
        }

        // Date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_date_to']);
        }
    }
}
