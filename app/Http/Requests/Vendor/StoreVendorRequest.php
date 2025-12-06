<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class StoreVendorRequest extends FormRequest
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
            // Vendor Information - Translations (using language IDs)
            'translations' => 'required|array|min:1',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
            
            // Files
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            
            // Relations
            'country_id' => 'required|exists:countries,id',
            'activity_ids' => 'required|array|min:1',
            'activity_ids.*' => 'required|exists:activities,id',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            
            // Documents (using language IDs)
            'documents' => 'nullable|array',
            'documents.*.translations' => 'required_with:documents|array',
            'documents.*.translations.*.name' => 'required_with:documents|string|max:255',
            'documents.*.file' => 'required_with:documents|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            
            // Account
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'translations.*.name' => __('Name'),
            'translations.*.description' => __('Description'),
            'logo' => __('vendor.logo'),
            'banner' => __('vendor.banner'),
            'country_id' => __('Country'),
            'activity_id' => __('common.activity'),
            'meta_title' => __('Meta Title'),
            'meta_description' => __('Meta Description'),
            'meta_keywords' => __('Meta Keywords'),
            'documents.*.translations.*.name' => __('Document Name'),
            'documents.*.file' => __('Document File'),
            'email' => __('Email'),
            'password' => __('Password'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        $messages = [
            'translations.*.name.required' => __('vendor.name_required_all_languages'),
            'translations.required' => __('vendor.at_least_one_translation_required'),
            'country_id.required' => __('vendor.please_select_country'),
            'country_id.exists' => __('vendor.selected_country_invalid'),
            'activity_ids.required' => __('vendor.please_select_activity'),
            'activity_ids.min' => __('vendor.please_select_at_least_one_activity'),
            'activity_ids.*.exists' => __('vendor.selected_activity_invalid'),
            'email.required' => __('vendor.email_required'),
            'email.email' => __('vendor.email_valid'),
            'email.unique' => __('vendor.email_already_registered'),
            'password.required' => __('vendor.password_required'),
            'password.min' => __('vendor.password_min_8'),
            'password.confirmed' => __('vendor.password_confirmation_mismatch'),
            'logo.required' => __('vendor.logo_required'),
            'logo.image' => __('vendor.logo_must_be_image'),
            'logo.mimes' => __('vendor.logo_file_types'),
            'logo.max' => __('vendor.logo_max_size'),
            'banner.required' => __('vendor.banner_required'),
            'banner.image' => __('vendor.banner_must_be_image'),
            'banner.mimes' => __('vendor.banner_file_types'),
            'banner.max' => __('vendor.banner_max_size'),
            'documents.*.file.required_with' => __('vendor.document_file_required'),
            'documents.*.file.mimes' => __('vendor.document_file_types'),
            'documents.*.file.max' => __('vendor.document_max_size'),
        ];

        // Add custom messages for document translations with language names
        $languages = Language::all();
        foreach ($languages as $language) {
            $messages["documents.*.translations.{$language->id}.name.required_with"] = 
                __('vendor.document_name_required_for_language', ['language' => $language->name]);
        }

        return $messages;
    }
}
