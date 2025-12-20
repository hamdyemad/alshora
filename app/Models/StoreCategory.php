<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCategory extends Model
{
    use HasFactory, Translation, SoftDeletes;

    protected $table = 'store_categories';

    protected $fillable = [
        'image',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get name attribute based on locale
     */
    public function getNameAttribute()
    {
        return $this->getTranslation('name', app()->getLocale());
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Relationship with products
     */
    public function products()
    {
        return $this->hasMany(StoreProduct::class, 'category_id');
    }
}
