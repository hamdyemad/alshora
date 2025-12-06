<?php

namespace App\Http\Requests\Api;

use App\Traits\Res;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends FormRequest
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
            'uuid' => 'required|string|max:255|exists:users,uuid',
            'password' => ['required', 'confirmed', 'min:8'],
            'reset_code' => ['required'],
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
