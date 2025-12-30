<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAgenda extends Model
{
    use SoftDeletes;

    protected $table = 'client_agendas';

    protected $fillable = [
        'user_id',
        'client_name',
        'client_phone',
        'client_inquiry',
        'follow_up_response',
        'follow_up_date',
        'notification_days',
        'is_notified',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'is_notified' => 'boolean',
    ];

    /**
     * Get the user that owns the client agenda
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
