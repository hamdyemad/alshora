<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCart extends Model
{
    use HasFactory;

    protected $table = 'store_cart';

    protected $fillable = [
        'user_id',
        'customer_id',
        'product_id',
        'quantity'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relationship with product
     */
    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'product_id');
    }

    /**
     * Calculate subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * ($this->product ? $this->product->price : 0);
    }
}
