@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">{{ __('hosting.reservation_details') }}</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                            class="uil uil-estate"></i>{{ __('menu.dashboard.title') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.hosting.index') }}">{{ __('hosting.hosting') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('admin.hosting.reservations.index') }}">{{ __('hosting.hosting_reservations') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ __('hosting.reservation_details') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-default card-md mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6>{{ __('hosting.reservation_information') }}</h6>
                        <a href="{{ route('admin.hosting.reservations.index') }}"
                            class="btn btn-secondary btn-default btn-squared">
                            <i class="uil uil-arrow-left"></i>
                            {{ __('common.back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-light mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="uil uil-user-md"></i>
                                            {{ __('hosting.lawyer_information') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label
                                                class="form-label text-muted small">{{ __('hosting.lawyer_name') }}</label>
                                            <p class="fw-bold mb-0">{{ $reservation->lawyer->user->name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">{{ __('hosting.email') }}</label>
                                            <p class="fw-bold mb-0">{{ $reservation->lawyer->user->email }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label text-muted small">{{ __('hosting.phone') }}</label>
                                            <p class="fw-bold mb-0">
                                                {{ $reservation->lawyer->phone ?? __('common.not_available') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-light mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0"><i class="uil uil-calendar-alt"></i>
                                            {{ __('hosting.hosting_slot_information') }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">{{ __('hosting.day') }}</label>
                                            <p class="fw-bold mb-0"><span
                                                    class="badge badge-round badge-lg bg-info">{{ ucfirst(__('hosting.' . strtolower($reservation->hostingTime->day))) }}</span>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small">{{ __('hosting.time') }}</label>
                                            <p class="fw-bold mb-0">{{ $reservation->hostingTime->from_time }} -
                                                {{ $reservation->hostingTime->to_time }}</p>
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label text-muted small">{{ __('hosting.status') }}</label>
                                            <p class="fw-bold mb-0">
                                                @if ($reservation->status === 'pending')
                                                    <span
                                                        class="badge badge-round badge-lg bg-warning text-dark">{{ __('hosting.pending') }}</span>
                                                @elseif($reservation->status === 'approved')
                                                    <span
                                                        class="badge badge-round badge-lg bg-success">{{ __('hosting.approved') }}</span>
                                                @elseif($reservation->status === 'rejected')
                                                    <span
                                                        class="badge badge-round badge-lg bg-danger">{{ __('hosting.rejected') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-round badge-lg bg-secondary">{{ $reservation->status }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            @if ($reservation->status === 'pending')
                                <div class="col-lg-12">
                                    <div class="card card-md">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="uil uil-bolt"></i> {{ __('hosting.take_action') }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-grid gap-3">
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal">
                                                    <i class="uil uil-check-circle"></i>
                                                    {{ __('hosting.approve_request') }}
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal">
                                                    <i class="uil uil-times-circle"></i> {{ __('hosting.reject_request') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('hosting.approve_reservation') }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.hosting.reservations.approve', $reservation) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="admin_notes"
                                                            class="form-label">{{ __('hosting.admin_notes') }}</label>
                                                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3"
                                                            placeholder="{{ __('hosting.optional_notes') }}"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                                    <button type="submit"
                                                        class="btn btn-success">{{ __('hosting.approve') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ __('hosting.reject_reservation') }}</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.hosting.reservations.reject', $reservation) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reject_notes"
                                                            class="form-label">{{ __('hosting.rejection_reason') }} <span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="reject_notes" name="admin_notes"
                                                            rows="3" placeholder="{{ __('hosting.please_provide_reason') }}" required></textarea>
                                                        @error('admin_notes')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                                    <button type="submit"
                                                        class="btn btn-danger">{{ __('hosting.reject') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card card-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="uil uil-file-text"></i> {{ __('hosting.request_details') }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">{{ __('hosting.reason') }}</label>
                                    <p class="fw-bold mb-0">{{ $reservation->reason ?? __('hosting.no_reason_provided') }}
                                    </p>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-muted small">{{ __('hosting.requested_at') }}</label>
                                    <p class="fw-bold mb-0">{{ $reservation->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($reservation->status !== 'pending')
                            <div class="card card-light mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="uil uil-check-circle"></i>
                                        {{ __('hosting.admin_decision') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">{{ __('hosting.decided_by') }}</label>
                                        <p class="fw-bold mb-0">
                                            {{ $reservation->approvedBy->email ?? __('common.not_available') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">{{ __('hosting.decided_at') }}</label>
                                        <p class="fw-bold mb-0">{{ $reservation->approved_at->format('Y-m-d H:i:s') }}</p>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label text-muted small">{{ __('hosting.admin_notes') }}</label>
                                        <p class="fw-bold mb-0">{{ $reservation->admin_notes ?? __('hosting.no_notes') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
