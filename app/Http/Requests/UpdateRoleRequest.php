<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class UpdateRoleRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permessions,id'
        ];

        // Add dynamic validation rules for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $rules['name_' . $language->code] = 'required|string|max:255';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];
        
        $languages = Language::all();
        foreach ($languages as $language) {
            $attributes['name_' . $language->code] = __('Role Name') . ' (' . $language->name . ')';
        }

        return $attributes;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'permissions.array' => __('Permissions must be an array'),
            'permissions.*.exists' => __('Selected permission is invalid'),
        ];
    }
}
