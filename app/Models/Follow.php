<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user (follower)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the followed lawyer
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }
}
