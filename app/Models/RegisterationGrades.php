<?php

namespace App\Models;

use App\Traits\Translation;
use Illuminate\Database\Eloquent\Model;

class RegisterationGrades extends Model
{
    use Translation;
    
    protected $table = 'registration_grades';
    protected $guarded = [];
}
