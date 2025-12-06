<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    protected $guarded = [];

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
