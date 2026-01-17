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
            'consultation_price' => $this->consultation_price,
            'notes' => $this->notes,
            'status' => $this->status,
            'cancellation_reason' => $this->cancellation_reason,
            'customer' => $this->whenLoaded('customer', new CustomerResource($this->customer)),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
