@extends('layout.app')

@section('content')
    {{-- Include Loading Overlay Component --}}
    <x-loading-overlay 
        :loadingText="trans('loading.deleting')" 
        :loadingSubtext="trans('loading.please_wait')" 
    />

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    [
                        'title' => trans('dashboard.title'),
                        'url' => route('admin.dashboard'),
                        'icon' => 'uil uil-estate'
                    ],
                    [
                        'title' => __('Vendors Management'),
                        'url' => route('admin.vendors.index')
                    ],
                    [
                        'title' => __('View Vendor')
                    ]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="userDatatable global-shadow border-light-0 bg-white radius-xl w-100 mb-30">
                    <div class="card-header py-20 px-25 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('Vendor Details') }}</h6>
                        <div class="button-group d-flex" style="gap: 10px;">
                            <a href="{{ route('admin.vendors.edit', $vendor->id) }}" 
                               class="btn btn-primary btn-sm btn-squared text-capitalize">
                                <i class="uil uil-edit"></i> {{ __('Edit Vendor') }}
                            </a>
                            <a href="{{ route('admin.vendors.index') }}" 
                               class="btn btn-light btn-sm btn-squared text-capitalize">
                                <i class="uil uil-angle-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i> {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-25">
                        <!-- Vendor Information -->
                        <div class="row mb-30">
                            <div class="col-12">
                                <h6 class="mb-20 fw-500 color-dark border-bottom pb-15">
                                    <i class="uil uil-info-circle me-2"></i>{{ __('Vendor Information') }}
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Vendor ID') }}</label>
                                    <p class="fs-15 fw-400 color-dark">#{{ $vendor->id }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Name') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $vendor->name }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Status') }}</label>
                                    <p class="fs-15 fw-400 color-dark">
                                        @if($vendor->status == 'active')
                                            <span class="badge badge-success" style="border-radius: 6px; padding: 6px 12px;">
                                                <i class="uil uil-check-circle me-1"></i>{{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger" style="border-radius: 6px; padding: 6px 12px;">
                                                <i class="uil uil-times-circle me-1"></i>{{ __('Inactive') }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Email') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $vendor->email }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Phone') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $vendor->phone ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Commission Rate') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $vendor->commission_rate ?? '0' }}%</p>
                                </div>
                            </div>

                            <div class="col-md-12 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Address') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $vendor->address ?? '-' }}</p>
                                </div>
                            </div>

                            @if($vendor->logo)
                            <div class="col-md-12 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Logo') }}</label>
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $vendor->logo) }}" alt="Logo" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-12 mb-20">
                                <div class="form-group">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ __('Description') }}</label>
                                    <p class="fs-15 fw-400 color-dark">{{ $vendor->description ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="row mb-30">
                            <div class="col-12">
                                <h6 class="mb-20 fw-500 color-dark border-bottom pb-15">
                                    <i class="uil uil-clock me-2"></i>{{ __('Timestamps') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-20">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-20">
                                        <div class="d-flex align-items-center mb-10">
                                            <i class="uil uil-calendar-alt me-2 color-primary"></i>
                                            <strong class="fs-14">{{ __('Created At') }}</strong>
                                        </div>
                                        <p class="fs-15 fw-400 color-dark mb-0">
                                            {{ $vendor->created_at->format('Y-m-d H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-20">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-20">
                                        <div class="d-flex align-items-center mb-10">
                                            <i class="uil uil-clock-three me-2 color-primary"></i>
                                            <strong class="fs-14">{{ __('Updated At') }}</strong>
                                        </div>
                                        <p class="fs-15 fw-400 color-dark mb-0">
                                            {{ $vendor->updated_at->format('Y-m-d H:i:s') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="button-group d-flex justify-content-end" style="gap: 10px;">
                                    <a href="{{ route('admin.vendors.edit', $vendor->id) }}" 
                                       class="btn btn-primary btn-default btn-squared text-capitalize">
                                        <i class="uil uil-edit"></i> {{ __('Edit Vendor') }}
                                    </a>
                                    <button type="button" 
                                            class="btn btn-danger btn-default btn-squared text-capitalize"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-delete-vendor"
                                            data-vendor-id="{{ $vendor->id }}"
                                            data-vendor-name="{{ $vendor->name }}">
                                        <i class="uil uil-trash-alt"></i> {{ __('Delete Vendor') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal Component --}}
    <x-delete-modal 
        modalId="modal-delete-vendor"
        :title="__('Confirm Delete')"
        :message="__('Are you sure you want to delete this vendor?')"
        itemNameId="delete-vendor-name"
        confirmBtnId="confirmDeleteBtn"
        :deleteRoute="route('admin.vendors.index')"
        :cancelText="__('Cancel')"
        :deleteText="__('Delete Vendor')"
    />
@endsection
