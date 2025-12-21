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
                    [
                        'title' => trans('agenda.preparer_agenda_management'),
                        'url' => route('admin.preparer-agendas.index'),
                    ],
                    ['title' => trans('agenda.view_preparer_agenda')],
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-500">{{ trans('agenda.preparer_agenda_details') }}</h5>
                        <div class="d-flex gap-10">
                            <a href="{{ route('admin.preparer-agendas.index') }}" class="btn btn-light btn-sm">
                                <i class="uil uil-arrow-left me-2"></i>{{ __('common.back_to_list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- User Information --}}
                            <div class="col-12 mb-30">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-user me-2"></i>{{ trans('agenda.user_information') }}
                                </h6>
                            </div>
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.user_name') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->user->name ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.user_email') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->user->email ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Preparer Agenda Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i
                                        class="uil uil-clipboard-notes me-2"></i>{{ trans('agenda.preparer_agenda_information') }}
                                </h6>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.court_bailiffs') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->court_bailiffs }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.paper_type') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->paper_type }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label
                                        class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.paper_delivery_date') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->paper_delivery_date->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.paper_number') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->paper_number }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.session_date') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->session_date->format('Y-m-d') }}</p>
                                </div>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.client_name') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->client_name }}</p>
                                </div>
                            </div>

                            <div class="col-md-12 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.notes') }}</label>
                                    <p class="fs-15 color-dark">{{ $agenda->notes ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Notification Information --}}
                            <div class="col-12 mb-30 mt-20">
                                <h6 class="fw-500 color-dark border-bottom pb-15 mb-20">
                                    <i class="uil uil-bell me-2"></i>{{ trans('agenda.notification_information') }}
                                </h6>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.datetime') }}</label>
                                    <p class="fs-15 color-dark fw-500">
                                        <i
                                            class="uil uil-calendar-alt me-2"></i>{{ $agenda->datetime->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label
                                        class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.notification_days') }}</label>
                                    <p class="fs-15 color-dark fw-500">{{ $agenda->notification_days }}
                                        {{ trans('agenda.days') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-25">
                                <div class="view-item">
                                    <label class="il-gray fs-14 fw-500 mb-10">{{ trans('agenda.is_notified') }}</label>
                                    <p class="fs-15">
                                        @if ($agenda->is_notified)
                                            <span class="badge bg-success">{{ trans('agenda.notified') }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ trans('agenda.pending') }}</span>
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
