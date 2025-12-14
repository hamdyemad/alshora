<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DraftingContractResource extends JsonResource
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
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'file_en' => ($this->file_en) ? asset('storage/' . $this->file_en) : '',
            'file_ar' => ($this->file_ar) ? asset('storage/' . $this->file_ar) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
