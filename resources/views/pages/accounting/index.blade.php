@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <x-breadcrumb :items="[
                    ['title' => trans('dashboard.title'), 'url' => route('admin.dashboard'), 'icon' => 'uil uil-estate'],
                    ['title' => trans('menu.accounting.title')]
                ]" />
            </div>
        </div>

        {{-- Filters --}}
        <div class="row mb-25">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.accounting.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">{{ __('accounting.lawyer') }}</label>
                                    <select name="lawyer_id" class="form-control">
                                        <option value="">{{ __('accounting.all_lawyers') }}</option>
                                        @foreach($allLawyers as $lawyer)
                                            <option value="{{ $lawyer->id }}" {{ $lawyerId == $lawyer->id ? 'selected' : '' }}>
                                                {{ $lawyer->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
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
                                    <a href="{{ route('admin.accounting.index') }}" class="btn btn-secondary w-100">
                                        <i class="uil uil-redo"></i> {{ __('common.reset') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Overall Stats --}}
        <div class="row">
            <div class="col-md-4 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-2">{{ __('accounting.total_income') }}</p>
                                <h3 class="mb-0 text-success">{{ number_format($totalIncome, 2) }} {{ __('common.egp') }}</h3>
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
                                <p class="text-muted mb-2">{{ __('accounting.total_expenses') }}</p>
                                <h3 class="mb-0 text-danger">{{ number_format($totalExpenses, 2) }} {{ __('common.egp') }}</h3>
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
                                <p class="text-muted mb-2">{{ __('accounting.total_profit') }}</p>
                                <h3 class="mb-0 {{ $totalProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                                    {{ number_format($totalProfit, 2) }} {{ __('common.egp') }}
                                </h3>
                            </div>
                            <div class="icon-box {{ $totalProfit >= 0 ? 'bg-primary-transparent' : 'bg-danger-transparent' }}">
                                <i class="uil uil-dollar-alt {{ $totalProfit >= 0 ? 'text-primary' : 'text-danger' }}" style="font-size: 32px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="row">
            <div class="col-lg-4 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h6 class="mb-0 fw-500">{{ __('accounting.financial_overview') }}</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 250px; position: relative;">
                            <canvas id="financialOverviewChart"></canvas>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2" style="width: 12px; height: 12px;"></span>
                                    <span>{{ __('accounting.income') }}</span>
                                </div>
                                <span class="fw-bold">{{ number_format($totalIncome, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-danger me-2" style="width: 12px; height: 12px;"></span>
                                    <span>{{ __('accounting.expenses') }}</span>
                                </div>
                                <span class="fw-bold">{{ number_format($totalExpenses, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2" style="width: 12px; height: 12px;"></span>
                                    <span>{{ __('accounting.profit') }}</span>
                                </div>
                                <span class="fw-bold">{{ number_format($totalProfit, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-25">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h6 class="mb-0 fw-500">{{ __('accounting.income_expenses_trend') }}</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px; position: relative;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lawyers Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-20">
                        <h5 class="mb-0 fw-500">{{ __('accounting.lawyer_accounting') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('accounting.lawyer') }}</th>
                                        <th>{{ __('common.email') }}</th>
                                        <th class="text-end">{{ __('accounting.income') }}</th>
                                        <th class="text-end">{{ __('accounting.expenses') }}</th>
                                        <th class="text-end">{{ __('accounting.profit') }}</th>
                                        <th class="text-center">{{ __('accounting.appointments_count') }}</th>
                                        <th class="text-center">{{ __('common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lawyers as $index => $lawyer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $lawyer['name'] }}</td>
                                            <td>{{ $lawyer['email'] }}</td>
                                            <td class="text-end text-success fw-bold">{{ number_format($lawyer['income'], 2) }}</td>
                                            <td class="text-end text-danger fw-bold">{{ number_format($lawyer['expenses'], 2) }}</td>
                                            <td class="text-end fw-bold {{ $lawyer['profit'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                                {{ number_format($lawyer['profit'], 2) }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $lawyer['appointments_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.accounting.show', $lawyer['id']) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="uil uil-eye"></i> {{ __('accounting.view_transactions') }}
                                                </a>
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Financial Overview Doughnut Chart
    const financialCtx = document.getElementById('financialOverviewChart').getContext('2d');
    new Chart(financialCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("accounting.income") }}', '{{ __("accounting.expenses") }}', '{{ __("accounting.profit") }}'],
            datasets: [{
                data: [{{ $totalIncome }}, {{ $totalExpenses }}, {{ abs($totalProfit) }}],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    '{{ $totalProfit >= 0 ? "rgba(54, 162, 235, 0.8)" : "rgba(255, 159, 64, 0.8)" }}'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%'
        }
    });

    // Income & Expenses Trend Line Chart
    @php
        use Carbon\Carbon;
        
        // Use provided dates or all time
        $calcDateFrom = $dateFrom ?? '1970-01-01';
        $calcDateTo = $dateTo ?? now()->addYears(10)->format('Y-m-d');
        
        // Get transactions grouped by date (limit to avoid infinite loop)
        $transactionsQuery = \App\Models\LawyerTransaction::whereBetween('transaction_date', [$calcDateFrom, $calcDateTo]);
        
        // Filter by lawyer if selected
        if ($lawyerId) {
            $transactionsQuery->where('lawyer_id', $lawyerId);
        }
        
        $transactions = $transactionsQuery
            ->selectRaw('DATE(transaction_date) as date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();
        
        // Prepare data for chart
        $chartDates = [];
        $chartIncome = [];
        $chartExpenses = [];
        
        // Group by date
        $groupedByDate = $transactions->groupBy('date');
        
        foreach ($groupedByDate as $date => $items) {
            $chartDates[] = Carbon::parse($date)->format('M d');
            $chartIncome[] = $items->where('type', 'income')->sum('total');
            $chartExpenses[] = $items->where('type', 'expense')->sum('total');
        }
        
        // If no data, show empty chart
        if (empty($chartDates)) {
            $chartDates = ['No Data'];
            $chartIncome = [0];
            $chartExpenses = [0];
        }
    @endphp

    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartDates) !!},
            datasets: [
                {
                    label: '{{ __("accounting.income") }}',
                    data: {!! json_encode($chartIncome) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: '{{ __("accounting.expenses") }}',
                    data: {!! json_encode($chartExpenses) !!},
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
</script>
@endpush
