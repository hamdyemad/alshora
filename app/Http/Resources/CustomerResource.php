<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $logo = null;
        if ($this->relationLoaded('attachments')) {
            $logo = $this->attachments->where('type', 'logo')->first();
        }

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', app()->getLocale()) ?? '',
            'name_en' => $this->getTranslation('name', 'en') ?? '',
            'name_ar' => $this->getTranslation('name', 'ar') ?? '',
            'email' => ($this->user) ? $this->user->email : '',
            'user_type' => 'customer',
            'phone' => $this->phone,
            'phone_country' => new CountryResource($this->whenLoaded('phoneCountry')),
            'address' => $this->address,
            'city' => $this->whenLoaded('city', function() {
                return new CityResource($this->city);
            }),
            'region' => $this->whenLoaded('region', function() {
                return new RegionResource($this->region);
            }),
            'active' => $this->active,
            'logo' => $logo ? Storage::disk('public')->url($logo->path) : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
