<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchOfLaw extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'branches_of_laws';
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all attachments (images, files)
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get the main image attachment
     */
    public function getImageAttribute()
    {
        return $this->attachments()->where('type', 'image')->first();
    }

    /**
     * Get all laws for this branch
     */
    public function laws()
    {
        return $this->hasMany(Law::class);
    }
}
