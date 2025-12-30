@extends('layout.app')

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
                    ['title' => trans('client_agenda.client_agenda_management'), 'url' => route('admin.client-agendas.index')],
                    ['title' => trans('client_agenda.view_client_agenda')],
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-500">{{ trans('client_agenda.client_agenda_details') }}</h5>
                        <div class="d-flex gap-10">
                            <a href="{{ route('admin.client-agendas.index') }}" class="btn btn-light btn-sm">
                                <i class="uil uil-arrow-left me-2"></i>{{ __('common.back_to_list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- User Information --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-user me-2"></i>{{ trans('client_agenda.user_information') }}
                                </h6>
                            </div>
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.user_name') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $clientAgenda->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.user_email') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $clientAgenda->user->email ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Client Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-users-alt me-2"></i>{{ trans('client_agenda.client_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.client_name') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $clientAgenda->client_name }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.client_phone') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        @if($clientAgenda->client_phone)
                                            <i class="uil uil-phone me-2"></i>{{ $clientAgenda->client_phone }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.client_inquiry') }}</label>
                                    <p class="fs-15 color-dark">{{ $clientAgenda->client_inquiry ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.follow_up_response') }}</label>
                                    <p class="fs-15 color-dark">{{ $clientAgenda->follow_up_response ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Notification Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-bell me-2"></i>{{ trans('client_agenda.notification_information') }}
                                </h6>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.follow_up_date') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i class="uil uil-calendar-alt me-2"></i>{{ $clientAgenda->follow_up_date->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.notification_days') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $clientAgenda->notification_days }} {{ trans('client_agenda.days') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('client_agenda.is_notified') }}</label>
                                    <p class="fs-15">
                                        @if ($clientAgenda->is_notified)
                                            <span class="badge bg-success">{{ trans('client_agenda.notified') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ trans('client_agenda.pending') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .view-item label {
            color: #9299b8;
            margin-bottom: 8px;
        }

        .view-item p {
            margin-bottom: 0;
            font-weight: 500;
        }
    </style>
@endpush
