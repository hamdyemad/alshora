<?php

namespace App\Http\Requests\AreaRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class SubRegionRequest extends FormRequest
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
        $subregionId = $this->route('subregion');
        $rules = [
            'region_id' => 'required|exists:regions,id',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [
            'region_id' => __('areas/subregion.region'),
        ];

        // Add dynamic attributes for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $attributes['translations.' . $language->id . '.name'] = __('areas/subregion.name_' . ($language->code == 'ar' ? 'arabic' : 'english'));
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
            'region_id.required' => __('areas/subregion.region_required'),
            'region_id.exists' => __('areas/subregion.region_exists'),
            'translations.required' => __('areas/subregion.translations_required'),
            'translations.*.name.required' => __('areas/subregion.name_required'),
            'translations.*.name.string' => __('areas/subregion.name_string'),
            'translations.*.name.max' => __('areas/subregion.name_max'),
        ];
    }
}
