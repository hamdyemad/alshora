<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'appointment_date' => $this->appointment_date?->format('Y-m-d'),
            'day' => $this->day,
            'period' => $this->period,
            'time_slot' => $this->time_slot?->format('H:i'),
            'consultation_type' => $this->consultation_type,
            'notes' => $this->notes,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'lawyer' => $this->whenLoaded('lawyer', fn() => [
                'id' => $this->lawyer->id,
                'name' => $this->lawyer->getTranslation('name', app()->getLocale()),
                'name_en' => $this->lawyer->getTranslation('name', 'en'),
                'name_ar' => $this->lawyer->getTranslation('name', 'ar'),
                'phone' => $this->lawyer->phone,
                'consultation_price' => $this->lawyer->consultation_price,
                'profile_image' => $this->lawyer->profile_image 
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->lawyer->profile_image->path) 
                    : null,
            ]),
            'customer' => $this->whenLoaded('customer', fn() => [
                'id' => $this->customer->id,
                'name' => $this->customer->getTranslation('name', app()->getLocale()),
                'name_en' => $this->customer->getTranslation('name', 'en'),
                'name_ar' => $this->customer->getTranslation('name', 'ar'),
                'phone' => $this->customer->phone,
            ]),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
