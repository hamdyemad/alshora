<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreparerAgendaResource extends JsonResource
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
            'court_bailiffs' => $this->court_bailiffs,
            'paper_type' => $this->paper_type,
            'paper_delivery_date' => $this->paper_delivery_date?->format('Y-m-d'),
            'paper_number' => $this->paper_number,
            'session_date' => $this->session_date?->format('Y-m-d'),
            'client_name' => $this->client_name,
            'notes' => $this->notes,
            'datetime' => $this->datetime?->format('Y-m-d H:i:s'),
            'notification_days' => $this->notification_days,
            'is_notified' => $this->is_notified,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
