@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="breadcrumb-main">
                <h4 class="text-capitalize breadcrumb-title">{{ __('hosting.hosting_settings') }}</h4>
                <div class="breadcrumb-action justify-content-center flex-wrap">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="uil uil-estate"></i>{{ __('menu.dashboard.title') }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.hosting.index') }}">{{ __('hosting.hosting') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('hosting.hosting_settings') }}</li>
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
                    <h6>{{ __('hosting.manage_hosting_times') }}</h6>
                    <a href="{{ route('admin.hosting.index') }}" class="btn btn-secondary btn-default btn-squared">
                        <i class="uil uil-eye"></i>
                        {{ __('hosting.view_schedule') }}
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

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ __('common.validation_errors') }}</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.hosting.settings.store') }}" method="POST" id="hostingForm">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered" id="hostingTable">
                                <thead>
                                    <tr>
                                        <th width="20%">{{ __('hosting.day') }}</th>
                                        <th width="25%">{{ __('hosting.from_time') }}</th>
                                        <th width="25%">{{ __('hosting.to_time') }}</th>
                                        <th width="20%">{{ __('hosting.status') }}</th>
                                        <th width="10%">{{ __('hosting.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="timeSlotsBody">
                                    @foreach($days as $dayKey => $dayName)
                                        @php
                                            $daySlots = $hostingTimes->get($dayKey) ?? collect([]);
                                        @endphp

                                        @if($daySlots->isEmpty())
                                            {{-- Add one empty slot for each day if no slots exist --}}
                                            <tr class="time-slot-row" data-day="{{ $dayKey }}">
                                                <td>
                                                    <strong>{{ $dayName }}</strong>
                                                    <input type="hidden" name="time_slots[{{ $loop->index }}_0][day]" value="{{ $dayKey }}">
                                                    <input type="hidden" name="time_slots[{{ $loop->index }}_0][id]" value="">
                                                </td>
                                                <td>
                                                    <input
                                                        type="time"
                                                        name="time_slots[{{ $loop->index }}_0][from_time]"
                                                        class="form-control from-time"
                                                        value=""
                                                    >
                                                </td>
                                                <td>
                                                    <input
                                                        type="time"
                                                        name="time_slots[{{ $loop->index }}_0][to_time]"
                                                        class="form-control to-time"
                                                        value=""
                                                    >
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch form-switch-success">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="time_slots[{{ $loop->index }}_0][is_active]"
                                                            value="1"
                                                            checked
                                                        >
                                                        <label class="form-check-label">
                                                            {{ __('hosting.active') }}
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm add-time-btn" data-day="{{ $dayKey }}" data-day-name="{{ $dayName }}" title="{{ __('hosting.add_time_slot') }}">
                                                        <i class="uil uil-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($daySlots as $slotIndex => $slot)
                                                <tr class="time-slot-row" data-day="{{ $dayKey }}">
                                                    <td>
                                                        @if($slotIndex === 0)
                                                            <strong>{{ $dayName }}</strong>
                                                        @endif
                                                        <input type="hidden" name="time_slots[{{ $loop->parent->index }}_{{ $slotIndex }}][day]" value="{{ $dayKey }}">
                                                        <input type="hidden" name="time_slots[{{ $loop->parent->index }}_{{ $slotIndex }}][id]" value="{{ $slot->id }}">
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="time"
                                                            name="time_slots[{{ $loop->parent->index }}_{{ $slotIndex }}][from_time]"
                                                            class="form-control from-time"
                                                            value="{{ $slot->from_time }}"
                                                        >
                                                    </td>
                                                    <td>
                                                        <input
                                                            type="time"
                                                            name="time_slots[{{ $loop->parent->index }}_{{ $slotIndex }}][to_time]"
                                                            class="form-control to-time"
                                                            value="{{ $slot->to_time }}"
                                                        >
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch form-switch-success">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                name="time_slots[{{ $loop->parent->index }}_{{ $slotIndex }}][is_active]"
                                                                value="1"
                                                                {{ $slot->is_active ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label">
                                                                {{ __('hosting.active') }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($slotIndex === 0)
                                                            <button type="button" class="btn btn-success btn-sm add-time-btn" data-day="{{ $dayKey }}" data-day-name="{{ $dayName }}" title="{{ __('hosting.add_time_slot') }}">
                                                                <i class="uil uil-plus"></i>
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-danger btn-sm remove-time-btn" title="{{ __('hosting.remove_time_slot') }}">
                                                                <i class="uil uil-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-default btn-squared">
                                <i class="uil uil-check"></i>
                                {{ __('hosting.save_changes') }}
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
    let slotCounter = 1000; // Start with a high number to avoid conflicts

    // Add time slot
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-time-btn') || e.target.closest('.add-time-btn')) {
            const button = e.target.classList.contains('add-time-btn') ? e.target : e.target.closest('.add-time-btn');
            const day = button.getAttribute('data-day');
            const dayName = button.getAttribute('data-day-name');
            const currentRow = button.closest('tr');

            // Create new row
            const newRow = document.createElement('tr');
            newRow.className = 'time-slot-row';
            newRow.setAttribute('data-day', day);

            const uniqueId = `${day}_${slotCounter++}`;

            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="time_slots[${uniqueId}][day]" value="${day}">
                    <input type="hidden" name="time_slots[${uniqueId}][id]" value="">
                </td>
                <td>
                    <input
                        type="time"
                        name="time_slots[${uniqueId}][from_time]"
                        class="form-control from-time"
                        value=""
                    >
                </td>
                <td>
                    <input
                        type="time"
                        name="time_slots[${uniqueId}][to_time]"
                        class="form-control to-time"
                        value=""
                    >
                </td>
                <td>
                    <div class="form-check form-switch form-switch-success">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="time_slots[${uniqueId}][is_active]"
                            value="1"
                            checked
                        >
                        <label class="form-check-label">
                            {{ __('hosting.active') }}
                        </label>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-time-btn" title="{{ __('hosting.remove_time_slot') }}">
                        <i class="uil uil-trash-alt"></i>
                    </button>
                </td>
            `;

            // Find the last row for this day
            let lastRowForDay = currentRow;
            let nextRow = currentRow.nextElementSibling;

            while (nextRow && nextRow.getAttribute('data-day') === day) {
                lastRowForDay = nextRow;
                nextRow = nextRow.nextElementSibling;
            }

            // Insert after the last row for this day
            lastRowForDay.after(newRow);

            e.preventDefault();
        }
    });

    // Remove time slot
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-time-btn') || e.target.closest('.remove-time-btn')) {
            const button = e.target.classList.contains('remove-time-btn') ? e.target : e.target.closest('.remove-time-btn');
            const row = button.closest('tr');

            if (confirm('{{ __('hosting.confirm_remove_slot') }}')) {
                row.remove();
            }

            e.preventDefault();
        }
    });
});
</script>
@endpush
@endsection
