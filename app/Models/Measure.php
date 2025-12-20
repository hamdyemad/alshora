<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Measure extends Model
{
    use HasFactory, Translation, SoftDeletes;

    protected $fillable = [
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get title attribute based on locale
     */
    public function getTitleAttribute()
    {
        return $this->getTranslation('title', app()->getLocale());
    }

    /**
     * Get description attribute based on locale
     */
    public function getDescriptionAttribute()
    {
        return $this->getTranslation('description', app()->getLocale());
    }
}
