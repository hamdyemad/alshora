<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'lawyer_id',
        'appointment_date',
        'day',
        'period',
        'time_slot',
        'consultation_type',
        'consultation_price',
        'notes',
        'status',
        'cancellation_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'time_slot' => 'datetime:H:i',
        'consultation_price' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the appointment
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the lawyer that owns the appointment
     */
    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(Lawyer::class);
    }
}
