<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
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
            'action_number' => $this->action_number,
            'years' => $this->years,
            'action_subject' => $this->action_subject,
            'court' => $this->court,
            'district_number' => $this->district_number,
            'details' => $this->details,
            'claiment_name' => $this->claiment_name,
            'defendant_name' => $this->defendant_name,
            'datetime' => $this->datetime?->format('Y-m-d H:i:s'),
            'notification_days' => $this->notification_days,
            'is_notified' => $this->is_notified,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
