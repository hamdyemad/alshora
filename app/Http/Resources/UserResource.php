<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->user_type->name ?? 'user',
            'avatar' => null,
        ];

        // Try to get avatar from lawyer or customer profile
        if ($this->lawyer && $this->lawyer->profile_image) {
            $data['avatar'] = Storage::disk('public')->url($this->lawyer->profile_image->path);
            $data['name'] = $this->lawyer->getTranslation('name', app()->getLocale());
        } elseif ($this->customer && $this->customer->profile_image) {
            $data['avatar'] = Storage::disk('public')->url($this->customer->profile_image->path);
        }

        return $data;
    }
}
