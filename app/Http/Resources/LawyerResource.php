<?php

namespace App\Http\Resources;

use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LawyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', app()->getLocale()) ?? '',
            'name_en' => $this->getTranslation('name', 'en') ?? '',
            'name_ar' => $this->getTranslation('name', 'ar') ?? '',
            'email' => ($this->user) ? $this->user->email : '',
            'user_type' => 'lawyer',
            'gender' => $this->gender,
            'phone' => $this->phone,
            'phone_country' => new CountryResource($this->phoneCountry),
            'address' => $this->address,
            'city' => $this->whenLoaded('city', new CityResource($this->city)),
            'region' => $this->whenLoaded('region', new RegionResource($this->region)),
            'consultation_price' => $this->consultation_price,
            'active' => $this->active,
            'is_featured' => $this->is_featured,
            'register_grade' => new RegisterGradeResource($this->RegisterGrade),
            'section_of_law' => $this->whenLoaded('sectionsOfLaws', SectionOfLawResource::collection($this->sectionsOfLaws)),
            'experience' => $this->getTranslation('experience', app()->getLocale()) ?? '',
            'experience_en' => $this->getTranslation('experience', 'en') ?? '',
            'experience_ar' => $this->getTranslation('experience', 'ar') ?? '',
            'latitude' => $this->latitude ?? '',
            'longitude' => $this->longitude ?? '',
            'officeHours' => $this->whenLoaded('officeHours', function() {
                return $this->officeHours->groupBy('period');
            }),
            'profile_image' => $this->when($this->relationLoaded('profile_image') && $this->profile_image, function() {
                return Storage::disk('public')->url($this->profile_image->path);
            }, ''),
            'id_card' => $this->when($this->relationLoaded('id_card') && $this->id_card, function() {
                return Storage::disk('public')->url($this->id_card->path);
            }, ''),
            'facebook_url' => $this->facebook_url ?? '',
            'twitter_url' => $this->twitter_url ?? '',
            'instagram_url' => $this->instagram_url ?? '',
            'telegram_url' => $this->telegram_url ?? '',
            'tiktok_url' => $this->tiktok_url ?? '',
            'fcm_token' => $this->user?->fcm_token ?? '',
            'subscription' => $this->subscription ? new SubscriptionResource($this->subscription) : null,
            'subscription_expires_at' => $this->subscription_expires_at?->format('Y-m-d'),
            'followers_count' => $this->followers()->count(),
            'is_followed_by_me' => auth()->check() ? $this->isFollowedBy(auth()->id()) : false,
            'likes_count' => $this->likes()->count(),
            'is_liked_by_me' => auth()->check() ? $this->isLikedBy(auth()->id()) : false,
            'dislikes_count' => $this->dislikes()->count(),
            'is_disliked_by_me' => auth()->check() ? $this->isDislikedBy(auth()->id()) : false,
            'average_rating' => round($this->reviews_avg_rating ?? 0, 2),
            'reviews_count' => $this->reviews_count ?? 0,
            'rating_statistics' => $this->when($this->relationLoaded('reviews'), function() {
                $ratingDistribution = $this->reviews()
                    ->where('approved', true)
                    ->selectRaw('rating, COUNT(*) as count')
                    ->groupBy('rating')
                    ->pluck('count', 'rating')
                    ->toArray();
                
                $totalReviews = array_sum($ratingDistribution);
                $averageRating = $totalReviews > 0 
                    ? $this->reviews()->where('approved', true)->avg('rating') 
                    : 0;

                return [
                    'average_rating' => round($averageRating ?? 0, 2),
                    'total_reviews' => $totalReviews,
                    'rating_distribution' => [
                        '5_stars' => $ratingDistribution[5] ?? 0,
                        '4_stars' => $ratingDistribution[4] ?? 0,
                        '3_stars' => $ratingDistribution[3] ?? 0,
                        '2_stars' => $ratingDistribution[2] ?? 0,
                        '1_star' => $ratingDistribution[1] ?? 0,
                    ]
                ];
            }),
            'reviews' => $this->when($this->relationLoaded('reviews'), function() {
                return ReviewResource::collection(
                    $this->reviews()->where('approved', true)->latest()->take(10)->get()
                );
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
