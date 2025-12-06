<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'users_types';
    protected $guarded = [];

    const SUPER_ADMIN_TYPE = 1;
    const ADMIN_TYPE = 2;
    const LAWYER_TYPE = 3;
    const CUSTOMER_TYPE = 4;
}
