<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'user_id',
        'action_number',
        'years',
        'action_subject',
        'court',
        'district_number',
        'details',
        'claiment_name',
        'defendant_name',
        'datetime',
        'notification_days',
        'is_notified',
    ];

    protected $casts = [
        'datetime' => 'datetime',
        'is_notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
