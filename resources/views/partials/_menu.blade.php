<div class="sidebar__menu-group">
    <ul class="sidebar_nav">
        {{-- Dashboard --}}
        @can('dashboard.view')
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon uil uil-create-dashboard"></span>
                    <span class="menu-text">{{ trans('menu.dashboard.title') }}</span>
                </a>
            </li>
        @endcan

        {{-- Lawyers --}}
        <li>
            <a href="{{ route('admin.lawyers.index') }}">
                <span class="nav-icon uil uil-balance-scale"></span>
                <span class="menu-text">{{ trans('menu.lawyers.title') }}</span>
            </a>
        </li>

        {{-- Follow-up with lawyers --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-clipboard-notes"></span>
                <span class="menu-text">{{ trans('menu.follow-up with lawyers.title') }}</span>
            </a>
        </li>

        {{-- Reviews of Lawyers --}}
        <li>
            <a href="{{ route('admin.reviews.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/reviews*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-star"></span>
                <span class="menu-text">{{ trans('menu.reviews.title') }}</span>
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
            <a href="{{ route('admin.reservations.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/reservations*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-calendar-alt"></span>
                <span class="menu-text">{{ trans('menu.reservations.title') }}</span>
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
        <li class="has-child {{ request()->routeIs('admin.hosting.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.hosting.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-server"></span>
                <span class="menu-text">{{ trans('menu.hosting.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a href="{{ route('admin.hosting.index') }}" class="{{ request()->routeIs('admin.hosting.index') ? 'active' : '' }}">
                        {{ trans('menu.hosting.hosting') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hosting.settings') }}" class="{{ request()->routeIs('admin.hosting.settings') ? 'active' : '' }}">
                        {{ trans('menu.hosting.hosting_settings') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hosting.reservations.index') }}" class="{{ request()->routeIs('admin.hosting.reservations.*') ? 'active' : '' }}">
                        {{ trans('menu.hosting.hosting_reservations') }}
                    </a>
                </li>
            </ul>
        </li>

        {{-- Support messages --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-comment-message"></span>
                <span class="menu-text">{{ trans('menu.support messages.title') }}</span>
            </a>
        </li>

        {{-- Technical support for clients --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-headphones"></span>
                <span class="menu-text">{{ trans('menu.technical support clients.title') }}</span>
            </a>
        </li>

        {{-- Technical support for consultants --}}
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-life-ring"></span>
                <span class="menu-text">{{ trans('menu.technical support consultants.title') }}</span>
            </a>
        </li>

        {{-- Newsletter --}}
        <li>
            <a href="{{ route('admin.news.index') }}">
                <span class="nav-icon uil uil-envelope-alt"></span>
                <span class="menu-text">{{ trans('menu.newsletter.title') }}</span>
            </a>
        </li>

        {{-- Sections of Laws --}}
        <li>
            <a href="{{ route('admin.sections-of-laws.index') }}">
                <span class="nav-icon uil uil-books"></span>
                <span class="menu-text">{{ trans('menu.sections_of_laws') }}</span>
            </a>
        </li>

        {{-- Instructions --}}
        <li>
            <a href="{{ route('admin.instructions.index') }}">
                <span class="nav-icon uil uil-file-info-alt"></span>
                <span class="menu-text">{{ trans('menu.instructions') }}</span>
            </a>
        </li>

        {{-- Branches of Laws --}}
        <li class="has-child {{ request()->routeIs('admin.branches-of-laws.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.branches-of-laws.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-layers"></span>
                <span class="menu-text">{{ trans('menu.branches_of_laws') }}</span>
            </a>
            <ul>
                <li><a href="{{ route('admin.branches-of-laws.index') }}" class="{{ request()->routeIs('admin.branches-of-laws.index') ? 'active' : '' }}">{{ trans('menu.all_branches') }}</a></li>
                <li><a href="{{ route('admin.branches-of-laws.create') }}" class="{{ request()->routeIs('admin.branches-of-laws.create') ? 'active' : '' }}">{{ trans('menu.add_branch') }}</a></li>
            </ul>
        </li>

        {{-- Contracts --}}
        <li class="has-child {{ request()->routeIs('admin.drafting-contracts.*', 'admin.drafting-lawsuits.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.drafting-contracts.*', 'admin.drafting-lawsuits.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-file-contract-dollar"></span>
                <span class="menu-text">{{ trans('menu.contracts.title') }}</span>
            </a>
            <ul>
                <li><a href="{{ route('admin.drafting-contracts.index') }}" class="{{ request()->routeIs('admin.drafting-contracts.*') ? 'active' : '' }}">{{ trans('menu.contracts.drafting_contracts') }}</a></li>
                <li><a href="{{ route('admin.drafting-lawsuits.index') }}" class="{{ request()->routeIs('admin.drafting-lawsuits.*') ? 'active' : '' }}">{{ trans('menu.contracts.drafting_lawsuits') }}</a></li>
            </ul>
        </li>

        {{-- Destinations --}}
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-map-marker"></span>
                <span class="menu-text">{{ trans('menu.destinations.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a
                        href="{{ route('admin.area-settings.countries.index') }}">{{ trans('menu.destinations.countries') }}</a>
                </li>
                <li>
                    <a
                        href="{{ route('admin.area-settings.cities.index') }}">{{ trans('menu.destinations.cities') }}</a>
                </li>
                <li>
                    <a
                        href="{{ route('admin.area-settings.regions.index') }}">{{ trans('menu.destinations.regions') }}</a>
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
                <li class="l_sidebar"><a href="#" data-layout="light">{{ trans('menu.layouts.light mode') }}</a>
                </li>
                <li class="l_sidebar"><a href="#" data-layout="dark">{{ trans('menu.layouts.dark mode') }}</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
