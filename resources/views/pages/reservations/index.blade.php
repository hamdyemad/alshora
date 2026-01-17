@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('reservation.reservations_management')]
                ]" />
            </div>
        </div>

        {{-- Header Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">{{ trans('reservation.reservations_management') }}</h4>
                                <p class="text-muted mb-0">{{ trans('reservation.manage_all_appointments') }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge badge-lg badge-round badge badge-lg badge-round-round badge badge-lg badge-round-lg bg-primary fs-14 px-3 py-2">
                                    {{ $appointments->total() }} {{ trans('reservation.total_reservations') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Card --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('admin.reservations.index') }}" id="filterForm">
                            <div class="row align-items-end">
                                {{-- Search --}}
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">{{ trans('reservation.search') }}</label>
                                    <input type="text" name="search" class="form-control"
                                           placeholder="{{ trans('reservation.search_placeholder') }}"
                                           value="{{ request('search') }}">
                                </div>

                                {{-- Status Filter --}}
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">{{ trans('reservation.status') }}</label>
                                    <select name="status" class="form-select">
                                        <option value="">{{ trans('reservation.all_status') }}</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ trans('reservation.' . $status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Lawyer Filter --}}
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">{{ trans('reservation.lawyer') }}</label>
                                    <x-lawyer-select 
                                        name="lawyer_id" 
                                        :selected="request('lawyer_id')"
                                        :placeholder="trans('reservation.all_lawyers')"
                                    />
                                </div>

                                {{-- Date From --}}
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">{{ trans('reservation.date_from') }}</label>
                                    <input type="date" name="date_from" class="form-control"
                                           value="{{ request('date_from') }}">
                                </div>

                                {{-- Date To --}}
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">{{ trans('reservation.date_to') }}</label>
                                    <input type="date" name="date_to" class="form-control"
                                           value="{{ request('date_to') }}">
                                </div>

                                {{-- Filter Button --}}
                                <div class="col-md-1 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="uil uil-filter m-0"></i>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Reset Button Row --}}
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="uil uil-redo me-1"></i>
                                        {{ trans('common.clear_filters') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointments Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold">{{ trans('reservation.id') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.customer') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.lawyer') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.date_time') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.consultation_price') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.status') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.created') }}</th>
                                        <th class="border-0 fw-bold">{{ trans('reservation.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $appointment)
                                        <tr>
                                            <td class="py-3">
                                                <span class="fw-bold text-primary">#{{ $appointment->id }}</span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle me-2 overflow-hidden" style="width: 40px; height: 40px;">
                                                        @if($appointment->customer->logo)
                                                            <img src="{{ asset('storage/' . $appointment->customer->logo->path) }}"
                                                                 alt="{{ $appointment->customer->name }}"
                                                                 class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                                                <i class="uil uil-user text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $appointment->customer->name ?? trans('reservation.unknown_customer') }}</div>
                                                        <small class="text-muted">{{ $appointment->customer->user->email ?? trans('reservation.no_email') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle me-2 overflow-hidden" style="width: 40px; height: 40px;">
                                                        @if($appointment->lawyer->profile_image)
                                                            <img src="{{ asset('storage/' . $appointment->lawyer->profile_image->path) }}"
                                                                 alt="{{ $appointment->lawyer->name }}"
                                                                 class="w-100 h-100 object-fit-cover">
                                                        @else
                                                            <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center w-100 h-100">
                                                                <i class="uil uil-briefcase text-primary"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $appointment->lawyer->name ?? trans('reservation.unknown_lawyer') }}</div>
                                                        <small class="text-muted">{{ $appointment->lawyer->user->email ?? trans('reservation.no_email') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div>
                                                    <div class="fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                                                    <small class="text-muted d-block">{{ trans('reservation.' . $appointment->day) }}</small>
                                                    <small class="text-muted">
                                                        @if($appointment->lawyerOfficeHour)
                                                            {{ \Carbon\Carbon::parse($appointment->lawyerOfficeHour->from_time)->format('h:i A') }} -
                                                            {{ \Carbon\Carbon::parse($appointment->lawyerOfficeHour->to_time)->format('h:i A') }}
                                                        @else
                                                            {{ \Carbon\Carbon::parse($appointment->time_slot)->format('h:i A') }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="fw-medium text-success">
                                                    {{ number_format($appointment->consultation_price ?? 0, 2) }} 
                                                    <small class="text-muted">{{ trans('common.egp') }}</small>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @if(in_array($appointment->status, ['completed', 'rejected']))
                                                    {{-- Show badge badge-lg badge-round for final statuses --}}
                                                    <span class="badge badge-lg badge-round 
                                                        @if($appointment->status == 'completed') bg-success
                                                        @elseif($appointment->status == 'rejected') bg-danger
                                                        @endif
                                                        px-3 py-2">
                                                        {{ trans('reservation.' . $appointment->status) }}
                                                    </span>
                                                @else
                                                    {{-- Show select for editable statuses --}}
                                                    <select class="form-select form-select-sm status-select"
                                                            data-appointment-id="{{ $appointment->id }}"
                                                            style="width: auto;">
                                                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>
                                                            {{ trans('reservation.pending') }}
                                                        </option>
                                                        <option value="approved" {{ $appointment->status == 'approved' ? 'selected' : '' }}>
                                                            {{ trans('reservation.approved') }}
                                                        </option>
                                                        <option value="rejected" {{ $appointment->status == 'rejected' ? 'selected' : '' }}>
                                                            {{ trans('reservation.rejected') }}
                                                        </option>
                                                        <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>
                                                            {{ trans('reservation.completed') }}
                                                        </option>
                                                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>
                                                            {{ trans('reservation.cancelled') }}
                                                        </option>
                                                    </select>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <small class="text-muted">{{ $appointment->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.reservations.show', $appointment->id) }}"
                                                       class="btn btn-sm btn-outline-primary" title="{{ trans('reservation.view_details') }}">
                                                        <i class="uil uil-eye m-0"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="uil uil-calendar-alt" style="font-size: 48px;"></i>
                                                    <p class="mt-3 mb-0">{{ trans('reservation.no_reservations_found') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($appointments->hasPages())
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        {{ trans('reservation.showing_results', [
                                            'first' => $appointments->firstItem(),
                                            'last' => $appointments->lastItem(),
                                            'total' => $appointments->total()
                                        ]) }}
                                    </div>
                                    {{ $appointments->withQueryString()->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Update Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle status change
            document.querySelectorAll('.status-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const appointmentId = this.dataset.appointmentId;
                    const newStatus = this.value;

                    fetch(`/{{ LaravelLocalization::getCurrentLocale() }}/admin/reservations/${appointmentId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message using the custom message system
                            showMessage('success', '{{ trans('reservation.appointment_status_updated') }}', 'check-circle');
                            // Reload page after short delay to show the message
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Revert the select value
                            select.value = select.dataset.originalValue;
                            showMessage('danger', '{{ trans('reservation.failed_to_update_status') }}: ' + (data.message || 'Unknown error'), 'exclamation-triangle');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revert the select value
                        select.value = select.dataset.originalValue;
                        showMessage('danger', '{{ trans('reservation.error_updating_status') }}: ' + error.message, 'exclamation-triangle');
                    });
                });

                // Store original value
                select.dataset.originalValue = select.value;
            });
        });
    </script>
@endsection
