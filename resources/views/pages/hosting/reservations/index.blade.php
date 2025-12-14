@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ __('hosting.hosting_reservations') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="uil uil-estate"></i>{{ __('menu.dashboard.title') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.hosting.index') }}">{{ __('hosting.hosting') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('hosting.hosting_reservations') }}</li>
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
                    <h6>{{ __('hosting.manage_reservations') }}</h6>
                    <a href="{{ route('admin.hosting.index') }}" class="btn btn-secondary btn-default btn-squared">
                        <i class="uil uil-arrow-left"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <div class="card card-light mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="uil uil-filter"></i> {{ __('common.filters') }}</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.hosting.reservations.index') }}" class="row g-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="search" class="form-label">{{ __('hosting.lawyer_name_or_email') }}</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            placeholder="{{ __('common.search') }}" value="{{ request('search') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="day" class="form-label">{{ __('hosting.day') }}</label>
                                        <select class="form-select" id="day" name="day">
                                            <option value="">{{ __('common.all') }}</option>
                                            <option value="Saturday" {{ request('day') === 'Saturday' ? 'selected' : '' }}>{{ __('hosting.saturday') }}</option>
                                            <option value="Sunday" {{ request('day') === 'Sunday' ? 'selected' : '' }}>{{ __('hosting.sunday') }}</option>
                                            <option value="Monday" {{ request('day') === 'Monday' ? 'selected' : '' }}>{{ __('hosting.monday') }}</option>
                                            <option value="Tuesday" {{ request('day') === 'Tuesday' ? 'selected' : '' }}>{{ __('hosting.tuesday') }}</option>
                                            <option value="Wednesday" {{ request('day') === 'Wednesday' ? 'selected' : '' }}>{{ __('hosting.wednesday') }}</option>
                                            <option value="Thursday" {{ request('day') === 'Thursday' ? 'selected' : '' }}>{{ __('hosting.thursday') }}</option>
                                            <option value="Friday" {{ request('day') === 'Friday' ? 'selected' : '' }}>{{ __('hosting.friday') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="status" class="form-label">{{ __('hosting.status') }}</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">{{ __('common.all') }}</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('hosting.pending') }}</option>
                                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('hosting.approved') }}</option>
                                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('hosting.rejected') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="date_from" class="form-label">{{ __('common.from_date') }}</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from"
                                            value="{{ request('date_from') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="date_to" class="form-label">{{ __('common.to_date') }}</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to"
                                            value="{{ request('date_to') }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="uil uil-search"></i> {{ __('common.search') }}
                                        </button>
                                    </div>

                                    <div class="col-md-3">
                                        <a href="{{ route('admin.hosting.reservations.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="uil uil-times"></i> {{ __('common.clear_filters') }}
                                        </a>
                                    </div>
                                </form>
                                </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ '#' }}</th>
                                    <th>{{ __('hosting.lawyer_name') }}</th>
                                    <th>{{ __('hosting.day') }}</th>
                                    <th>{{ __('hosting.time') }}</th>
                                    <th>{{ __('hosting.reason') }}</th>
                                    <th>{{ __('hosting.status') }}</th>
                                    <th>{{ __('hosting.requested_at') }}</th>
                                    <th class="text-center">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class="userDatatable-inline-title">
                                                    <a href="{{ route('admin.lawyers.show', $reservation->lawyer) }}" class="text-dark fw-500">
                                                        <h6>{{ $reservation->lawyer->name ?? 'N/A' }}</h6>
                                                    </a>
                                                    <p class="text-muted">{{ $reservation->lawyer->user->email ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                <span class="badge badge-round badge-lg bg-info">{{ ucfirst(__('hosting.' . strtolower($reservation->hostingTime->day))) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $reservation->hostingTime->from_time }} - {{ $reservation->hostingTime->to_time }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content--subject">
                                                {{ $reservation->reason ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content d-inline-block">
                                                @if($reservation->status === 'pending')
                                                    <span class="bg-opacity-warning color-warning userDatatable-content-status pending">{{ __('hosting.pending') }}</span>
                                                @elseif($reservation->status === 'approved')
                                                    <span class="bg-opacity-success color-success userDatatable-content-status active">{{ __('hosting.approved') }}</span>
                                                @elseif($reservation->status === 'rejected')
                                                    <span class="bg-opacity-danger color-danger userDatatable-content-status inactive">{{ __('hosting.rejected') }}</span>
                                                @else
                                                    <span class="bg-opacity-secondary color-secondary userDatatable-content-status">{{ $reservation->status }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content">
                                                {{ $reservation->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <ul class="orderDatatable_actions mb-0 d-flex flex-wrap justify-content-center gap-1">
                                                <li>
                                                    <a href="{{ route('admin.hosting.reservations.show', $reservation) }}" class="view" title="{{ __('common.view') }}">
                                                        <i class="uil uil-eye"></i>
                                                    </a>
                                                </li>
                                                @if($reservation->status === 'pending')
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                           class="edit"
                                                           title="{{ __('hosting.approve') }}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#approveModal{{ $reservation->id }}">
                                                            <i class="uil uil-check"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                           class="remove"
                                                           title="{{ __('hosting.reject') }}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#rejectModal{{ $reservation->id }}">
                                                            <i class="uil uil-times"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $reservation->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('hosting.approve_reservation') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.hosting.reservations.approve', $reservation) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="admin_notes{{ $reservation->id }}" class="form-label">{{ __('hosting.admin_notes') }}</label>
                                                            <textarea class="form-control" id="admin_notes{{ $reservation->id }}" name="admin_notes" rows="3" placeholder="{{ __('hosting.optional_notes') }}"></textarea>
                                                        </div>
                                                        <div class="alert alert-info">
                                                            <strong>{{ __('hosting.lawyer') }}:</strong> {{ $reservation->lawyer->user->name }}<br>
                                                            <strong>{{ __('hosting.day') }}:</strong> {{ $reservation->hostingTime->day }}<br>
                                                            <strong>{{ __('hosting.time') }}:</strong> {{ $reservation->hostingTime->from_time }} - {{ $reservation->hostingTime->to_time }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                                        <button type="submit" class="btn btn-success">{{ __('hosting.approve') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $reservation->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('hosting.reject_reservation') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.hosting.reservations.reject', $reservation) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="reject_notes{{ $reservation->id }}" class="form-label">{{ __('hosting.rejection_reason') }} <span class="text-danger">*</span></label>
                                                            <textarea class="form-control @error('admin_notes') is-invalid @enderror" id="reject_notes{{ $reservation->id }}" name="admin_notes" rows="3" placeholder="{{ __('hosting.please_provide_reason') }}" required></textarea>
                                                            @error('admin_notes')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="alert alert-info">
                                                            <strong>{{ __('hosting.lawyer') }}:</strong> {{ $reservation->lawyer->user->name }}<br>
                                                            <strong>{{ __('hosting.day') }}:</strong> {{ $reservation->hostingTime->day }}<br>
                                                            <strong>{{ __('hosting.time') }}:</strong> {{ $reservation->hostingTime->from_time }} - {{ $reservation->hostingTime->to_time }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                                        <button type="submit" class="btn btn-danger">{{ __('hosting.reject') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            {{ __('hosting.no_reservations_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($reservations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reservations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
