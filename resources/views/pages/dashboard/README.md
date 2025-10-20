# Dashboard Components

This folder contains modular components for the dashboard page to improve code maintainability and readability.

## Component Structure

### Main Components:
1. **stats-overview.blade.php** - Expenses, Income, Net Profit, Revenue cards
2. **sales-reports.blade.php** - Sales Reports chart section
3. **sales-chart.blade.php** - Sales chart with Today/Week/Month tabs
4. **earnings-chart.blade.php** - Earnings chart with Today/Week/Month tabs
5. **total-sales-chart.blade.php** - Total Sales chart with Today/Week/Month tabs
6. **stats-cards.blade.php** - 8 statistics cards (In Stock, Out of Stock, Total Orders, etc.)
7. **orders-overview.blade.php** - Orders Overview doughnut chart
8. **top-selling-products.blade.php** - Top 5 selling products table
9. **latest-orders.blade.php** - Latest 5 orders table
10. **best-customers.blade.php** - Best 5 customers table
11. **charts-scripts.blade.php** - All Chart.js initialization scripts

## Usage

### In your dashboard blade file:
```blade
@extends('layout.app')

@section('content')
    <div class="crm mb-25">
        <div class="container-fluid">
            <div class="row">
                {{-- Include components --}}
                @include('components.dashboard.stats-overview')
                @include('components.dashboard.sales-reports')
                @include('components.dashboard.sales-chart')
                {{-- ... other components --}}
            </div>
        </div>
    </div>
    
    {{-- Chart Scripts --}}
    @include('components.dashboard.charts-scripts')
@endsection
```

## Benefits

1. **Maintainability** - Each component is in its own file, easy to find and update
2. **Reusability** - Components can be reused across different pages
3. **Readability** - Main dashboard file is now ~70 lines instead of 1300+
4. **Organization** - Logical separation of concerns
5. **Collaboration** - Multiple developers can work on different components

## File Locations

- Components: `resources/views/components/dashboard/`
- Main Dashboard: `resources/views/pages/dashboard-optimized.blade.php`
- Original Dashboard: `resources/views/pages/dashboard.blade.php` (backup)
