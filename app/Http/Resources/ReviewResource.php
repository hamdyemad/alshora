<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'customer_name' => $this->customer->name ?? 'Anonymous',
            'customer_email' => $this->customer->user->email ?? null,
            'approved' => $this->approved,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
