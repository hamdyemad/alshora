<div class="sidebar__menu-group">
    <ul class="sidebar_nav">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/dashboard') ? 'active':'' }}">
                <span class="nav-icon uil uil-create-dashboard"></span>
                <span class="menu-text">{{ trans('menu.dashboard.title') }}</span>
            </a>
        </li>
        <li class="has-child {{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/vendors*') ? 'open':'' }}">
            <a href="#" class="{{ Request::is(LaravelLocalization::getCurrentLocale() . '/admin/vendors*') ? 'active':'' }}">
                <span class="nav-icon uil uil-users-alt"></span>
                <span class="menu-text">{{ trans('menu.vendors.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="d-flex align-items-center justify-content-between w-100">
                            <span>{{ trans('menu.vendors.all') }}</span>
                            <span class="badge-circle badge-success ms-1">50</span>
                        </span>
                    </a>
                </li>
                <li><a href="{{ route('admin.vendors.create') }}">{{ trans('menu.vendors.create') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-clipboard-notes"></span>
                <span class="menu-text">{{ trans('menu.become a vendor requests.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.become a vendor requests.new') }}
                        <span class="badge-circle badge-primary ms-1">50</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.become a vendor requests.accepted') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.become a vendor requests.rejected') }}
                        <span class="badge-circle badge-danger ms-1">50</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-user"></span>
                <span class="menu-text">{{ trans('menu.users.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.users.create') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.users.all') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-calculator"></span>
                <span class="menu-text">{{ trans('menu.accounting module.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.accounting module.overview') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.accounting module.balance') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.accounting module.expenses keys') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.accounting module.expenses') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-money-withdrawal"></span>
                <span class="menu-text">{{ trans('menu.withdraw module.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.withdraw module.send money to vendors') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.withdraw module.all transactions') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.withdraw module.vendors accepted requests') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.withdraw module.vendors rejected requests') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.withdraw module.vendors new requests') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-box"></span>
                <span class="menu-text">{{ trans('menu.products.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.products.create') }}</a></li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.products.all') }}
                        <span class="badge-circle badge-primary ms-1">250</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.products.in stock') }}
                        <span class="badge-circle badge-success ms-1">200</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.products.out of stock') }}
                        <span class="badge-circle badge-danger ms-1">50</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-map-marker"></span>
                <span class="menu-text">{{ trans('menu.area settings.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.area settings.country') }}
                        <span class="badge-circle badge-success ms-1">15</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.area settings.city') }}
                        <span class="badge-circle badge-info ms-1">120</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.area settings.region') }}
                        <span class="badge-circle badge-warning ms-1">45</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.area settings.subregion') }}
                        <span class="badge-circle badge-secondary ms-1">80</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-wrench"></span>
                <span class="menu-text">{{ trans('menu.products setup.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.products setup.all') }}
                        <span class="badge-circle badge-primary ms-1">35</span>
                    </a>
                </li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.products setup.create') }}</a></li>
            </ul>
        </li>
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-receipt"></span>
                <span class="menu-text">{{ trans('menu.taxes.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.taxes.all') }}
                        <span class="badge-circle badge-info ms-1">12</span>
                    </a>
                </li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.taxes.create') }}</a></li>
            </ul>
        </li>
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-tag-alt"></span>
                <span class="menu-text">{{ trans('menu.offers.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.offers.all') }}
                        <span class="badge-circle badge-warning ms-1">8</span>
                    </a>
                </li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.offers.create') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-user-check"></span>
                <span class="menu-text">{{ trans('menu.admin managment.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.admin managment.roles managment') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.admin managment.admin managment') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-sitemap"></span>
                <span class="menu-text">{{ trans('menu.category managment.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.category managment.title') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.category managment.sub category managment') }}</a></li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-star"></span>
                <span class="menu-text">{{ trans('menu.product reviews.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.product reviews.all') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.product reviews.accepted') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.product reviews.rejected') }}</a></li>
            </ul>
        </li>



        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-shopping-cart"></span>
                <span class="menu-text">{{ trans('menu.orders.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul  class="px-0">
                <li class="l_sidebar">
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.orders.new') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li class="l_sidebar">
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.orders.inprogress') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li class="l_sidebar">
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.orders.delivered') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li class="l_sidebar">
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.orders.canceled') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li class="l_sidebar">
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.orders.refunded') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="d-flex align-items-center justify-content-between w-100">
                    <span class="d-flex align-items-center">
                        <span class="nav-icon uil uil-megaphone"></span>
                        <span class="menu-text">{{ trans('menu.promos.title') }}</span>
                    </span>
                    <span class="badge-circle badge-success ms-1">50</span>
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="d-flex align-items-center justify-content-between w-100">
                    <span class="d-flex align-items-center">
                        <span class="nav-icon uil uil-list-ul"></span>
                        <span class="menu-text">{{ trans('menu.order stages.title') }}</span>
                    </span>
                    <span class="badge-circle badge-success ms-1">50</span>
                </span>
            </a>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-award"></span>
                <span class="menu-text">{{ trans('menu.point managment.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.point managment.title') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.point managment.users points') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-trophy"></span>
                <span class="menu-text">{{ trans('menu.achievements.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.achievements.positions') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        {{ trans('menu.achievements.advestments') }}
                        <span class="badge-circle badge-success ms-1">50</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-bell"></span>
                <span class="menu-text">{{ trans('menu.notifications.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.notifications.send notification') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}">{{ trans('menu.notifications.all notification') }}</a></li>
            </ul>
        </li>

        <li>
            <a href="{{ route('admin.dashboard') }}">
                <span class="nav-icon uil uil-file-alt"></span>
                <span class="menu-text">{{ trans('menu.system log.title') }}</span>
            </a>
        </li>

        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-setting"></span>
                <span class="menu-text">{{ trans('menu.system settings.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul class="px-0">
                <li><a href="{{ route('admin.dashboard') }}"><span class="nav-icon uil uil-file-contract-dollar"></span> {{ trans('menu.system settings.terms and conditions') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}"><span class="nav-icon uil uil-shield-check"></span> {{ trans('menu.system settings.privacy policy') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}"><span class="nav-icon uil uil-info-circle"></span> {{ trans('menu.system settings.about us') }}</a></li>
                <li><a href="{{ route('admin.dashboard') }}"><span class="nav-icon uil uil-phone"></span> {{ trans('menu.system settings.contact us') }}</a></li>
                <li>
                    <a class="d-flex align-items-center justify-content-between" href="{{ route('admin.dashboard') }}">
                        <span class="d-flex align-items-center">
                            <span class="nav-icon uil uil-envelope"></span>
                            <span>{{ trans('menu.system settings.messages') }}</span>
                        </span>
                        <span class="badge-circle badge-primary ms-1">25</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="has-child">
            <a href="#" class="">
                <span class="nav-icon uil uil-window-section"></span>
                <span class="menu-text">{{ trans('menu.layouts.title') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul  class="px-0">
                <li class="l_sidebar"><a href="#" data-layout="light">{{ trans('menu.layouts.light mode') }}</a></li>
                <li class="l_sidebar"><a href="#" data-layout="dark">{{ trans('menu.layouts.dark mode') }}</a></li>
            </ul>
        </li>



    </ul>
</div>
