<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'user' => new UserResource($this->whenLoaded('user')),
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count ?? 0,
            'comments_count' => $this->comments_count,
            'is_liked' => $this->is_liked,
            'is_disliked' => $this->is_disliked ?? false,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
