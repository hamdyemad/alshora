<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LawyerRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $lawyerId = $this->route('lawyer');

        $rules = [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'registration_number' => 'required|string|max:100',
            'degree_of_registration_id' => 'required|exists:registration_grades,id',
            'phone' => 'required|string|max:20',
            'phone_country_id' => 'required|exists:countries,id',
            'consultation_price' => 'required|numeric|min:0',
            'sections_of_laws' => 'required|array|min:1',
            'sections_of_laws.*' => 'required|exists:sections_of_laws,id',
            'address' => 'nullable|string|max:500',
            'city_id' => 'nullable|exists:cities,id',
            'region_id' => 'nullable|exists:regions,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'experience_en' => 'nullable|string',
            'experience_ar' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'nullable|boolean',
        ];

        // Email validation - unique for new lawyers, or unique except current lawyer when updating
        if ($lawyerId) {
            // Get the user_id from the lawyer
            $lawyer = \App\Models\Lawyer::find($lawyerId);
            $userIdToIgnore = $lawyer ? $lawyer->user_id : null;
            
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userIdToIgnore),
            ];
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'name_en' => __('lawyer.name_en'),
            'name_ar' => __('lawyer.name_ar'),
            'gender' => __('lawyer.gender'),
            'registration_number' => __('lawyer.registration_number'),
            'degree_of_registration_id' => __('lawyer.degree_of_registration'),
            'email' => __('lawyer.email'),
            'password' => __('lawyer.password'),
            'phone' => __('lawyer.phone'),
            'phone_country_id' => __('lawyer.phone_country'),
            'consultation_price' => __('lawyer.consultation_price'),
            'sections_of_laws' => __('lawyer.sections_of_laws'),
            'address' => __('lawyer.address'),
            'city_id' => __('lawyer.city'),
            'region_id' => __('lawyer.region'),
        ];
    }
}
