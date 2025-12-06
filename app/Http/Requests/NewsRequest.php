<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'details_en' => 'required|string',
            'details_ar' => 'required|string',
            'source_en' => 'nullable|string|max:255',
            'source_ar' => 'nullable|string|max:255',
            'source_link' => 'nullable|url|max:500',
            'date' => 'required|date',
            'active' => 'boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title_en' => 'Title (English)',
            'title_ar' => 'Title (Arabic)',
            'details_en' => 'Details (English)',
            'details_ar' => 'Details (Arabic)',
            'source_en' => 'Source (English)',
            'source_ar' => 'Source (Arabic)',
            'source_link' => 'Source Link',
            'date' => 'Date',
            'active' => 'Status',
        ];
    }
}
