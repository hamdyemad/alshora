<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchOfLawRequest extends FormRequest
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
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
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
            'title_en' => 'Title (English)',
            'title_ar' => 'Title (Arabic)',
            'image' => 'Image',
            'active' => 'Status',
        ];
    }
}
