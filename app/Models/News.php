<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'news';
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'active' => 'boolean',
    ];
}
