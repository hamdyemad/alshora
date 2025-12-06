<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionOfLawRequest extends FormRequest
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
        $rules = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'details_en' => 'required|string',
            'details_ar' => 'required|string',
            'active' => 'boolean',
        ];

        // Image is required only on create
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name_en' => 'Name (English)',
            'name_ar' => 'Name (Arabic)',
            'details_en' => 'Details (English)',
            'details_ar' => 'Details (Arabic)',
            'image' => 'Image',
            'active' => 'Status',
        ];
    }
}
