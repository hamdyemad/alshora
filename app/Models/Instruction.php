<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instruction extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'instructions';
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
    ];
}
