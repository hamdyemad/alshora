<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveHostingReservationRequest extends FormRequest
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
            'admin_notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'admin_notes.string' => __('hosting.admin_notes_must_be_string'),
            'admin_notes.max' => __('hosting.admin_notes_max_500_characters'),
        ];
    }
}
