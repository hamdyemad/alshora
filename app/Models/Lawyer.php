<?php

namespace App\Models;

use App\Models\Areas\Country;
use App\Models\Areas\Region;
use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lawyer extends Model
{
    use Translation, SoftDeletes;

    protected $table = 'lawyers';
    protected $guarded = [];

    protected $casts = [
        'subscription_expires_at' => 'date',
        'ads_enabled' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Get the user that owns the lawyer profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the city
     */
    public function city()
    {
        return $this->belongsTo(\App\Models\Areas\City::class);
    }

    /**
     * Get the region
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the phone country
     */
    public function phoneCountry()
    {
        return $this->belongsTo(Country::class, 'phone_country_id');
    }

    /**
     * Get the lawyer's attachments (profile image, ID card, etc.)
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function RegisterGrade() {
        return $this->belongsTo(RegisterationGrades::class, 'degree_of_registration_id');
    }

    /**
     * Get profile image attachment
     */
    public function profile_image()
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('type', 'profile_image');
    }

    /**
     * Get ID card attachment
     */
    public function id_card()
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('type', 'id_card');
    }

    /**
     * Get ID card back attachment
     */
    public function id_card_back()
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('type', 'id_card_back');
    }

    /**
     * Get lawyer license card attachment
     */
    public function lawyer_license_card()
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('type', 'lawyer_license_card');
    }

    /**
     * Get the registration grade (degree of registration)
     */
    public function registrationGrade()
    {
        return $this->belongsTo(RegisterationGrades::class, 'degree_of_registration_id');
    }

    /**
     * Get the office hours for the lawyer
     */
    public function officeHours()
    {
        return $this->hasMany(LawyerOfficeHour::class);
    }

    /**
     * Get the sections of laws (specializations) for the lawyer
     */
    public function sectionsOfLaws()
    {
        return $this->belongsToMany(SectionOfLaw::class, 'lawyer_section_of_law');
    }

    /**
     * Get all reviews for the lawyer
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getNameAttribute() {
        return $this->getTranslation('name', app()->getLocale());
    }
}
