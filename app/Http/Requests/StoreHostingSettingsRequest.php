<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostingSettingsRequest extends FormRequest
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
            'time_slots' => 'required|array',
        ];

        $timeSlots = $this->input('time_slots', []);

        foreach ($timeSlots as $key => $slot) {
            if (is_array($slot)) {
                $rules["time_slots.{$key}.day"] = 'required|string';
                $rules["time_slots.{$key}.from_time"] = 'nullable';
                $rules["time_slots.{$key}.to_time"] = 'nullable';
                $rules["time_slots.{$key}.is_active"] = 'nullable|in:0,1';
            }
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'time_slots.required' => __('hosting.no_time_slots_provided'),
            'time_slots.*.from_time.date_format' => __('hosting.invalid_time_format'),
            'time_slots.*.to_time.date_format' => __('hosting.invalid_time_format'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $timeSlots = $this->input('time_slots', []);

        foreach ($timeSlots as $key => $slot) {
            if (is_array($slot)) {
                // Clean empty strings to null for time fields
                if (isset($slot['from_time']) && empty($slot['from_time'])) {
                    $timeSlots[$key]['from_time'] = null;
                }
                if (isset($slot['to_time']) && empty($slot['to_time'])) {
                    $timeSlots[$key]['to_time'] = null;
                }
            }
        }

        $this->merge(['time_slots' => $timeSlots]);
    }
}
