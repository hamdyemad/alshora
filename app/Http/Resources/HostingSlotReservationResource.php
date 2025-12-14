<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostingSlotReservationResource extends JsonResource
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
            'lawyer' => new LawyerResource($this->whenLoaded('lawyer')),
            'hosting_time' => [
                'id' => $this->hostingTime->id,
                'day' => $this->hostingTime->day,
                'from_time' => $this->hostingTime->from_time,
                'to_time' => $this->hostingTime->to_time,
            ],
            'status' => $this->status,
            'reason' => $this->reason,
            'admin_notes' => $this->admin_notes,
            'approved_by' => $this->whenLoaded('approvedBy', new UserResource($this->approvedBy)),
            'approved_at' => $this->approved_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
