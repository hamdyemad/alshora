@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('admin.admins_management'), 'url' => route('admin.admin-management.admins.index')],
                    ['title' => isset($admin) ? trans('admin.edit_admin') : trans('admin.add_admin')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 p-30 bg-white radius-xl mb-30">
                    <h4 class="mb-25 fw-500">{{ isset($admin) ? trans('admin.edit_admin') : trans('admin.add_admin') }}</h4>
                    
                    <form id="adminForm" 
                          action="{{ isset($admin) ? route('admin.admin-management.admins.update', $admin->id) : route('admin.admin-management.admins.store') }}" 
                          method="POST">
                        @csrf
                        @if(isset($admin))
                            @method('PUT')
                        @endif

                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-6 mb-25">
                                <div class="form-group">
                                    <label for="name" class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $admin->name ?? '') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6 mb-25">
                                <div class="form-group">
                                    <label for="email" class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.email') }} <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $admin->email ?? '') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6 mb-25">
                                <div class="form-group">
                                    <label for="password" class="il-gray fs-14 fw-500 mb-10">
                                        {{ trans('admin.password') }} 
                                        @if(!isset($admin))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="password" 
                                           class="form-control ih-medium ip-gray radius-xs b-light px-15 @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password"
                                           {{ !isset($admin) ? 'required' : '' }}>
                                    @if(isset($admin))
                                        <small class="text-muted">{{ __('common.leave_blank_to_keep_current') }}</small>
                                    @endif
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password Confirmation --}}
                            <div class="col-md-6 mb-25">
                                <div class="form-group">
                                    <label for="password_confirmation" class="il-gray fs-14 fw-500 mb-10">
                                        {{ trans('admin.password_confirmation') }}
                                        @if(!isset($admin))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    <input type="password" 
                                           class="form-control ih-medium ip-gray radius-xs b-light px-15" 
                                           id="password_confirmation" 
                                           name="password_confirmation"
                                           {{ !isset($admin) ? 'required' : '' }}>
                                </div>
                            </div>

                            {{-- Roles - Select2 Multiple --}}
                            <div class="col-md-6 mb-25">
                                <div class="form-group">
                                    <label for="roles" class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.roles') }} <span class="text-danger">*</span></label>
                                    <select multiple class="form-control select2 @error('roles') is-invalid @enderror" 
                                            id="roles" 
                                            name="roles[]" 
                                            multiple="multiple"
                                            required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" 
                                                {{ (isset($admin) && $admin->roles->contains($role->id)) || (old('roles') && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                                                {{ $role->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-25">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('admin.status') }}</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="is_blocked" 
                                               id="is_blocked" 
                                               value="1"
                                               {{ old('is_blocked', $admin->is_blocked ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_blocked">
                                            {{ trans('admin.is_blocked') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-default btn-squared">
                                <i class="uil uil-check"></i> {{ __('common.save') }}
                            </button>
                            <a href="{{ route('admin.admin-management.admins.index') }}" class="btn btn-light btn-default btn-squared">
                                <i class="uil uil-times"></i> {{ __('common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .select2-container--default .select2-selection--multiple {
        min-height: 44px;
        border: 1px solid #e3e6ef;
        border-radius: 4px;
        padding: 4px 8px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #5f63f2;
        border: none;
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        margin-top: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #5f63f2;
    }
    .select2-dropdown {
        border-color: #e3e6ef;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5f63f2;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for roles
    $('.select2-roles').select2({
        placeholder: '{{ trans("admin.select_roles") }}',
        allowClear: true,
        width: '100%'
    });

    // Form submission
    $('#adminForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const formData = new FormData(this);
        
        // Show loading
        if (window.LoadingOverlay) {
            window.LoadingOverlay.show();
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (window.LoadingOverlay) {
                    window.LoadingOverlay.hide();
                }
                
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => window.location.href = response.redirect, 1000);
                } else {
                    toastr.error(response.message || '{{ __("common.error") }}');
                }
            },
            error: function(xhr) {
                if (window.LoadingOverlay) {
                    window.LoadingOverlay.hide();
                }
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else if (xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    }
                } else {
                    toastr.error(xhr.responseJSON?.message || '{{ __("common.something_went_wrong") }}');
                }
            }
        });
    });
});
</script>
@endpush

@push('after-body')
    <x-loading-overlay />
@endpush
