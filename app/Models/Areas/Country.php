<?php

namespace App\Models\Areas;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'countries';
    protected $guarded = [];

    public function cities() {
        return $this->hasMany(City::class, 'country_id');
    }


    public function scopeFilter(Builder $query, $filters) {
        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('translations', function($query) use ($search) {
                    $query->where('lang_value', 'like', "%{$search}%");
                })->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }
        // Default filter
        if (isset($filters['default']) && $filters['default'] !== '') {
            $query->where('default_country', $filters['default']);
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
