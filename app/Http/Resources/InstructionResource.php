<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructionResource extends JsonResource
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
            'title' => $this->getTranslation('title', app()->getLocale()) ?? '',
            'title_en' => $this->getTranslation('title', 'en') ?? '',
            'title_ar' => $this->getTranslation('title', 'ar') ?? '',
            'content' => $this->getTranslation('content', app()->getLocale()) ?? '',
            'content_en' => $this->getTranslation('content', 'en') ?? '',
            'content_ar' => $this->getTranslation('content', 'ar') ?? '',
            'active' => $this->active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
