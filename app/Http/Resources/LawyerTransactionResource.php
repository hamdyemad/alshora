<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerTransactionResource extends JsonResource
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
            'lawyer_id' => $this->lawyer_id,
            'appointment_id' => $this->appointment_id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            'transaction_date' => $this->transaction_date->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'appointment' => $this->whenLoaded('appointment', function() {
                return [
                    'id' => $this->appointment->id,
                    'appointment_date' => $this->appointment->appointment_date,
                    'status' => $this->appointment->status,
                ];
            }),
            'can_edit' => !$this->appointment_id,
            'can_delete' => !$this->appointment_id,
        ];
    }
}
