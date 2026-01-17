@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('menu.accounting.title'), 'url' => route('admin.accounting.index')],
                    ['title' => $lawyer->getTranslation('name', app()->getLocale())]
                ]" />
            </div>
        </div>

        {{-- Lawyer Info Card --}}
        <div class="row mb-25">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="rounded-circle bg-primary-transparent d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="uil uil-user text-primary" style="font-size: 32px;"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-1">{{ $lawyer->getTranslation('name', app()->getLocale()) }}</h4>
                                    <p class="text-muted mb-0">{{ $lawyer->user?->email }}</p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.lawyers.show', $lawyer->id) }}" class="btn btn-outline-primary">
                                    <i class="uil uil-eye"></i> {{ __('lawyer.view_lawyer') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="row mb-25">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.accounting.show', $lawyer->id) }}">
                            <div class="row align-items-end">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">{{ __('accounting.type') }}</label>
                                    <select name="type" class="form-control">
                                        <option value="">{{ __('accounting.all_types') }}</option>
                                        <option value="income" {{ $type == 'income' ? 'selected' : '' }}>{{ __('accounting.income') }}</option>
                                        <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>{{ __('accounting.expenses') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">{{ __('common.from_date') }}</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">{{ __('common.to_date') }}</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="uil uil-filter"></i> {{ __('accounting.filter') }}
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('admin.accounting.show', $lawyer->id) }}" class="btn btn-secondary w-100">
                                        <i class="uil uil-redo"></i> {{ __('common.reset') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-md-4 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2">{{ __('accounting.income') }}</p>
                                <h3 class="mb-0 text-success">{{ number_format($income, 2) }} {{ __('common.egp') }}</h3>
                            </div>
                            <div class="icon-box bg-success-transparent">
                                <i class="uil uil-arrow-growth text-success" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2">{{ __('accounting.expenses') }}</p>
                                <h3 class="mb-0 text-danger">{{ number_format($expenses, 2) }} {{ __('common.egp') }}</h3>
                            </div>
                            <div class="icon-box bg-danger-transparent">
                                <i class="uil uil-arrow-down text-danger" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2">{{ __('accounting.net_profit') }}</p>
                                <h3 class="mb-0 {{ $profit >= 0 ? 'text-primary' : 'text-danger' }}">
                                    {{ number_format($profit, 2) }} {{ __('common.egp') }}
                                </h3>
                            </div>
                            <div class="icon-box {{ $profit >= 0 ? 'bg-primary-transparent' : 'bg-danger-transparent' }}">
                                <i class="uil uil-dollar-alt {{ $profit >= 0 ? 'text-primary' : 'text-danger' }}" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="row">
            <div class="col-lg-6 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h6 class="mb-0 fw-500">{{ __('accounting.income_vs_expenses') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeExpenseChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h6 class="mb-0 fw-500">{{ __('accounting.transactions_by_category') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Transaction Button --}}
        <div class="row mb-25">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                    <i class="uil uil-plus"></i> {{ __('accounting.add_transaction') }}
                </button>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">{{ __('accounting.transactions') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('accounting.transaction_date') }}</th>
                                        <th>{{ __('accounting.type') }}</th>
                                        <th>{{ __('accounting.category') }}</th>
                                        <th>{{ __('accounting.description') }}</th>
                                        <th class="text-end">{{ __('accounting.amount') }}</th>
                                        <th>{{ __('accounting.appointment') }}</th>
                                        <th class="text-center">{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->id }}</td>
                                            <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                                            <td>
                                                @if($transaction->type == 'income')
                                                    <span class="badge badge-lg badge-round bg-success">{{ __('accounting.income') }}</span>
                                                @else
                                                    <span class="badge badge-lg badge-round bg-danger">{{ __('accounting.expenses') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->category)
                                                    <span class="badge badge-lg badge-round bg-secondary">{{ __('accounting.'.$transaction->category) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $transaction->description ?? '-' }}</td>
                                            <td class="text-end fw-bold {{ $transaction->type == 'income' ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($transaction->amount, 2) }}
                                            </td>
                                            <td>
                                                @if($transaction->appointment_id)
                                                    <span class="badge badge-lg badge-round bg-info">#{{ $transaction->appointment_id }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$transaction->appointment_id)
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteTransaction({{ $transaction->id }})">
                                                        <i class="uil uil-trash m-0"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="uil uil-file-slash" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="text-muted mt-2">{{ __('accounting.no_transactions_found') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($transactions->hasPages())
                        <div class="card-footer bg-white border-top">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Add Transaction Modal --}}
    <div class="modal fade" id="addTransactionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('accounting.add_transaction') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addTransactionForm">
                    @csrf
                    <input type="hidden" name="lawyer_id" value="{{ $lawyer->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('accounting.type') }} <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required>
                                <option value="">{{ __('common.select') }}</option>
                                <option value="income">{{ __('accounting.income') }}</option>
                                <option value="expense">{{ __('accounting.expenses') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('accounting.amount') }} <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('accounting.category') }}</label>
                            <select name="category" class="form-control">
                                <option value="">{{ __('accounting.select_category') }}</option>
                                <option value="consultation">{{ __('accounting.consultation') }}</option>
                                <option value="subscription">{{ __('accounting.subscription') }}</option>
                                <option value="office_rent">{{ __('accounting.office_rent') }}</option>
                                <option value="utilities">{{ __('accounting.utilities') }}</option>
                                <option value="marketing">{{ __('accounting.marketing') }}</option>
                                <option value="salary">{{ __('accounting.salary') }}</option>
                                <option value="other">{{ __('accounting.other') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('accounting.description') }}</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('accounting.transaction_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('common.confirm') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('common.are_you_sure') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">{{ __('common.delete') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Income vs Expense Chart
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(incomeExpenseCtx, {
        type: 'bar',
        data: {
            labels: ['{{ __("accounting.income") }}', '{{ __("accounting.expenses") }}', '{{ __("accounting.profit") }}'],
            datasets: [{
                label: '{{ __("accounting.amount") }}',
                data: [{{ $income }}, {{ $expenses }}, {{ $profit }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    '{{ $profit >= 0 ? "rgba(54, 162, 235, 0.6)" : "rgba(255, 99, 132, 0.6)" }}'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    '{{ $profit >= 0 ? "rgba(54, 162, 235, 1)" : "rgba(255, 99, 132, 1)" }}'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Chart
    @php
        $categoryData = $allTransactions->groupBy('category')->map(function($items) {
            return $items->sum('amount');
        });
    @endphp
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($categoryData as $category => $amount)
                    '{{ $category ? __("accounting.".$category) : __("accounting.other") }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($categoryData as $amount)
                        {{ $amount }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(199, 199, 199, 0.6)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Add Transaction Form
    document.getElementById('addTransactionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("common.loading") }}';
        
        fetch('{{ route("admin.accounting.transactions.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addTransactionModal'));
                modal.hide();
                
                // Show success toast
                showToast(data.message || '{{ __("accounting.transaction_created_successfully") }}', 'success');
                
                // Reload page after short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast(data.message || '{{ __("common.error") }}', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __("common.save") }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = '{{ __("common.error") }}';
            
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join('\n');
            } else if (error.message) {
                errorMessage = error.message;
            }
            
            showToast(errorMessage, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '{{ __("common.save") }}';
        });
    });

    // Delete Transaction
    let transactionToDelete = null;
    
    function deleteTransaction(id) {
        transactionToDelete = id;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteModal.show();
    }
    
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!transactionToDelete) return;
        
        const confirmBtn = this;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("common.loading") }}';
        
        fetch(`{{ route("admin.accounting.transactions.destroy", ":id") }}`.replace(':id', transactionToDelete), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                deleteModal.hide();
                
                // Show success toast
                showToast(data.message || '{{ __("accounting.transaction_deleted_successfully") }}', 'success');
                
                // Reload page after short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast(data.message || '{{ __("common.error") }}', 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = '{{ __("common.error") }}';
            
            if (error.message) {
                errorMessage = error.message;
            }
            
            showToast(errorMessage, 'error');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
</script>
@endpush
