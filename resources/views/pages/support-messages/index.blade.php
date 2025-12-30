@extends('layout.app')

@section('content')
    <style>
        .support-glass-container {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden;
        }
        .support-page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 24px 24px 0 0;
            padding: 25px 30px !important;
            color: white !important;
        }
        .support-page-header h4 {
            margin: 0;
            font-weight: 600;
            color: white !important;
        }
        .support-filter-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            border-radius: 16px !important;
            padding: 20px !important;
            margin: 20px !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }
        .support-filter-input {
            border: 2px solid #e9ecef !important;
            border-radius: 10px !important;
            padding: 10px 15px !important;
            transition: all 0.3s ease;
        }
        .support-filter-input:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
        }
        .support-btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 10px 20px !important;
            color: white !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .support-btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            color: white !important;
        }
        .support-btn-reset {
            background: white !important;
            border: 2px solid #e9ecef !important;
            border-radius: 10px !important;
            padding: 10px 20px !important;
            color: #6c757d !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .support-btn-reset:hover {
            border-color: #667eea !important;
            color: #667eea !important;
        }
        .support-message-table {
            margin: 0 20px 20px !important;
        }
        .support-message-table table {
            border-collapse: separate !important;
            border-spacing: 0 10px !important;
        }
        .support-message-table thead th {
            background: transparent !important;
            border: none !important;
            color: #6c757d !important;
            font-size: 0.75rem !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            padding: 15px 20px !important;
        }
        .support-message-table tbody tr {
            background: white !important;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }
        .support-message-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }
        .support-message-table tbody td {
            padding: 18px 20px !important;
            border: none !important;
            vertical-align: middle !important;
        }
        .support-message-table tbody td:first-child {
            border-radius: 12px 0 0 12px;
        }
        .support-message-table tbody td:last-child {
            border-radius: 0 12px 12px 0;
        }
        .support-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .support-user-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .support-user-email {
            font-weight: 500;
            color: #2d3436;
        }
        .support-message-title {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 4px;
        }
        .support-message-preview {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .support-status-pill {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .support-status-pending {
            background: rgba(255, 193, 7, 0.15) !important;
            color: #d39e00 !important;
        }
        .support-status-read {
            background: rgba(23, 162, 184, 0.15) !important;
            color: #117a8b !important;
        }
        .support-status-resolved {
            background: rgba(40, 167, 69, 0.15) !important;
            color: #1e7e34 !important;
        }
        .support-date-info {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .support-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none !important;
            margin: 0 3px;
        }
        .support-action-btn.view {
            background: rgba(102, 126, 234, 0.1) !important;
            color: #667eea !important;
        }
        .support-action-btn.view:hover {
            background: #667eea !important;
            color: white !important;
        }
        .support-action-btn.delete {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }
        .support-action-btn.delete:hover {
            background: #dc3545 !important;
            color: white !important;
        }
        .support-empty-state {
            padding: 60px 20px;
            text-align: center;
        }
        .support-empty-state i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
            display: block;
        }
        .support-empty-state p {
            color: #6c757d;
            font-size: 1.1rem;
        }
        .support-pagination-wrapper {
            padding: 20px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('support.support_messages')]
                ]" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="support-glass-container">
                    {{-- Header --}}
                    <div class="support-page-header d-flex justify-content-between align-items-center">
                        <h4><i class="uil uil-comment-alt-message me-2"></i>{{ trans('support.support_messages') }}</h4>
                        <button class="btn btn-light btn-sm rounded-pill px-4" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="uil uil-filter me-1"></i>{{ __('common.filter') }}
                        </button>
                    </div>

                    {{-- Filters --}}
                    <div class="collapse {{ ($filters['search'] ?? false || $filters['status'] ?? false) ? 'show' : '' }}" id="filterCollapse">
                        <div class="support-filter-card">
                            <form method="GET" action="{{ route('admin.support-messages.index') }}">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold mb-2">{{ __('common.search') }}</label>
                                        <input type="text" class="form-control support-filter-input" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ trans('support.search_placeholder') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted fw-bold mb-2">{{ trans('support.status') }}</label>
                                        <select class="form-control support-filter-input" name="status">
                                            <option value="">{{ __('common.all') }}</option>
                                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>{{ trans('support.pending') }}</option>
                                            <option value="read" {{ ($filters['status'] ?? '') == 'read' ? 'selected' : '' }}>{{ trans('support.read') }}</option>
                                            <option value="resolved" {{ ($filters['status'] ?? '') == 'resolved' ? 'selected' : '' }}>{{ trans('support.resolved') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted fw-bold mb-2">{{ __('common.date_from') }}</label>
                                        <input type="date" class="form-control support-filter-input" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small text-muted fw-bold mb-2">{{ __('common.date_to') }}</label>
                                        <input type="date" class="form-control support-filter-input" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn support-btn-filter">
                                                <i class="uil uil-search me-1"></i>{{ __('common.search') }}
                                            </button>
                                            <a href="{{ route('admin.support-messages.index') }}" class="btn support-btn-reset">
                                                <i class="uil uil-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="support-message-table">
                        @if($messages->count() > 0)
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('support.user') }}</th>
                                    <th>{{ trans('support.title') }}</th>
                                    <th>{{ trans('support.status') }}</th>
                                    <th>{{ __('common.created_at') }}</th>
                                    <th class="text-center">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-muted">{{ $loop->iteration + ($messages->currentPage() - 1) * $messages->perPage() }}</span>
                                    </td>
                                    <td>
                                        <div class="support-user-info">
                                            <div class="support-user-avatar">
                                                @if($message->user)
                                                    {{ strtoupper(substr($message->user->email, 0, 1)) }}
                                                @else
                                                    <i class="uil uil-user"></i>
                                                @endif
                                            </div>
                                            <div class="support-user-email">
                                                @if($message->user)
                                                    {{ $message->user->email }}
                                                @else
                                                    <span class="text-muted">{{ trans('support.guest') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="support-message-title">{{ Str::limit($message->title, 40) }}</div>
                                        <div class="support-message-preview">{{ Str::limit($message->message, 50) }}</div>
                                    </td>
                                    <td>
                                        <span class="support-status-pill support-status-{{ $message->status }}">
                                            {{ trans('support.' . $message->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="support-date-info">
                                            <i class="uil uil-calendar-alt me-1"></i>{{ $message->created_at->format('Y-m-d') }}
                                            <br>
                                            <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @can('support-messages.view')
                                        <a href="{{ route('admin.support-messages.show', $message->id) }}" class="support-action-btn view" title="{{ __('common.view') }}">
                                            <i class="uil uil-eye"></i>
                                        </a>
                                        @endcan
                                        @can('support-messages.delete')
                                        <button type="button" class="support-action-btn delete" title="{{ __('common.delete') }}"
                                                data-bs-toggle="modal" data-bs-target="#modal-delete-message"
                                                data-item-id="{{ $message->id }}" data-item-name="{{ $message->title }}">
                                            <i class="uil uil-trash-alt"></i>
                                        </button>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="support-empty-state">
                            <i class="uil uil-comment-alt-message"></i>
                            <p>{{ trans('support.no_messages_found') }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if($messages->hasPages())
                    <div class="support-pagination-wrapper d-flex justify-content-end">
                        {{ $messages->appends(request()->all())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <x-delete-modal 
        modalId="modal-delete-message"
        title="{{ trans('support.confirm_delete') }}"
        message="{{ trans('support.delete_confirmation') }}"
        itemNameId="delete-message-name"
        confirmBtnId="confirmDeleteMessageBtn"
        :deleteRoute="route('admin.support-messages.index')"
        cancelText="{{ __('common.cancel') }}"
        deleteText="{{ __('common.delete') }}"
    />
@endsection

@push('after-body')
    <x-loading-overlay />
@endpush
