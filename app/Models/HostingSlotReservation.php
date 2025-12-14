<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostingSlotReservation extends Model
{
    protected $table = 'hosting_slot_reservations';

    protected $fillable = [
        'lawyer_id',
        'hosting_time_id',
        'status',
        'reason',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the lawyer that made the reservation
     */
    public function lawyer(): BelongsTo
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * Get the hosting time slot
     */
    public function hostingTime(): BelongsTo
    {
        return $this->belongsTo(HostingTime::class);
    }

    /**
     * Get the admin who approved the reservation
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope to get pending reservations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved reservations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected reservations
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
