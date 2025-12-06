<?php

namespace App\Http\Requests\AreaRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class RegionRequest extends FormRequest
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
        $regionId = $this->route('region');
        $rules = [
            'city_id' => 'required|exists:cities,id',
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
            'city_id' => __('areas/region.city'),
        ];

        // Add dynamic attributes for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $attributes['translations.' . $language->id . '.name'] = __('areas/region.name_' . ($language->code == 'ar' ? 'arabic' : 'english'));
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
        $messages = [
            'city_id.required' => __('areas/region.validation.city_required'),
            'city_id.exists' => __('areas/region.validation.city_exists'),
        ];

        // Add dynamic messages for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $messages['translations.' . $language->id . '.name.required'] = __('areas/region.validation.name_' . ($language->code == 'ar' ? 'ar' : 'en') . '_required');
        }

        return $messages;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert active value to integer (handles hidden input + checkbox pattern)
        $this->merge([
            'active' => (int) $this->input('active', 0),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $regionId = $this->route('region');
            $cityId = $this->city_id;
            $translations = $this->input('translations', []);
            
            // Check uniqueness for each language translation
            foreach ($translations as $langId => $translation) {
                $nameValue = $translation['name'] ?? null;
                
                if ($nameValue) {
                    // Check if a region with this name exists in the same city
                    $query = \App\Models\Areas\Region::where('city_id', $cityId)
                        ->whereHas('translations', function ($q) use ($langId, $nameValue) {
                            $q->where('lang_id', $langId)
                                ->where('lang_key', 'name')
                                ->where('lang_value', $nameValue);
                        });
                    
                    // Ignore current region if updating
                    if ($regionId) {
                        $query->where('id', '!=', $regionId);
                    }
                    
                    if ($query->exists()) {
                        $validator->errors()->add(
                            'translations.' . $langId . '.name',
                            __('areas/region.validation.name_unique')
                        );
                    }
                }
            }
        });
    }
}
