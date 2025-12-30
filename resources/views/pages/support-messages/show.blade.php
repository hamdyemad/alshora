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
            padding: 25px 30px !important;
            color: white !important;
        }
        .support-page-header h5 {
            margin: 0;
            font-weight: 600;
        }
        .support-message-content {
            background: linear-gradient(135deg, #f8f9fb 0%, #ffffff 100%);
            border-left: 4px solid #667eea;
            border-radius: 12px;
            padding: 24px;
        }
        .support-icon-box {
            width: 40px;
            height: 40px;
            min-width: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
        }
        .support-info-box {
            background: rgba(102, 126, 234, 0.05);
            border: 1px solid rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            padding: 16px;
        }
        .support-status-pill {
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
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
        .support-action-card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden;
        }
        .support-action-card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            padding: 16px 20px !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }
        .support-btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .support-btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            color: white !important;
        }
        .support-btn-outline {
            background: white !important;
            border: 2px solid #e9ecef !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            color: #6c757d !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .support-btn-outline:hover {
            border-color: #667eea !important;
            color: #667eea !important;
        }
        .support-btn-danger {
            background: rgba(220, 53, 69, 0.1) !important;
            border: 2px solid rgba(220, 53, 69, 0.2) !important;
            color: #dc3545 !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .support-btn-danger:hover {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }
        .support-form-select {
            border: 2px solid #e9ecef !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .support-form-select:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('support.support_messages'), 'url' => route('admin.support-messages.index')],
                    ['title' => trans('support.view_message')]
                ]" />
            </div>
        </div>

        <div class="row">
            {{-- Main Message Card --}}
            <div class="col-lg-8 mb-4">
                <div class="support-glass-container">
                    {{-- Header --}}
                    <div class="support-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5>
                            <i class="uil uil-envelope-open me-2"></i>{{ $message->title }}
                        </h5>
                        <span class="support-status-pill support-status-{{ $message->status }}">
                            @if($message->status == 'pending')
                                <i class="uil uil-clock me-1"></i>
                            @elseif($message->status == 'read')
                                <i class="uil uil-eye me-1"></i>
                            @else
                                <i class="uil uil-check-circle me-1"></i>
                            @endif
                            {{ trans('support.' . $message->status) }}
                        </span>
                    </div>

                    {{-- Body --}}
                    <div class="p-4">
                        {{-- Message Content --}}
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-bold mb-2 d-block" style="letter-spacing: 0.5px;">
                                <i class="uil uil-comment-alt-message me-1"></i>{{ trans('support.message') }}
                            </label>
                            <div class="support-message-content">
                                <p class="mb-0" style="line-height: 1.8; color: #5a5f7d; font-size: 15px;">
                                    {!! nl2br(e($message->message)) !!}
                                </p>
                            </div>
                        </div>

                        {{-- Info Grid --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="support-info-box">
                                    <label class="text-muted small text-uppercase fw-bold mb-2 d-block" style="font-size: 11px; letter-spacing: 0.5px;">
                                        {{ trans('support.user') }}
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <div class="support-icon-box me-3">
                                            <i class="uil uil-user"></i>
                                        </div>
                                        <span class="fw-500" style="color: #272b41;">
                                            @if($message->user)
                                                {{ $message->user->email }}
                                            @else
                                                <span class="text-muted">{{ trans('support.guest') }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="support-info-box">
                                    <label class="text-muted small text-uppercase fw-bold mb-2 d-block" style="font-size: 11px; letter-spacing: 0.5px;">
                                        {{ __('common.created_at') }}
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <div class="support-icon-box me-3">
                                            <i class="uil uil-calendar-alt"></i>
                                        </div>
                                        <span class="fw-500" style="color: #272b41;">
                                            {{ $message->created_at->format('Y-m-d H:i:s') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions Sidebar --}}
            <div class="col-lg-4 mb-4">
                {{-- Update Status Card --}}
                <div class="support-action-card mb-4">
                    <div class="support-action-card-header">
                        <h6 class="mb-0 fw-bold" style="color: #272b41;">
                            <i class="uil uil-sync me-2" style="color: #667eea;"></i>{{ trans('support.update_status') }}
                        </h6>
                    </div>
                    <div class="p-4">
                        <form id="statusForm">
                            @csrf
                            <div class="mb-4">
                                <select class="form-control support-form-select" name="status" id="statusSelect">
                                    <option value="pending" {{ $message->status == 'pending' ? 'selected' : '' }}>🕐 {{ trans('support.pending') }}</option>
                                    <option value="read" {{ $message->status == 'read' ? 'selected' : '' }}>👁️ {{ trans('support.read') }}</option>
                                    <option value="resolved" {{ $message->status == 'resolved' ? 'selected' : '' }}>✅ {{ trans('support.resolved') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn support-btn-gradient w-100">
                                <i class="uil uil-check me-1"></i>{{ trans('support.update_status') }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Quick Actions Card --}}
                <div class="support-action-card">
                    <div class="support-action-card-header">
                        <h6 class="mb-0 fw-bold" style="color: #272b41;">
                            <i class="uil uil-bolt me-2" style="color: #667eea;"></i>{{ __('common.actions') }}
                        </h6>
                    </div>
                    <div class="p-4">
                        <a href="{{ route('admin.support-messages.index') }}" class="btn support-btn-outline w-100 mb-3">
                            <i class="uil uil-arrow-left me-1"></i>{{ __('common.back_to_list') }}
                        </a>
                        
                        @can('support-messages.delete')
                        <hr style="border-color: #e9ecef;">
                        <button type="button" class="btn support-btn-danger w-100"
                                data-bs-toggle="modal" data-bs-target="#modal-delete-message"
                                data-item-id="{{ $message->id }}" data-item-name="{{ $message->title }}">
                            <i class="uil uil-trash-alt me-1"></i>{{ __('common.delete') }}
                        </button>
                        @endcan
                    </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#statusForm').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $(this).find('button[type="submit"]');
        const originalText = btn.html();
        btn.html('<i class="uil uil-spinner-alt uil-spin me-1"></i>{{ __("common.loading") }}...').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("admin.support-messages.update-status", $message->id) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: $('#statusSelect').val()
            },
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message, 'check-circle');
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                btn.html(originalText).prop('disabled', false);
                showMessage('danger', xhr.responseJSON?.message || '{{ __("common.error") }}', 'times-circle');
            }
        });
    });
});
</script>
@endpush

@push('after-body')
    <x-loading-overlay />
@endpush
