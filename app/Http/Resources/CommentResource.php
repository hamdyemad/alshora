<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => new UserResource($this->whenLoaded('user')),
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count ?? 0,
            'is_liked' => $this->is_liked,
            'is_disliked' => $this->is_disliked ?? false,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
