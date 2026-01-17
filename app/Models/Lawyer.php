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
        'is_featured' => 'boolean',
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

    /**
     * Get the hosting slot reservations for the lawyer
     */
    public function hostingSlotReservations()
    {
        return $this->hasMany(HostingSlotReservation::class);
    }

    public function getNameAttribute() {
        return $this->getTranslation('name', app()->getLocale());
    }

    /**
     * Get all likes for the lawyer
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Get all dislikes for the lawyer
     */
    public function dislikes()
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }

    /**
     * Get all followers for the lawyer
     */
    public function followers()
    {
        return $this->hasMany(Follow::class);
    }

    /**
     * Check if the lawyer is liked by a specific user
     */
    public function isLikedBy($userId): bool
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Check if the lawyer is disliked by a specific user
     */
    public function isDislikedBy($userId): bool
    {
        return $this->dislikes()->where('user_id', $userId)->exists();
    }

    /**
     * Check if the lawyer is followed by a specific user
     */
    public function isFollowedBy($userId): bool
    {
        return $this->followers()->where('user_id', $userId)->exists();
    }

    /**
     * Get all appointments for the lawyer
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get all transactions for the lawyer
     */
    public function transactions()
    {
        return $this->hasMany(LawyerTransaction::class);
    }

    /**
     * Scope for applying filters
     */
    public function scopeFilter($query, array $filters)
    {
        // Apply search filter (name or email)
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('translations', function($q) use ($search) {
                    $q->where('lang_value', 'like', "%{$search}%")
                      ->whereIn('lang_key', ['name']);
                })->orWhereHas('user', function($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%");
                });
            });
        }

        // Apply active filter
        if (isset($filters['active']) && $filters['active'] !== '') {
            $query->where('active', $filters['active']);
        }

        // Apply section of law filter
        if (isset($filters['section_of_law_id']) && $filters['section_of_law_id'] !== '') {
            $query->whereHas('sectionsOfLaws', function ($q) use ($filters) {
                $q->where('section_of_law_id', $filters['section_of_law_id']);
            });
        }

        // Apply date from filter
        if (!empty($filters['created_date_from'])) {
            $query->whereDate('date', '>=', $filters['created_date_from']);
        }

        // Apply date to filter
        if (!empty($filters['created_date_to'])) {
            $query->whereDate('date', '<=', $filters['created_date_to']);
        }

        return $query;
    }
}
