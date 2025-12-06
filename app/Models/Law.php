<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Law extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'laws';
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the branch of law that owns the law
     */
    public function branchOfLaw()
    {
        return $this->belongsTo(BranchOfLaw::class);
    }
}
