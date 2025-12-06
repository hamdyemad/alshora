<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class SubscriptionRequest extends FormRequest
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
        $subscriptionId = $this->route('subscription');
        
        $rules = [
            'number_of_months' => 'required|integer|min:1|max:120',
            'translations' => 'required|array',
            'translations.*.name' => 'required|string|max:255',
            'active' => 'nullable|boolean',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        $attributes = [
            'number_of_months' => __('subscription.number_of_months'),
        ];

        // Add dynamic attributes for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $attributes['translations.' . $language->id . '.name'] = __('subscription.name_' . ($language->code == 'ar' ? 'arabic' : 'english'));
        }

        return $attributes;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        $messages = [
            'number_of_months.required' => __('subscription.validation.number_of_months_required'),
            'number_of_months.integer' => __('subscription.validation.number_of_months_integer'),
            'number_of_months.min' => __('subscription.validation.number_of_months_min'),
            'number_of_months.max' => __('subscription.validation.number_of_months_max'),
        ];

        // Add dynamic messages for each language
        $languages = Language::all();
        foreach ($languages as $language) {
            $messages['translations.' . $language->id . '.name.required'] = __('subscription.validation.name_' . ($language->code == 'ar' ? 'ar' : 'en') . '_required');
        }

        return $messages;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert active value to integer
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
            $subscriptionId = $this->route('subscription');
            $translations = $this->input('translations', []);
            
            // Check uniqueness for each language translation
            foreach ($translations as $langId => $translation) {
                $nameValue = $translation['name'] ?? null;
                
                if ($nameValue) {
                    $query = \App\Models\Subscription::whereHas('translations', function ($q) use ($langId, $nameValue) {
                        $q->where('lang_id', $langId)
                            ->where('lang_key', 'name')
                            ->where('lang_value', $nameValue);
                    });
                    
                    // Ignore current subscription if updating
                    if ($subscriptionId) {
                        $query->where('id', '!=', $subscriptionId);
                    }
                    
                    if ($query->exists()) {
                        $validator->errors()->add(
                            'translations.' . $langId . '.name',
                            __('subscription.validation.name_unique')
                        );
                    }
                }
            }
        });
    }
}
