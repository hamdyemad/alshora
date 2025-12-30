<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Law extends Model
{
    use Translation, SoftDeletes;

    protected $table = 'laws';
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the branch of law that owns the law
     */
    public function branchOfLaw()
    {
        return $this->belongsTo(BranchOfLaw::class);
    }

    /**
     * Scope to search laws by title or description
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->whereHas('translations', function ($q) use ($search) {
            $q->where('lang_value', 'like', "%{$search}%")
              ->whereIn('lang_key', ['title', 'description']);
        });
    }

    function getTitleAttribute()
    {
        return $this->getTranslation('title', app()->getLocale());
    }

    function getDescriptionAttribute()
    {
        return $this->getTranslation('description', app()->getLocale());
    }
}
