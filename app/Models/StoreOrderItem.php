<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreOrderItem extends Model
{
    use HasFactory;

    protected $table = 'store_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relationship with order
     */
    public function order()
    {
        return $this->belongsTo(StoreOrder::class, 'order_id');
    }

    /**
     * Relationship with product
     */
    public function product()
    {
        return $this->belongsTo(StoreProduct::class, 'product_id');
    }
}
