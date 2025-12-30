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
        @can('lawyers.view')
        <li>
            <a href="{{ route('admin.lawyers.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/lawyers*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-balance-scale"></span>
                <span class="menu-text">{{ trans('menu.lawyers.title') }}</span>
            </a>
        </li>
        @endcan

        {{-- Reviews of Lawyers --}}
        @can('reviews.view')
        <li>
            <a href="{{ route('admin.reviews.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/reviews*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-star"></span>
                <span class="menu-text">{{ trans('menu.reviews.title') }}</span>
            </a>
        </li>
        @endcan

        {{-- Customers --}}
        @can('customers.view')
        <li>
            <a href="{{ route('admin.customers.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/customers*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text">{{ __('customer.customers_management') }}</span>
            </a>
        </li>
        @endcan

        {{-- Reservations --}}
        @can('reservations.view')
        <li>
            <a href="{{ route('admin.reservations.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/reservations*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-calendar-alt"></span>
                <span class="menu-text">{{ trans('menu.reservations.title') }}</span>
            </a>
        </li>
        @endcan

        {{-- Subscriptions --}}
        @can('subscriptions.view')
        <li>
            <a href="{{ route('admin.subscriptions.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/subscriptions*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-ticket"></span>
                <span class="menu-text">{{ __('subscription.subscriptions_management') }}</span>
            </a>
        </li>
        @endcan

        {{-- Hosting --}}
        @can('hosting.view')
        <li class="has-child {{ request()->routeIs('admin.hosting.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.hosting.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-server"></span>
                <span class="menu-text">{{ trans('menu.hosting.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a href="{{ route('admin.hosting.index') }}"
                        class="{{ request()->routeIs('admin.hosting.index') ? 'active' : '' }}">
                        {{ trans('menu.hosting.hosting') }}
                    </a>
                </li>
                @can('hosting.settings')
                <li>
                    <a href="{{ route('admin.hosting.settings') }}"
                        class="{{ request()->routeIs('admin.hosting.settings') ? 'active' : '' }}">
                        {{ trans('menu.hosting.hosting_settings') }}
                    </a>
                </li>
                @endcan
                @can('hosting-reservations.view')
                <li>
                    <a href="{{ route('admin.hosting.reservations.index') }}"
                        class="{{ request()->routeIs('admin.hosting.reservations.*') ? 'active' : '' }}">
                        {{ trans('menu.hosting.hosting_reservations') }}
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- Notifications --}}
        @can('support-messages.view')
        <li>
            <a href="{{ route('admin.support-messages.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/support-messages*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-comment-message"></span>
                <span class="menu-text">{{ trans('menu.support messages.title') }}</span>
            </a>
        </li>
        @endcan

        {{-- News --}}
        @can('news.view')
        <li>
            <a href="{{ route('admin.news.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/news*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-envelope-alt"></span>
                <span class="menu-text">{{ trans('menu.newsletter.title') }}</span>
            </a>
        </li>
        @endcan

        {{-- Judicial Agenda --}}
        @can('agendas.view')
        <li>
            <a href="{{ route('admin.agendas.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/agendas*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-clipboard-notes"></span>
                <span class="menu-text">{{ trans('agenda.judicial_agenda_management') }}</span>
            </a>
        </li>
        @endcan

        {{-- Preparer Judicial Agenda --}}
        @can('preparer-agendas.view')
        <li>
            <a href="{{ route('admin.preparer-agendas.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/preparer-agendas*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-file-check-alt"></span>
                <span class="menu-text">{{ trans('agenda.preparer_agenda_management') }}</span>
            </a>
        </li>
        @endcan

        {{-- Clients Agenda --}}
        @can('client-agendas.view')
        <li>
            <a href="{{ route('admin.client-agendas.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/client-agendas*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text">{{ trans('client_agenda.client_agenda_management') }}</span>
            </a>
        </li>
        @endcan

        {{-- Sections of Laws --}}
        @can('sections-of-laws.view')
        <li>
            <a href="{{ route('admin.sections-of-laws.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/sections-of-laws*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-books"></span>
                <span class="menu-text">{{ trans('menu.sections_of_laws') }}</span>
            </a>
        </li>
        @endcan

        {{-- Instructions --}}
        @can('instructions.view')
        <li>
            <a href="{{ route('admin.instructions.index') }}"
                class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/instructions*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-file-info-alt"></span>
                <span class="menu-text">{{ trans('menu.instructions') }}</span>
            </a>
        </li>
        @endcan

        {{-- Branches of Laws --}}
        @can('branches-of-laws.view')
        <li class="has-child {{ request()->routeIs('admin.branches-of-laws.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.branches-of-laws.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-layers"></span>
                <span class="menu-text">{{ trans('menu.branches_of_laws') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                <li><a href="{{ route('admin.branches-of-laws.index') }}"
                        class="{{ request()->routeIs('admin.branches-of-laws.index') ? 'active' : '' }}">{{ trans('menu.all_branches') }}</a>
                </li>
                @can('branches-of-laws.create')
                <li><a href="{{ route('admin.branches-of-laws.create') }}"
                        class="{{ request()->routeIs('admin.branches-of-laws.create') ? 'active' : '' }}">{{ trans('menu.add_branch') }}</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- Contracts --}}
        @canany(['drafting-contracts.view', 'drafting-lawsuits.view', 'measures.view'])
        <li class="has-child {{ request()->routeIs('admin.drafting-contracts.*', 'admin.drafting-lawsuits.*', 'admin.measures.*') ? 'open' : '' }}">
            <a href="#"
                class="{{ request()->routeIs('admin.drafting-contracts.*', 'admin.drafting-lawsuits.*', 'admin.measures.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-file-contract-dollar"></span>
                <span class="menu-text">{{ trans('menu.contracts.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                @can('drafting-contracts.view')
                <li><a href="{{ route('admin.drafting-contracts.index') }}"
                        class="{{ request()->routeIs('admin.drafting-contracts.*') ? 'active' : '' }}">{{ trans('menu.contracts.drafting_contracts') }}</a>
                </li>
                @endcan
                @can('drafting-lawsuits.view')
                <li><a href="{{ route('admin.drafting-lawsuits.index') }}"
                        class="{{ request()->routeIs('admin.drafting-lawsuits.*') ? 'active' : '' }}">{{ trans('menu.contracts.drafting_lawsuits') }}</a>
                </li>
                @endcan
                @can('measures.view')
                <li><a href="{{ route('admin.measures.index') }}"
                        class="{{ request()->routeIs('admin.measures.*') ? 'active' : '' }}">{{ trans('menu.contracts.measures') }}</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        {{-- Store --}}
        @canany(['store-categories.view', 'store-products.view', 'store-orders.view'])
        <li class="has-child {{ request()->routeIs('admin.store.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.store.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-shopping-bag"></span>
                <span class="menu-text">{{ trans('menu.store.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                @can('store-categories.view')
                <li><a href="{{ route('admin.store.categories.index') }}"
                        class="{{ request()->routeIs('admin.store.categories.*') ? 'active' : '' }}">{{ trans('menu.store.categories') }}</a>
                </li>
                @endcan
                @can('store-products.view')
                <li><a href="{{ route('admin.store.products.index') }}"
                        class="{{ request()->routeIs('admin.store.products.*') ? 'active' : '' }}">{{ trans('menu.store.products') }}</a>
                </li>
                @endcan
                @can('store-orders.view')
                <li><a href="{{ route('admin.store.orders.index') }}"
                        class="{{ request()->routeIs('admin.store.orders.*') ? 'active' : '' }}">{{ trans('menu.store.orders') }}</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        {{-- Destinations --}}
        @canany(['countries.view', 'cities.view', 'regions.view', 'subregions.view'])
        <li class="has-child {{ request()->routeIs('admin.area-settings.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.area-settings.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-map-marker"></span>
                <span class="menu-text">{{ trans('menu.destinations.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                @can('countries.view')
                <li>
                    <a href="{{ route('admin.area-settings.countries.index') }}"
                        class="{{ request()->routeIs('admin.area-settings.countries.*') ? 'active' : '' }}">{{ trans('menu.destinations.countries') }}</a>
                </li>
                @endcan
                @can('cities.view')
                <li>
                    <a href="{{ route('admin.area-settings.cities.index') }}"
                        class="{{ request()->routeIs('admin.area-settings.cities.*') ? 'active' : '' }}">{{ trans('menu.destinations.cities') }}</a>
                </li>
                @endcan
                @can('regions.view')
                <li>
                    <a href="{{ route('admin.area-settings.regions.index') }}"
                        class="{{ request()->routeIs('admin.area-settings.regions.*') ? 'active' : '' }}">{{ trans('menu.destinations.regions') }}</a>
                </li>
                @endcan
                @can('subregions.view')
                <li>
                    <a href="{{ route('admin.area-settings.subregions.index') }}"
                        class="{{ request()->routeIs('admin.area-settings.subregions.*') ? 'active' : '' }}">{{ trans('menu.destinations.subregions') }}</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        {{-- Admin Management --}}
        @canany(['admins.view', 'roles.view'])
        <li class="has-child {{ request()->routeIs('admin.admin-management.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('admin.admin-management.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-shield-check"></span>
                <span class="menu-text">{{ trans('menu.admin_management.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                @can('admins.view')
                <li><a href="{{ route('admin.admin-management.admins.index') }}"
                        class="{{ request()->routeIs('admin.admin-management.admins.*') ? 'active' : '' }}">{{ trans('menu.admin_management.admins') }}</a>
                </li>
                @endcan
                @can('roles.view')
                <li><a href="{{ route('admin.admin-management.roles.index') }}"
                        class="{{ request()->routeIs('admin.admin-management.roles.*') ? 'active' : '' }}">{{ trans('menu.admin_management.roles') }}</a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        {{-- Layouts --}}
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-window-section"></span>
                <span class="menu-text">{{ trans('menu.layouts.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li class="l_sidebar"><a href="#"
                        data-layout="light">{{ trans('menu.layouts.light mode') }}</a>
                </li>
                <li class="l_sidebar"><a href="#" data-layout="dark">{{ trans('menu.layouts.dark mode') }}</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
