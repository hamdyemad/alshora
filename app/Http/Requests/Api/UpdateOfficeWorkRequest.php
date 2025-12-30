<?php

namespace App\Http\Requests\Api;

use App\Models\LawyerOfficeHour;
use App\Traits\Res;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateOfficeWorkRequest extends FormRequest
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
     * Indicates if the validator should stop on the first rule failure.
     */
    protected $stopOnFirstFailure = false;

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('from_time') && !empty($this->from_time)) {
            // Convert H:i format to H:i:s if necessary
            if (preg_match('/^\d{2}:\d{2}$/', $this->from_time)) {
                $this->merge(['from_time' => $this->from_time . ':00']);
            }
        }

        if ($this->has('to_time') && !empty($this->to_time)) {
            // Convert H:i format to H:i:s if necessary
            if (preg_match('/^\d{2}:\d{2}$/', $this->to_time)) {
                $this->merge(['to_time' => $this->to_time . ':00']);
            }
        }

        if ($this->has('is_available')) {
            $val = $this->is_available;
            if ($val === true || $val === 'true' || $val == 1) {
                $this->merge(['is_available' => 1]);
            } else {
                $this->merge(['is_available' => 0]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'period' => ['required', Rule::in(array_keys(LawyerOfficeHour::getPeriods()))],
            'day' => ['required', Rule::in(array_keys(LawyerOfficeHour::getDays()))],
            'is_available' => ['required', 'in:1,0'],
            'from_time' => ['required', 'date_format:H:i:s'],
            'to_time' => ['required', 'date_format:H:i:s', 'after:from_time'],
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
}
