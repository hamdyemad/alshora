<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use Translation, SoftDeletes;
    
    protected $table = 'activities';
    protected $guarded = [];
}
