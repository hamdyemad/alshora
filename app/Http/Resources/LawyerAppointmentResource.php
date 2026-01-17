<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LawyerAppointmentResource extends JsonResource
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
            'consultation_price' => $this->consultation_price,
            'notes' => $this->notes,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'customer' => $this->whenLoaded('customer', function() {
                $logo = null;
                if ($this->customer->relationLoaded('attachments')) {
                    $logoAttachment = $this->customer->attachments->where('type', 'logo')->first();
                    if ($logoAttachment) {
                        $logo = \Illuminate\Support\Facades\Storage::disk('public')->url($logoAttachment->path);
                    }
                }
                
                return [
                    'id' => $this->customer->id,
                    'name' => $this->customer->getTranslation('name', app()->getLocale()),
                    'name_en' => $this->customer->getTranslation('name', 'en'),
                    'name_ar' => $this->customer->getTranslation('name', 'ar'),
                    'email' => $this->customer->user ? $this->customer->user->email : null,
                    'phone' => $this->customer->phone,
                    'address' => $this->customer->address,
                    'logo' => $logo,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
