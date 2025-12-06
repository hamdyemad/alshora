<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionOfLaw extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'sections_of_laws';
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all attachments (images, files)
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get the main image attachment
     */
    public function getImageAttribute()
    {
        return $this->attachments()->where('type', 'image')->first();
    }

    /**
     * Get the lawyers associated with this section of law
     */
    public function lawyers()
    {
        return $this->belongsToMany(Lawyer::class, 'lawyer_section_of_law');
    }


    public function scopeActive(Builder $query) {
        $query->where('active', 1);
    }

    public function scopeFilter(Builder $query, $filters) {
        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('translations', function($q) use ($search) {
                $q->where('lang_value', 'like', "%{$search}%")
                  ->whereIn('lang_key', ['name', 'details']);
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
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
