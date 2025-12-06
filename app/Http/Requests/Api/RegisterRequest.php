<?php

namespace App\Http\Requests\Api;

use App\Traits\Res;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'request_type' => 'required|in:lawyer,customer',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'degree_of_registration_id' => 'required_if:request_type,lawyer|exists:registration_grades,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required|string|max:20',
            'phone_country_id' => 'required|exists:countries,id',
            'address' => 'required|string|max:500',
            'city_id' => 'required|exists:cities,id',
            'region_id' => ['required', Rule::exists('regions', 'id')->where(function ($query) {
                $query->where('city_id', $this->city_id);
            })],
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
