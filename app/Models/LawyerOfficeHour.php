<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawyerOfficeHour extends Model
{
    protected $table = 'lawyer_office_hours';
    
    protected $guarded = [];


    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Get the lawyer that owns the office hour
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * Get all days of the week
     */
    public static function getDays()
    {
        return [
            'saturday' => __('lawyer.saturday'),
            'sunday' => __('lawyer.sunday'),
            'monday' => __('lawyer.monday'),
            'tuesday' => __('lawyer.tuesday'),
            'wednesday' => __('lawyer.wednesday'),
            'thursday' => __('lawyer.thursday'),
            'friday' => __('lawyer.friday'),
        ];
    }

    /**
     * Get all periods
     */
    public static function getPeriods()
    {
        return [
            'morning' => __('lawyer.morning'),
            'evening' => __('lawyer.evening'),
        ];
    }
}
