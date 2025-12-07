@extends('layout.app')

@section('styles')
    <style>
        /* Map Container */
        .map-container {
            border-radius: 12px;
            overflow: visible;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e6ed;
            position: relative;
            isolation: isolate;
        }
        
        .map-container gmp-map {
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }

        /* Search Wrapper */
        .map-search-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        /* Search Input Group */
        .search-input-group {
            position: relative;
            max-width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #8b92a8;
            z-index: 1;
        }

        .map-search-input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 2px solid transparent;
            border-radius: 50px;
            font-size: 15px;
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .map-search-input:focus {
            outline: none;
            border-color: #5f63f2;
            box-shadow: 0 6px 20px rgba(95, 99, 242, 0.2);
            transform: translateY(-2px);
        }

        .map-search-input::placeholder {
            color: #9299b8;
        }

        /* Google Maps Component */
        gmp-map {
            height: 500px;
            width: 100%;
            display: block;
            background: #f5f5f5;
            position: relative;
            pointer-events: auto !important;
            cursor: crosshair;
            z-index: 1;
        }
        
        /* Ensure map is interactive */
        gmp-map * {
            pointer-events: auto !important;
        }
        
        /* Ensure marker is visible */
        gmp-advanced-marker {
            cursor: grab;
            z-index: 1000;
            pointer-events: auto !important;
        }
        
        gmp-advanced-marker:active {
            cursor: grabbing;
        }

        /* Places Autocomplete Dropdown */
        .pac-container {
            z-index: 10000 !important;
            border-radius: 12px;
            margin-top: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border: 1px solid #e0e6ed;
            font-family: inherit;
        }

        .pac-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f0f2f5;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .pac-item:hover {
            background-color: #f8f9fb;
        }

        .pac-item:last-child {
            border-bottom: none;
        }

        .pac-item-query {
            font-weight: 600;
            color: #272b41;
        }

        .pac-matched {
            color: #5f63f2;
            font-weight: 700;
        }

        .pac-icon {
            margin-right: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .map-search-wrapper {
                padding: 15px;
            }

            .map-search-input {
                padding: 12px 16px 12px 45px;
                font-size: 14px;
            }

            gmp-map {
                height: 400px;
            }
        }

        /* Select2 Validation Error Styling */
        .is-invalid + .select2-container--default .select2-selection--multiple {
            border-color: #dc3545 !important;
            background-color: #fff5f5;
        }
        
        .is-invalid + .select2-container--default .select2-selection--multiple:focus,
        .is-invalid + .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    [
                        'title' => trans('dashboard.title'),
                        'url' => route('admin.dashboard'),
                        'icon' => 'uil uil-estate',
                    ],
                    ['title' => trans('lawyer.lawyers_management'), 'url' => route('admin.lawyers.index')],
                    ['title' => isset($lawyer) ? trans('lawyer.edit_lawyer') : trans('lawyer.create_lawyer')],
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">
                            {{ isset($lawyer) ? trans('lawyer.edit_lawyer') : trans('lawyer.create_lawyer') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Alert Container -->
                        <div id="alertContainer"></div>

                        <form id="lawyerForm"
                            action="{{ isset($lawyer) ? route('admin.lawyers.update', $lawyer->id) : route('admin.lawyers.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($lawyer))
                                @method('PUT')
                            @endif

                            <div class="row">
                                {{-- Basic Information Section --}}
                                <div class="col-12 mb-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-user me-2"></i>{{ trans('lawyer.basic_information') }}
                                    </h6>
                                </div>

                                {{-- Name English --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.name_en') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_en') is-invalid @enderror"
                                            id="name_en" name="name_en"
                                            value="{{ isset($lawyer) ? $lawyer->getTranslation('name', 'en') : old('name_en') }}"
                                            placeholder="{{ trans('lawyer.enter_name_en') }}">
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Name Arabic --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="name_ar" class="il-gray fs-14 fw-500 mb-10" dir="rtl"
                                            style="text-align: right; display: block;">
                                            {{ trans('lawyer.name_ar') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name_ar') is-invalid @enderror"
                                            id="name_ar" name="name_ar"
                                            value="{{ isset($lawyer) ? $lawyer->getTranslation('name', 'ar') : old('name_ar') }}"
                                            placeholder="{{ trans('lawyer.enter_name_ar') }}" dir="rtl">
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Gender --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="gender" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.gender') }} <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('gender') is-invalid @enderror"
                                            id="gender" name="gender">
                                            <option value="">{{ trans('lawyer.select_gender') }}</option>
                                            <option value="male"
                                                {{ (isset($lawyer) && $lawyer->gender == 'male') || old('gender') == 'male' ? 'selected' : '' }}>
                                                {{ trans('lawyer.male') }}</option>
                                            <option value="female"
                                                {{ (isset($lawyer) && $lawyer->gender == 'female') || old('gender') == 'female' ? 'selected' : '' }}>
                                                {{ trans('lawyer.female') }}</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Registration Number --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="registration_number" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.registration_number') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('registration_number') is-invalid @enderror"
                                            id="registration_number" name="registration_number"
                                            value="{{ isset($lawyer) ? $lawyer->registration_number : old('registration_number') }}"
                                            placeholder="{{ trans('lawyer.enter_registration_number') }}">
                                        @error('registration_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Degree of Registration --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="degree_of_registration_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.degree_of_registration') }} <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('degree_of_registration_id') is-invalid @enderror"
                                            id="degree_of_registration_id" name="degree_of_registration_id">
                                            <option value="">{{ trans('lawyer.select_degree') }}</option>
                                            @foreach ($registerGrades as $grade)
                                                <option value="{{ $grade['id'] }}"
                                                    {{ (isset($lawyer) && $lawyer->degree_of_registration_id == $grade['id']) || old('degree_of_registration_id') == $grade['id'] ? 'selected' : '' }}>
                                                    {{ $grade['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('degree_of_registration_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="email" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.email') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="email"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('email') is-invalid @enderror"
                                            id="email" name="email"
                                            value="{{ isset($lawyer) ? $lawyer->user->email : old('email') }}"
                                            placeholder="Enter email address">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="password" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.password') }} <span class="text-danger">{{ isset($lawyer) ? '' : '*' }}</span>
                                        </label>
                                        <input type="password"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('password') is-invalid @enderror"
                                            id="password" name="password"
                                            value=""
                                            placeholder="{{ trans('lawyer.enter_password') }}">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Password Confirmation--}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.password_confirmation') }} <span class="text-danger">{{ isset($lawyer) ? '' : '*' }}</span>
                                        </label>
                                        <input type="password"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation"
                                            value=""
                                            placeholder="{{ trans('lawyer.enter_password_confirmation') }}">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>



                                {{-- Phone Country --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="phone_country_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.phone_country') }} <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-control select2 ih-medium ip-gray radius-xs b-light px-15 @error('phone_country_id') is-invalid @enderror"
                                            id="phone_country_id" name="phone_country_id">
                                            <option value="">{{ trans('lawyer.select_country') }}</option>
                                            @php
                                                $countries = \App\Models\Areas\Country::where('active', 1)->get();
                                            @endphp
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ (isset($lawyer) && $lawyer->phone_country_id == $country->id) || old('phone_country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->getTranslation('name', app()->getLocale()) }}
                                                    ({{ $country->phone_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('phone_country_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="phone" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.phone') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('phone') is-invalid @enderror"
                                            id="phone" name="phone"
                                            value="{{ isset($lawyer) ? $lawyer->phone : old('phone') }}"
                                            placeholder="{{ trans('lawyer.enter_phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Consultation Price --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="consultation_price" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.consultation_price') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" step="0.01"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('consultation_price') is-invalid @enderror"
                                            id="consultation_price" name="consultation_price"
                                            value="{{ isset($lawyer) ? $lawyer->consultation_price : old('consultation_price') }}"
                                            placeholder="{{ trans('lawyer.enter_consultation_price') }}">
                                        @error('consultation_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Specializations Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-briefcase me-2"></i>{{ trans('lawyer.specializations') }}
                                    </h6>
                                </div>

                                {{-- Sections of Laws (Specializations) --}}
                                <div class="col-md-12 mb-25">
                                    <div class="form-group">
                                        <label for="sections_of_laws" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.sections_of_laws') }} <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-control select2 @error('sections_of_laws') is-invalid @enderror @error('sections_of_laws.*') is-invalid @enderror"
                                            id="sections_of_laws" name="sections_of_laws[]" multiple="multiple" style="width: 100%;">
                                            @foreach ($sectionsOfLaws as $section)
                                                <option value="{{ $section->id }}"
                                                    {{ (isset($lawyer) && $lawyer->sectionsOfLaws->contains($section->id)) || (is_array(old('sections_of_laws')) && in_array($section->id, old('sections_of_laws'))) ? 'selected' : '' }}>
                                                    {{ $section->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sections_of_laws')
                                            <div class="text-danger mt-2 fs-13">
                                                <i class="uil uil-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        @error('sections_of_laws.*')
                                            <div class="text-danger mt-2 fs-13">
                                                <i class="uil uil-exclamation-triangle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        @if(!$errors->has('sections_of_laws') && !$errors->has('sections_of_laws.*'))
                                            <small class="text-muted d-block mt-2">{{ trans('lawyer.select_at_least_one_specialization') }}</small>
                                        @endif
                                    </div>
                                </div>

                                {{-- Images Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-image me-2"></i>{{ trans('lawyer.images') }}
                                    </h6>
                                </div>

                                {{-- Profile Image Upload --}}
                                {{-- Profile Image Upload --}}
                                <x-image-upload 
                                    name="profile_image"
                                    :label="trans('lawyer.profile_image')"
                                    :image="isset($lawyer) ? $lawyer->profile_image : null"
                                    :placeholderText="trans('lawyer.click_upload_profile')"
                                    :recommendedText="trans('lawyer.recommended_size_profile')"
                                />

                                {{-- ID Card Image Upload --}}
                                {{-- ID Card Image Upload --}}
                                <x-image-upload 
                                    name="id_card"
                                    :label="trans('lawyer.id_card_image')"
                                    :image="isset($lawyer) ? $lawyer->id_card : null"
                                    placeholderIcon="uil uil-credit-card"
                                    :placeholderText="trans('lawyer.click_upload_id_card')"
                                    :recommendedText="trans('lawyer.recommended_size_id_card')"
                                />

                                {{-- Location Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-map-marker me-2"></i>{{ trans('lawyer.location_information') }}
                                    </h6>
                                </div>

                                {{-- Address --}}
                                <div class="col-md-12 mb-25">
                                    <div class="form-group">
                                        <label for="address" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.address') }}
                                        </label>
                                        <input type="text"
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('address') is-invalid @enderror"
                                            id="address" name="address"
                                            value="{{ isset($lawyer) ? $lawyer->address : old('address') }}"
                                            placeholder="{{ trans('lawyer.enter_address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- City --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="city_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.city') }}
                                        </label>
                                        <select
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('city_id') is-invalid @enderror"
                                            id="city_id" name="city_id">
                                            <option value="">{{ trans('lawyer.select_city') }}</option>
                                            @php
                                                $cities = \App\Models\Areas\City::where('active', 1)->get();
                                            @endphp
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ (isset($lawyer) && $lawyer->city_id == $city->id) || old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->getTranslation('name', app()->getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Region --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="region_id" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.region') }}
                                        </label>
                                        <select
                                            class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('region_id') is-invalid @enderror"
                                            id="region_id" name="region_id" disabled>
                                            <option value="">{{ trans('lawyer.select_region') }}</option>
                                        </select>
                                        @error('region_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Experiences Section --}}
                                <div class="col-12 mb-20 mt-20">
                                    <h6 class="fw-500 color-dark border-bottom pb-15">
                                        <i class="uil uil-briefcase me-2"></i>{{ trans('lawyer.experiences') }}
                                    </h6>
                                </div>

                                {{-- Experience English --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="experience_en" class="il-gray fs-14 fw-500 mb-10">
                                            {{ trans('lawyer.experience_en') }} <span class="text-danger">*</span>
                                        </label>
                                        <textarea
                                            class="form-control ip-gray radius-xs b-light px-15 @error('experience_en') is-invalid @enderror"
                                            id="experience_en" name="experience_en" rows="4"
                                            placeholder="{{ trans('lawyer.enter_experience_en') }}">{{ isset($lawyer) ? $lawyer->getTranslation('experience', 'en') : old('experience_en') }}</textarea>
                                        @error('experience_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Experience Arabic --}}
                                <div class="col-md-6 mb-25">
                                    <div class="form-group">
                                        <label for="experience_ar" class="il-gray fs-14 fw-500 mb-10" dir="rtl" style="text-align: right; display: block;">
                                            {{ trans('lawyer.experience_ar') }} <span class="text-danger">*</span>
                                        </label>
                                        <textarea
                                            class="form-control ip-gray radius-xs b-light px-15 @error('experience_ar') is-invalid @enderror"
                                            id="experience_ar" name="experience_ar" rows="4"
                                            placeholder="{{ trans('lawyer.enter_experience_ar') }}" dir="rtl">{{ isset($lawyer) ? $lawyer->getTranslation('experience', 'ar') : old('experience_ar') }}</textarea>
                                        @error('experience_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Activation Switcher --}}
                                <div class="col-md-6 mb-25 mt-20">
                                    <div class="form-group">
                                        <label class="il-gray fs-14 fw-500 mb-10 d-block">
                                            {{ trans('lawyer.active') }}
                                        </label>
                                        <div class="dm-switch-wrap d-flex align-items-center">
                                            <div class="form-check form-switch form-switch-primary form-switch-md">
                                                <input type="hidden" name="active" value="0">
                                                <input type="checkbox" class="form-check-input" id="active"
                                                    name="active" value="1"
                                                    {{ old('active', $lawyer->active ?? 1) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        @error('active')
                                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-15 mt-30">
                                <a href="{{ route('admin.lawyers.index') }}"
                                    class="btn btn-light btn-default btn-squared fw-400 text-capitalize">
                                    <i class="uil uil-angle-left"></i> {{ trans('lawyer.cancel') }}
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="btn btn-primary btn-default btn-squared text-capitalize"
                                    style="display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="uil uil-check"></i>
                                    <span>{{ isset($lawyer) ? trans('lawyer.update_lawyer') : trans('lawyer.add_lawyer') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // AJAX Form Submission
                const lawyerForm = document.getElementById('lawyerForm');
                const submitBtn = document.getElementById('submitBtn');
                const alertContainer = document.getElementById('alertContainer');

                lawyerForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Disable submit button and show loading
                    submitBtn.disabled = true;
                    const btnIcon = submitBtn.querySelector('i');
                    const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                    if (btnIcon) btnIcon.classList.add('d-none');
                    if (btnText) btnText.classList.add('d-none');
                    submitBtn.querySelector('.spinner-border').classList.remove('d-none');

                    // Update loading text dynamically
                    const loadingText = @json(isset($lawyer) ? 'Updating Lawyer...' : 'Creating Lawyer...');
                    const loadingSubtext = 'Please wait';
                    const overlay = document.getElementById('loadingOverlay');
                    if (overlay) {
                        overlay.querySelector('.loading-text').textContent = loadingText;
                        overlay.querySelector('.loading-subtext').textContent = loadingSubtext;
                    }

                    // Show loading overlay
                    LoadingOverlay.show();

                    // Clear previous alerts
                    alertContainer.innerHTML = '';

                    // Remove previous validation errors
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                    // Start progress bar animation
                    LoadingOverlay.animateProgressBar(30, 300).then(() => {
                            // Prepare form data
                            const formData = new FormData(lawyerForm);
                            
                            // Log coordinates being submitted
                            const latitude = formData.get('latitude');
                            const longitude = formData.get('longitude');
                            console.log('📍 Submitting location coordinates:', {
                                latitude: latitude || 'not set',
                                longitude: longitude || 'not set'
                            });
                            
                            if (latitude && longitude) {
                                console.log('✅ Location will be saved to database');
                            } else {
                                console.log('ℹ️ No location selected (optional field)');
                            }

                            // Send AJAX request
                            return fetch(lawyerForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            });
                        })
                        .then(response => {
                            // Progress to 60%
                            LoadingOverlay.animateProgressBar(60, 200);

                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw data;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Progress to 90%
                            return LoadingOverlay.animateProgressBar(90, 200).then(() => data);
                        })
                        .then(data => {
                            // Complete progress bar
                            return LoadingOverlay.animateProgressBar(100, 200).then(() => {
                                // Show success animation with dynamic message
                                const successMessage = @json(isset($lawyer) ? 'Lawyer updated successfully!' : 'Lawyer created successfully!');
                                LoadingOverlay.showSuccess(
                                    successMessage,
                                    'Redirecting...'
                                );

                                // Show success alert
                                showAlert('success', data.message || successMessage);

                                // Redirect after 1.5 seconds
                                setTimeout(() => {
                                    window.location.href = data.redirect ||
                                        '{{ route('admin.lawyers.index') }}';
                                }, 1500);
                            });
                        })
                        .catch(error => {
                            // Hide loading overlay and reset progress bar
                            LoadingOverlay.hide();

                            // Handle validation errors
                            if (error.errors) {
                                // Clear previous errors
                                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove(
                                    'is-invalid'));
                                document.querySelectorAll('.invalid-feedback, .text-danger.mt-2').forEach(el => el.remove());

                                Object.keys(error.errors).forEach(key => {
                                    // Handle nested field names and array fields
                                    let fieldName = key;
                                    let baseFieldName = key.replace(/\.\d+$/, '').replace(/\.\*$/, '');

                                    // Try to find the input with exact name match
                                    let input = document.querySelector(`[name="${fieldName}"]`);

                                    // Try with brackets for array fields
                                    if (!input) {
                                        input = document.querySelector(`[name="${baseFieldName}[]"]`);
                                    }

                                    // Try with escaped brackets
                                    if (!input) {
                                        input = document.querySelector(
                                            `[name="${fieldName.replace(/\[/g, '\\[').replace(/\]/g, '\\]')}"]`
                                        );
                                    }

                                    // Try base field name without array notation
                                    if (!input) {
                                        input = document.querySelector(`[name="${baseFieldName}"]`);
                                    }

                                    // Try by ID as last resort
                                    if (!input) {
                                        input = document.getElementById(baseFieldName);
                                    }

                                    if (input) {
                                        input.classList.add('is-invalid');

                                        // Remove existing feedback if any
                                        const existingFeedback = input.parentNode.querySelector(
                                            '.invalid-feedback, .text-danger.mt-2');
                                        if (existingFeedback) {
                                            existingFeedback.remove();
                                        }

                                        // Add new feedback with appropriate styling
                                        const feedback = document.createElement('div');
                                        // Use text-danger for Select2 fields, invalid-feedback for others
                                        if (input.classList.contains('select2')) {
                                            feedback.className = 'text-danger mt-2 fs-13';
                                            feedback.innerHTML = `<i class="uil uil-exclamation-triangle me-1"></i>${error.errors[key][0]}`;
                                        } else {
                                            feedback.className = 'invalid-feedback d-block';
                                            feedback.textContent = error.errors[key][0];
                                        }
                                        input.parentNode.appendChild(feedback);

                                        // Scroll to first error
                                        if (Object.keys(error.errors)[0] === key) {
                                            input.scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'center'
                                            });
                                        }
                                    }
                                });
                                showAlert('danger', error.message || 'Please check the form for errors');
                            } else {
                                showAlert('danger', error.message || 'An error occurred');
                            }

                            // Re-enable submit button
                            submitBtn.disabled = false;
                            const btnIcon = submitBtn.querySelector('i');
                            const btnText = submitBtn.querySelector('span:not(.spinner-border)');
                            if (btnIcon) btnIcon.classList.remove('d-none');
                            if (btnText) btnText.classList.remove('d-none');
                            submitBtn.querySelector('.spinner-border').classList.add('d-none');
                        });
                });

                // Show alert function
                function showAlert(type, message) {
                    const alert = document.createElement('div');
                    alert.className = `alert alert-${type} alert-dismissible fade show mb-20`;
                    alert.innerHTML = `
                    <i class="uil uil-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                    alertContainer.appendChild(alert);

                    // Scroll to top to show alert
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        </script>

        {{-- Google Maps API with Places --}}
        <script async 
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyAumDLi1iYwwQaFMVbO3f1nXOPBwMKKM&libraries=places,maps,marker&v=weekly&callback=initMap">
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", initMap);

            function initMap() {
                const map = document.querySelector("gmp-map");
                const searchInput = document.getElementById("map-search");

                if (!map || !searchInput) return console.error("Map or input missing");

                // Load existing coordinates
                @if(isset($lawyer) && $lawyer->latitude && $lawyer->longitude)
                    placeMarker({{ $lawyer->latitude }}, {{ $lawyer->longitude }});
                @endif

                // Enable map click
                map.addEventListener("gmp-click", (event) => {
                    if (!event.detail?.latLng) return;
                    const { lat, lng } = event.detail.latLng;
                    placeMarker(lat, lng);
                });

                initAutocomplete(map, searchInput);
            }

            // -------------------------
            // Autocomplete
            // -------------------------
            function initAutocomplete(map, input) {
                if (!google?.maps?.places) {
                    return setTimeout(() => initAutocomplete(map, input), 300);
                }

                const autocomplete = new google.maps.places.Autocomplete(input, {
                    fields: ["geometry", "name", "formatted_address"],
                });

                autocomplete.addListener("place_changed", () => {
                    const place = autocomplete.getPlace();
                    if (!place.geometry) return alert("Select a place from the list");

                    const lat = place.geometry.location.lat();
                    const lng = place.geometry.location.lng();

                    map.setAttribute("center", `${lat},${lng}`);
                    map.setAttribute("zoom", "17");

                    placeMarker(lat, lng);
                    showMessage(`Location: ${place.name || place.formatted_address}`);
                });
            }

            // -------------------------
            // Marker placement
            // -------------------------
            function placeMarker(lat, lng) {
                const map = document.querySelector("gmp-map");
                if (!map) return;

                // Remove old markers
                document.querySelectorAll("gmp-advanced-marker").forEach(m => m.remove());

                const marker = document.createElement("gmp-advanced-marker");
                marker.setAttribute("position", `${lat},${lng}`);
                marker.setAttribute("title", "Selected Location");

                // Enable dragging
                marker.addEventListener("gmp-dragend", (event) => {
                    const { lat, lng } = event.detail.latLng;
                    updateInputs(lat, lng);
                    placeMarker(lat, lng);
                    showMessage("Location updated");
                });

                map.appendChild(marker);
                updateInputs(lat, lng);
            }

            // -------------------------
            // Update form fields
            // -------------------------
            function updateInputs(lat, lng) {
                const latitude = parseFloat(lat).toFixed(6);
                const longitude = parseFloat(lng).toFixed(6);

                document.getElementById("latitude").value = latitude;
                document.getElementById("longitude").value = longitude;

                const latSpan = document.getElementById("display-lat");
                const lngSpan = document.getElementById("display-lng");
                const box = document.getElementById("coordinates-display");

                if (latSpan && lngSpan && box) {
                    latSpan.textContent = latitude;
                    lngSpan.textContent = longitude;
                    box.style.display = "block";
                }
            }

            // -------------------------
            // Toast message
            // -------------------------
            function showMessage(message) {
                const box = document.createElement("div");
                box.className = "alert alert-success position-fixed";
                box.style.cssText = "top:20px; right:20px; z-index:9999;";
                box.innerHTML = `<i class='uil uil-check-circle'></i> ${message}`;
                document.body.appendChild(box);

                setTimeout(() => box.remove(), 2500);
            }
        </script>


        {{-- City/Region Dynamic Loading Script --}}
        <script>
            $(document).ready(function() {
                const citySelect = $('#city_id');
                const regionSelect = $('#region_id');
                const selectedRegionId = '{{ isset($lawyer) ? $lawyer->region_id : old("region_id") }}';
                const editMode = {{ isset($lawyer) ? 'true' : 'false' }};

                // Load regions when city changes
                citySelect.on('change', function() {
                    const cityId = $(this).val();
                    
                    console.log('City changed to:', cityId);
                    
                    // Reset region dropdown
                    regionSelect.html('<option value="">Loading...</option>');
                    regionSelect.prop('disabled', true);

                    if (cityId) {
                        // Fetch regions for selected city
                        const apiUrl = `/api/v1/area/regions/by-city/${cityId}`;
                        console.log('Fetching regions from:', apiUrl);
                        
                        $.ajax({
                            url: apiUrl,
                            type: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Accept-Language': '{{ app()->getLocale() }}'
                            },
                            success: function(response) {
                                console.log('API Response:', response);
                                console.log('Response data:', response.data);
                                console.log('Data length:', response.data ? response.data.length : 0);
                                
                                regionSelect.html('<option value="">{{ trans("lawyer.select_region") }}</option>');
                                
                                if (response.status && response.data && response.data.length > 0) {
                                    response.data.forEach(function(region) {
                                        console.log('Adding region:', region);
                                        const selected = (region.id == selectedRegionId) ? 'selected' : '';
                                        regionSelect.append(`<option value="${region.id}" ${selected}>${region.name}</option>`);
                                    });
                                    regionSelect.prop('disabled', false);
                                    console.log('Regions loaded successfully. Total:', response.data.length);
                                } else {
                                    console.log('No regions found for city:', cityId);
                                    regionSelect.html('<option value="">No regions available</option>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error loading regions:', error);
                                console.error('XHR:', xhr);
                                console.error('Status:', status);
                                console.error('Response Text:', xhr.responseText);
                                regionSelect.html('<option value="">Error loading regions</option>');
                            }
                        });
                    } else {
                        regionSelect.html('<option value="">{{ trans("lawyer.select_region") }}</option>');
                        regionSelect.prop('disabled', true);
                    }
                });

                // Trigger change event if city is already selected (edit mode or validation error)
                @if(isset($lawyer) && $lawyer->city_id)
                    citySelect.trigger('change');
                @elseif(old('city_id'))
                    citySelect.trigger('change');
                @endif
            });
        </script>
    @endpush
@endsection

{{-- Include Loading Overlay Component outside content section --}}
@push('after-body')
    <x-loading-overlay loadingText="Processing" loadingSubtext="Please wait" />
@endpush
