<div class="sidebar__menu-group">
    <ul class="sidebar_nav">
        {{-- Dashboard --}}
        @can('dashboard.view')
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon uil uil-create-dashboard"></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
        @endcan

        {{-- Lawyers --}}
        <li>
            <a href="{{ route('admin.lawyers.index') }}">
                <span class="nav-icon uil uil-balance-scale"></span>
                <span class="menu-text">Lawyers</span>
            </a>
        </li>

        {{-- Follow-up with lawyers --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-clipboard-notes"></span>
                <span class="menu-text">Follow-up with lawyers</span>
            </a>
        </li>

        {{-- Monitoring comments and ratings from consultants --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-star"></span>
                <span class="menu-text">Monitoring comments and ratings</span>
            </a>
        </li>

        {{-- Customers --}}
        <li>
            <a href="{{ route('admin.customers.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/customers*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text">{{ __('customer.customers_management') }}</span>
            </a>
        </li>

        {{-- Reservations --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-calendar-alt"></span>
                <span class="menu-text">Reservations</span>
            </a>
        </li>

        {{-- Subscriptions --}}
        <li>
            <a href="{{ route('admin.subscriptions.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/subscriptions*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-ticket"></span>
                <span class="menu-text">{{ __('subscription.subscriptions_management') }}</span>
            </a>
        </li>

        {{-- Hosting --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-server"></span>
                <span class="menu-text">Hosting</span>
            </a>
        </li>

        {{-- Support messages --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-comment-message"></span>
                <span class="menu-text">Support messages</span>
            </a>
        </li>

        {{-- Technical support for clients --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-headphones"></span>
                <span class="menu-text">Technical support for clients</span>
            </a>
        </li>

        {{-- Technical support for consultants --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-life-ring"></span>
                <span class="menu-text">Technical support for consultants</span>
            </a>
        </li>

        {{-- Newsletter --}}
        <li>
            <a href="{{ route('admin.news.index') }}">
                <span class="nav-icon uil uil-envelope-alt"></span>
                <span class="menu-text">Newsletter</span>
            </a>
        </li>

        {{-- Sections of Laws --}}
        <li>
            <a href="{{ route('admin.sections-of-laws.index') }}">
                <span class="nav-icon uil uil-books"></span>
                <span class="menu-text">Sections of Laws</span>
            </a>
        </li>

        {{-- Instructions --}}
        <li>
            <a href="{{ route('admin.instructions.index') }}">
                <span class="nav-icon uil uil-file-info-alt"></span>
                <span class="menu-text">Instructions</span>
            </a>
        </li>

        {{-- Branches of Laws --}}
        <li>
            <a href="{{ route('admin.branches-of-laws.index') }}">
                <span class="nav-icon uil uil-sitemap"></span>
                <span class="menu-text">Branches of Laws</span>
            </a>
        </li>

        {{-- Email messages (with children) --}}
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-envelope"></span>
                <span class="menu-text">Email messages</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Reservations</a>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}">Judicial agenda</a>
                </li>
            </ul>
        </li>

        {{-- Destinations --}}
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-map-marker"></span>
                <span class="menu-text">Destinations</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a href="{{ route('admin.area-settings.countries.index') }}">Countries</a>
                </li>
                <li>
                    <a href="{{ route('admin.area-settings.cities.index') }}">Cities</a>
                </li>
                <li>
                    <a href="{{ route('admin.area-settings.regions.index') }}">Regions</a>
                </li>
            </ul>
        </li>

        {{-- Layouts --}}
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-window-section"></span>
                <span class="menu-text">{{ trans('menu.layouts.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li class="l_sidebar"><a href="#" data-layout="light">{{ trans('menu.layouts.light mode') }}</a></li>
                <li class="l_sidebar"><a href="#" data-layout="dark">{{ trans('menu.layouts.dark mode') }}</a></li>
            </ul>
        </li>
    </ul>
</div>
