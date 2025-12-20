<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreProduct extends Model
{
    use HasFactory, Translation, SoftDeletes;

    protected $table = 'store_products';

    protected $fillable = [
        'category_id',
        'image',
        'price',
        'active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
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
     * Get description attribute based on locale
     */
    public function getDescriptionAttribute()
    {
        return $this->getTranslation('description', app()->getLocale());
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Relationship with category
     */
    public function category()
    {
        return $this->belongsTo(StoreCategory::class, 'category_id');
    }

    /**
     * Relationship with order items
     */
    public function orderItems()
    {
        return $this->hasMany(StoreOrderItem::class, 'product_id');
    }

    /**
     * Relationship with cart items
     */
    public function cartItems()
    {
        return $this->hasMany(StoreCart::class, 'product_id');
    }
}
