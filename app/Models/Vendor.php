<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, Translation;

    protected $guarded = [];


    /**
     * Get the user that owns the vendor
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the country
     */
    public function country()
    {
        return $this->belongsTo(\App\Models\Areas\Country::class);
    }

    /**
     * Get the activity (single - for backward compatibility)
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Get the vendor's activities (many-to-many)
     */
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'vendors_activities', 'vendor_id', 'activity_id');
    }
    
    /**
     * Alias for attachments relationship
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}
