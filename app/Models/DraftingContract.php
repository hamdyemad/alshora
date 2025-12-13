<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftingContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'file_en',
        'file_ar',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the name attribute based on current locale
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get the file attribute based on current locale
     */
    public function getFileAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->file_ar : $this->file_en;
    }

    /**
     * Get file URL for English version
     */
    public function getFileEnUrlAttribute()
    {
        return $this->file_en ? asset('storage/' . $this->file_en) : null;
    }

    /**
     * Get file URL for Arabic version
     */
    public function getFileArUrlAttribute()
    {
        return $this->file_ar ? asset('storage/' . $this->file_ar) : null;
    }
}
