<?php

namespace App\Http\Requests\Api;

use App\Models\LawyerOfficeHour;
use App\Traits\Res;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateOfficeHoursApiRequest extends FormRequest
{
    use Res;

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
        $days = array_keys(LawyerOfficeHour::getDays());
        $periods = array_keys(LawyerOfficeHour::getPeriods());

        $rules = [
            'office_hours' => 'required|array',
        ];

        // Add validation for each day and period
        foreach ($days as $day) {
            foreach ($periods as $period) {
                $rules["office_hours.{$day}.{$period}.is_available"] = 'nullable|in:0,1';
                $rules["office_hours.{$day}.{$period}.from_time"] = [
                    'nullable',
                    'date_format:H:i:s',
                    'required_if:office_hours.' . $day . '.' . $period . '.is_available,1',
                ];
                $rules["office_hours.{$day}.{$period}.to_time"] = [
                    'nullable',
                    'date_format:H:i:s',
                    'required_if:office_hours.' . $day . '.' . $period . '.is_available,1',
                    'after:office_hours.' . $day . '.' . $period . '.from_time',
                ];
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
            'office_hours.*.*.to_time.after' => 'The end time must be after the start time.',
            'office_hours.*.*.from_time.required_if' => 'The start time is required when available.',
            'office_hours.*.*.to_time.required_if' => 'The end time is required when available.',
            'office_hours.*.*.from_time.date_format' => 'The start time must be in HH:MM:SS format.',
            'office_hours.*.*.to_time.date_format' => 'The end time must be in HH:MM:SS format.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $messages = $validator->errors()->all();
        $allMessages = implode("\n", $messages);
        throw new HttpResponseException(
            $this->sendRes($allMessages, false, [], $validator->errors(), 422)
        );
    }

    /**
     * Determine if the request expects a JSON response.
     */
    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * Determine if the request wants a JSON response.
     */
    public function wantsJson(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $officeHours = $this->input('office_hours', []);

        // Clean empty strings to null for time fields and convert H:i to H:i:s
        foreach ($officeHours as $day => $periods) {
            foreach ($periods as $period => $data) {
                if (isset($data['from_time']) && empty($data['from_time'])) {
                    $officeHours[$day][$period]['from_time'] = null;
                } elseif (isset($data['from_time']) && !empty($data['from_time'])) {
                    // Convert H:i format to H:i:s format
                    $time = $data['from_time'];
                    if (preg_match('/^\d{2}:\d{2}$/', $time)) {
                        $officeHours[$day][$period]['from_time'] = $time . ':00';
                    }
                }

                if (isset($data['to_time']) && empty($data['to_time'])) {
                    $officeHours[$day][$period]['to_time'] = null;
                } elseif (isset($data['to_time']) && !empty($data['to_time'])) {
                    // Convert H:i format to H:i:s format
                    $time = $data['to_time'];
                    if (preg_match('/^\d{2}:\d{2}$/', $time)) {
                        $officeHours[$day][$period]['to_time'] = $time . ':00';
                    }
                }

                if (!isset($data['is_available'])) {
                    $officeHours[$day][$period]['is_available'] = 0;
                }
            }
        }

        $this->merge(['office_hours' => $officeHours]);
    }
}
