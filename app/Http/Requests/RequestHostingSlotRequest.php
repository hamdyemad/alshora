<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestHostingSlotRequest extends FormRequest
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
        return [
            'hosting_time_id' => 'required|integer|exists:hosting_times,id',
            'reason' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'hosting_time_id.required' => __('hosting.hosting_time_required'),
            'hosting_time_id.integer' => __('hosting.hosting_time_must_be_integer'),
            'hosting_time_id.exists' => __('hosting.hosting_time_not_found'),
            'reason.string' => __('hosting.reason_must_be_string'),
            'reason.max' => __('hosting.reason_max_500_characters'),
        ];
    }
}
