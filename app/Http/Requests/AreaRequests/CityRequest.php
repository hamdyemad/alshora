<?php

namespace App\Http\Requests\AreaRequests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
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
        $cityId = $this->route('city');
        $rules = [
            'country_id' => 'required|exists:countries,id',
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
            'country_id' => __('areas/city.country'),
        ];

        // Add dynamic attributes for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $attributes['translations.' . $language->id . '.name'] = __('areas/city.name_' . ($language->code == 'ar' ? 'arabic' : 'english'));
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
            'country_id.required' => __('areas/city.validation.country_required'),
            'country_id.exists' => __('areas/city.validation.country_exists'),
        ];

        // Add dynamic messages for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $messages['translations.' . $language->id . '.name.required'] = __('areas/city.validation.name_' . ($language->code == 'ar' ? 'ar' : 'en') . '_required');
            $messages['translations.' . $language->id . '.name.unique'] = __('areas/city.validation.name_' . ($language->code == 'ar' ? 'ar' : 'en') . '_unique');
        }

        return $messages;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Log raw request data
        \Log::info('Raw request data', [
            'all' => $this->all(),
            'input' => $this->input(),
        ]);
        
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
            $cityId = $this->route('city');
            $countryId = $this->country_id;
            $translations = $this->input('translations', []);
            
            // Check uniqueness for each language translation
            foreach ($translations as $langId => $translation) {
                $nameValue = $translation['name'] ?? null;
                
                if ($nameValue) {
                    // Check if a city with this name exists in the same country
                    $query = \App\Models\Areas\City::where('country_id', $countryId)
                        ->whereHas('translations', function ($q) use ($langId, $nameValue) {
                            $q->where('lang_id', $langId)
                                ->where('lang_key', 'name')
                                ->where('lang_value', $nameValue);
                        });
                    
                    // Ignore current city if updating
                    if ($cityId) {
                        $query->where('id', '!=', $cityId);
                    }
                    
                    if ($query->exists()) {
                        $validator->errors()->add(
                            'translations.' . $langId . '.name',
                            __('areas/city.validation.name_unique')
                        );
                    }
                }
            }
        });
    }
}
