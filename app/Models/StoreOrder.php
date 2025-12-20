<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StoreOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'store_orders';

    protected $fillable = [
        'user_id',
        'lawyer_id',
        'order_number',
        'status',
        'subtotal',
        'tax',
        'discount',
        'total',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Generate unique order number
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {

            // Get last numeric part
            $lastNumber = DB::table('store_orders')
                ->selectRaw("MAX(CAST(SUBSTRING(order_number, 9) AS UNSIGNED)) as max_number")
                ->value('max_number');

            $nextNumber = ($lastNumber ?? 0) + 1;

            $order->order_number = 'alshora-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with lawyer
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id');
    }

    /**
     * Relationship with order items
     */
    public function items()
    {
        return $this->hasMany(StoreOrderItem::class, 'order_id');
    }
}
