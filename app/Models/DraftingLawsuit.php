<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftingLawsuit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        return $this->file ? asset('storage/' . $this->file) : null;
    }
}
