<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ReserveAppointmentRequest extends FormRequest
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
            'lawyer_id' => 'required|exists:lawyers,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'day' => 'required|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'period' => 'required|in:morning,evening',
            'time_slot' => 'required|date_format:H:i',
            'consultation_type' => 'nullable|in:online,office',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'lawyer_id.required' => __('appointment.lawyer_required'),
            'lawyer_id.exists' => __('appointment.lawyer_not_found'),
            'appointment_date.required' => __('appointment.date_required'),
            'appointment_date.after_or_equal' => __('appointment.date_must_be_future'),
            'day.required' => __('appointment.day_required'),
            'day.in' => __('appointment.invalid_day'),
            'period.required' => __('appointment.period_required'),
            'period.in' => __('appointment.invalid_period'),
            'time_slot.required' => __('appointment.time_slot_required'),
            'time_slot.date_format' => __('appointment.invalid_time_format'),
            'consultation_type.in' => __('appointment.invalid_consultation_type'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert appointment_date to day name if not provided
        if ($this->has('appointment_date') && !$this->has('day')) {
            $dayName = strtolower(date('l', strtotime($this->appointment_date)));
            $this->merge(['day' => $dayName]);
        }
    }
}
