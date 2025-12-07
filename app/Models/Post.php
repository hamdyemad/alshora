<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = ['is_liked', 'likes_count', 'comments_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    
    public function getIsLikedAttribute()
    {
        if (auth('sanctum')->check()) {
            return $this->likes()->where('user_id', auth('sanctum')->id())->exists();
        }
        return false;
    }
    
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
    
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}
