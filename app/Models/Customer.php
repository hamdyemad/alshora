<?php

namespace App\Models;

use App\Models\Areas\City;
use App\Models\Areas\Country;
use App\Models\Areas\Region;
use App\Traits\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use SoftDeletes, Translation;

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Translatable attributes
     */
    protected array $translatable = ['name'];

    /**
     * Get the associated user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the phone country
     */
    public function phoneCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_country_id');
    }

    /**
     * Get the city
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the region
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the logo attachment
     */
    public function logo()
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('type', 'logo');

    }

    /**
     * Get all attachments
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopeFilter(Builder $query, $filters) {
        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('translations', function($qt) use ($search) {
                    $qt->where('lang_value', 'like', "%{$search}%")
                       ->where('lang_key', 'name');
                })
                ->orWhereHas('user', function($qu) use ($search) {
                    $qu->where('email', 'like', "%{$search}%");
                })
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Apply city filter
        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        }

        // Apply region filter
        if (!empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }

        // Apply date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_date_from']);
        }

        // Apply date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_date_to']);
        }
    }
}
