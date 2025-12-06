<?php

namespace App\Http\Requests\Api;

use App\Traits\Res;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name_en' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'experience_en' => 'nullable|string',
            'experience_ar' => 'nullable|string',
            'consultation_price' => 'nullable|numeric|min:0',
            'gender' => 'nullable|in:male,female',
            'degree_of_registration_id' => 'nullable|exists:registration_grades,id',
            'phone' => 'required_with:phone_country_id|string|max:20',
            'phone_country_id' => 'nullable|exists:countries,id',
            'sections_of_laws' => 'nullable|array|min:1',
            'sections_of_laws.*' => ['required_with:sections_of_laws', Rule::exists('sections_of_laws', 'id')->where('active', 1)],
            'address' => 'nullable|string|max:500',
            'city_id' => 'nullable|exists:cities,id',
            'region_id' => ['required_with:city_id', Rule::exists('regions', 'id')->where(function ($query) {
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
