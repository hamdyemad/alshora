<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparerAgenda extends Model
{
    protected $fillable = [
        'user_id',
        'court_bailiffs',
        'paper_type',
        'paper_delivery_date',
        'paper_number',
        'session_date',
        'client_name',
        'notes',
        'datetime',
        'notification_days',
        'is_notified',
    ];

    protected $casts = [
        'paper_delivery_date' => 'date',
        'session_date' => 'date',
        'datetime' => 'datetime',
        'is_notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
