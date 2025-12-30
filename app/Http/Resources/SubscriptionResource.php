<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'description' => $this->getTranslation('description', app()->getLocale()) ?? '',
            'number_of_months' => $this->number_of_months,
            'price' => $this->price ?? 0,
            'active' => $this->active,
        ];
    }
}
