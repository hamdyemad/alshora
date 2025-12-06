<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $customerId = $this->route('customer');
        $userId = null;

        // If customer is being edited, get the user_id from the customer
        if ($customerId) {
            $customer = \App\Models\Customer::find($customerId);
            $userId = $customer ? $customer->user_id : null;
        }

        $rules = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'phone_country_id' => 'required|exists:countries,id',
            'address' => 'nullable|string|max:500',
            'city_id' => 'nullable|exists:cities,id',
            'region_id' => 'nullable|exists:regions,id',
            'active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Email validation - unique in users table
        if ($userId) {
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)];
        } else {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|string|min:8';
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set active to false if not present (unchecked checkbox)
        if (!$this->has('active')) {
            $this->merge(['active' => false]);
        }
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => __('customer.name_required'),
            'name_en.required' => __('customer.name_en_required'),
            'name_ar.required' => __('customer.name_ar_required'),
            'email.required' => __('customer.email_required'),
            'email.unique' => __('customer.email_unique'),
            'phone.required' => __('customer.phone_required'),
            'phone_country_id.required' => __('customer.phone_country_required'),
            'logo.image' => __('customer.logo_must_be_image'),
        ];
    }
}
