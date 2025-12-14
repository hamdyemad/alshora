<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingTime extends Model
{
    protected $table = 'hosting_times';

    protected $fillable = [
        'day',
        'from_time',
        'to_time',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all days of the week
     */
    public static function getDays()
    {
        return [
            'saturday' => __('hosting.saturday'),
            'sunday' => __('hosting.sunday'),
            'monday' => __('hosting.monday'),
            'tuesday' => __('hosting.tuesday'),
            'wednesday' => __('hosting.wednesday'),
            'thursday' => __('hosting.thursday'),
            'friday' => __('hosting.friday'),
        ];
    }
}
