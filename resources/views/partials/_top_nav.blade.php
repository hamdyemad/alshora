<nav class="navbar navbar-light">
    <div class="navbar-left">
        <div class="logo-area">
            <a class="navbar-brand" href="#">
                <img class="dark" src="{{ asset('assets/img/logo-dark.svg') }}" alt="svg">
                <img class="light" src="{{ asset('assets/img/logo-white.svg') }}" alt="img">
            </a>
            <a href="#" class="sidebar-toggle">
                <img class="svg" src="{{ asset('assets/img/svg/align-center-alt.svg') }}" alt="img"></a>
        </div>

        <div class="top-menu">
            <div class="hexadash-top-menu position-relative">
                <ul>
                    <li class="has-subMenu">
                        <a href="#" class="">{{ trans('menu.layouts.title') }}</a>
                        <ul class="subMenu">
                            <li class="l_sidebar">
                            <a href="#" data-layout="light">{{ trans('menu.layouts.light mode') }}</a>
                            </li>
                            <li class="l_sidebar">
                            <a href="#" data-layout="dark">{{ trans('menu.layouts.dark mode') }}</a>
                            </li>
                            <li class="l_navbar">
                            <a href="#" data-layout="top">{{ trans('menu.layouts.top menu') }}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-subMenu">
                        <a href="#" class="{{ Request::is(app()->getLocale().'/table/*') || Request::is(app()->getLocale().'/pages/dynamic-table') || Request::is(app()->getLocale().'/applications/job/*') || Request::is(app()->getLocale().'/applications/support/*') || Request::is(app()->getLocale().'/applications/social/profile-settings') || Request::is(app()->getLocale().'/applications/bookmark') || Request::is(app()->getLocale().'/applications/task')  || Request::is(app()->getLocale().'/applications/filemanager') || Request::is(app()->getLocale().'/applications/import_export/*') || Request::is(app()->getLocale().'/applications/kanban') || Request::is(app()->getLocale().'/applications/todo') || Request::is(app()->getLocale().'/applications/note') || Request::is(app()->getLocale().'/applications/contact/*') || Request::is(app()->getLocale().'/applications/user/*') || Request::is(app()->getLocale().'/applications/calendar') || Request::is(app()->getLocale().'/applications/project/*') || Request::is(app()->getLocale().'/applications/ecommerce/*') || Request::is(app()->getLocale().'/applications/email/*') || Request::is(app()->getLocale().'/applications/chat') ? 'active':'' }}">Apps</a>
                        <ul class="megaMenu-wrapper megaMenu-dropdown">
                            <li>
                                <ul>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/email/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-envelope"></span>
                                            <span class="menu-text">{{ trans('menu.email-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/email/inbox') ? 'active':'' }}" href="#">{{ trans('menu.email-inbox') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/email/read') ? 'active':'' }}" href="#">{{ trans('menu.email-read') }}</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                    <a href="#">
                                        <span class="nav-icon uil uil-chat"></span>
                                        <span class="menu-text">{{ trans('menu.chat-menu-title') }}</span>
                                        <span class="badge badge-success menuItem rounded-circle">3</span>
                                    </a>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/ecommerce/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-bag"></span>
                                            <span class="menu-text text-initial">{{ trans('menu.ecommerce-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/ecommerce/products') ? 'active':'' }}">{{ trans('menu.ecommerce-products') }}</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-subMenu-left">
                                    <a href="#" class="{{ Request::is(app()->getLocale().'/applications/project/*') ? 'active':'' }}">
                                        <span class="nav-icon uil uil-folder"></span>
                                        <span class="menu-text">{{ trans('menu.project-menu-title') }}</span>
                                    </a>
                                    <ul class="subMenu">
                                        <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/project/list') ? 'active':'' }}">{{ trans('menu.project-title') }}</a></li>
                                        <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/project/project-detail') ? 'active':'' }}">{{ trans('menu.project-detail') }}</a></li>
                                        <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/project/create') ? 'active':'' }}">{{ trans('menu.create-project') }}</a></li>
                                    </ul>
                                    </li>
                                    <li>
                                    <a href="#" class="{{ Request::is(app()->getLocale().'/applications/calendar') ? 'active':'' }}">
                                        <span class="nav-icon uil uil-calendar-alt"></span>
                                        <span class="menu-text">{{ trans('menu.calendar-menu-title') }}</span>
                                    </a>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-users-alt"></span>
                                            <span class="menu-text">{{ trans('menu.user-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/member') ? 'active':'' }}">{{ trans('menu.user-team') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/grid') ? 'active':'' }}">{{ trans('menu.user-grid') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/list') ? 'active':'' }}">{{ trans('menu.user-list') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/grid-style') ? 'active':'' }}">{{ trans('menu.user-grid-style') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/group') ? 'active':'' }}">{{ trans('menu.user-group') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/add') ? 'active':'' }}">{{ trans('menu.user-add') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/user/table') ? 'active':'' }}">{{ trans('menu.user-table') }}</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/contact/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-at"></span>
                                            <span class="menu-text">{{ trans('menu.contact-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/contact/grid') ? 'active':'' }}" href="#">{{ trans('menu.contact-grid') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/contact/list') ? 'active':'' }}" href="#">{{ trans('menu.contact-list') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/contact/create') ? 'active':'' }}" href="#">{{ trans('menu.contact-list') }}</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/note') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-clipboard-notes"></span>
                                            <span class="menu-text">{{ trans('menu.note-menu-title') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/todo') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-check-square"></span>
                                            <span class="menu-text">{{ trans('menu.todo-menu-title') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li>
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/kanban') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-expand-arrows"></span>
                                            <span class="menu-text">{{ trans('menu.kanban-menu-title') }}</span>
                                        </a>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/import_export/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-exchange"></span>
                                            <span class="menu-text">{{ trans('menu.ie-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/import_export/import') ? 'active':'' }}" href="#">{{ trans('menu.ie-import') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/import_export/export') ? 'active':'' }}" href="#">{{ trans('menu.ie-export') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/import_export/export-selected') ? 'active':'' }}" href="#">{{ trans('menu.ie-export-selected') }}</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/filemanager') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-repeat"></span>
                                            <span class="menu-text">{{ trans('menu.filemanager-menu-title') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/task') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-lightbulb-alt"></span>
                                            <span class="menu-text">{{ trans('menu.task-menu-title') }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/bookmark') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-bookmark"></span>
                                            <span class="menu-text">{{ trans('menu.bookmark-menu-title') }}</span>
                                        </a>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/social/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-apps"></span>
                                            <span class="menu-text">{{ trans('menu.social-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li class="nav-item"><a href="#" class="{{ Request::is(app()->getLocale().'/applications/social/profile') ? 'active':'' }}">{{ trans('menu.social-profile') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/applications/social/profile-settings') ? 'active':'' }}">{{ trans('menu.social-profile-setting') }}</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/support/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-user"></span>
                                            <span class="menu-text">{{ trans('menu.support-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/support/support-ticket') ? 'active':'' }}" href="#">{{ trans('menu.support-ticket') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/support/support-details') ? 'active':'' }}" href="#">{{ trans('menu.support-ticket-detail') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/support/new-ticket') ? 'active':'' }}" href="#">{{ trans('menu.support-new-ticket') }}</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/applications/job/*') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-search"></span>
                                            <span class="menu-text">{{ trans('menu.job-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/job/job-search') ? 'active':'' }}" href="#">{{ trans('menu.job-search') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/job/job-search-list') ? 'active':'' }}" href="#">{{ trans('menu.job-search-list') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/job/job-detail') ? 'active':'' }}" href="#">{{ trans('menu.job-detail') }}</a></li>
                                            <li><a class="{{ Request::is(app()->getLocale().'/applications/job/job-apply') ? 'active':'' }}" href="#">{{ trans('menu.job-apply') }}</a></li>
                                        </ul>
                                    </li>
                                    <li class="has-subMenu-left">
                                        <a href="#" class="{{ Request::is(app()->getLocale().'/table/*') || Request::is(app()->getLocale().'/pages/dynamic-table') ? 'active':'' }}">
                                            <span class="nav-icon uil uil-table"></span>
                                            <span class="menu-text">{{ trans('menu.table-menu-title') }}</span>
                                        </a>
                                        <ul class="subMenu">
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/table/basic') ? 'active':'' }}">{{ trans('menu.table-basic') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/table/data') ? 'active':'' }}">{{ trans('menu.table-data') }}</a></li>
                                            <li><a href="#" class="{{ Request::is(app()->getLocale().'/pages/dynamic-table') ? 'active':'' }}">{{ trans('menu.dynamic-table-menu-title') }}</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="has-subMenu">
                        <a href="#" class="{{ Request::is(app()->getLocale().'/customer/*') ? 'active':'' }}">Crud</a>
                        <ul class="subMenu">
                            <li class="has-subMenu-left">
                                <a href="#" class="{{ Request::is(app()->getLocale().'/customer/*') ? 'active':'' }}">
                                    <span class="nav-icon uil uil-database"></span>
                                    <span class="menu-text">{{ trans('menu.customer-crud-menu-title') }}</span>
                                </a>
                                <ul class="subMenu">
                                    <li><a class="{{ Request::is(app()->getLocale().'/customer/list') ? 'active':'' }}" href="#">{{ trans('menu.customer-view-all') }}</a></li>
                                    <li><a class="{{ Request::is(app()->getLocale().'/customer/create') ? 'active':'' }}" href="#">{{ trans('menu.customer-add-new') }}</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="navbar-right">
        <ul class="navbar-right__menu">
            <li class="nav-search">
                <a href="#" class="search-toggle">
                    <i class="uil uil-search"></i>
                    <i class="uil uil-times"></i>
                </a>
                <form action="/" class="search-form-topMenu">
                    <span class="search-icon uil uil-search"></span>
                    <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..." aria-label="Search">
                </form>
            </li>
            <li class="nav-order">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle icon-active">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="svg">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </a>
                    <div class="dropdown-wrapper">
                        <h2 class="dropdown-wrapper__title">Latest Orders <span class="badge-circle badge-primary ms-1">5</span></h2>
                        <ul>
                            <li class="nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--primary">
                                    <i class="uil uil-shopping-bag"></i>
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Order #2029</a>
                                        <span>by John Smith</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">299.00 EGP</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--success">
                                    <i class="uil uil-shopping-bag"></i>
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Order #2028</a>
                                        <span>by Sarah Johnson</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">179.98 EGP</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--info">
                                    <i class="uil uil-shopping-bag"></i>
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Order #2027</a>
                                        <span>by Michael Brown</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">136.50 EGP</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--warning">
                                    <i class="uil uil-shopping-bag"></i>
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Order #2026</a>
                                        <span>by Emily Davis</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">79.96 EGP</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--secondary">
                                    <i class="uil uil-shopping-bag"></i>
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Order #2025</a>
                                        <span>by David Wilson</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">259.98 EGP</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <a href="" class="dropdown-wrapper__more">See All Orders</a>
                    </div>
                </div>
            </li>
            <li class="nav-message">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle icon-active">
                        <img class="svg" src="{{ asset('assets/img/svg/message.svg') }}" alt="img">
                    </a>
                    <div class="dropdown-wrapper">
                        <h2 class="dropdown-wrapper__title">Messages <span class="badge-circle badge-success ms-1">2</span></h2>
                        <ul>
                            <li class="author-online has-new-message">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                        <span class="msg-count badge-circle badge-success badge-sm">1</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-offline has-new-message">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                        <span class="msg-count badge-circle badge-success badge-sm">1</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-online has-new-message">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                        <span class="msg-count badge-circle badge-success badge-sm">1</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-offline">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-offline">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <a href="" class="dropdown-wrapper__more">See All Message</a>
                    </div>
                </div>
            </li>
            <li class="nav-notification">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle icon-active">
                        <img class="svg" src="{{ asset('assets/img/svg/alarm.svg') }}" alt="img">
                    </a>
                    <div class="dropdown-wrapper">
                        <h2 class="dropdown-wrapper__title">Notifications <span class="badge-circle badge-warning ms-1">4</span></h2>
                        <ul>
                            <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--primary">
                                    <img src="{{ asset('assets/img/svg/inbox.svg') }}" alt="inbox" class="svg">
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                        <span>sent you a message</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">5 hours ago</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--secondary">
                                    <img src="{{ asset('assets/img/svg/upload.svg') }}" alt="upload" class="svg">
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                        <span>sent you a message</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">5 hours ago</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--success">
                                    <img src="{{ asset('assets/img/svg/log-in.svg') }}" alt="log-in" class="svg">
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                        <span>sent you a message</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">5 hours ago</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--info">
                                    <img src="{{ asset('assets/img/svg/at-sign.svg') }}" alt="at-sign" class="svg">
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                        <span>sent you a message</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">5 hours ago</span>
                                    </p>
                                </div>
                            </li>
                            <li class="nav-notification__single nav-notification__single d-flex flex-wrap">
                                <div class="nav-notification__type nav-notification__type--danger">
                                    <img src="{{ asset('assets/img/svg/heart.svg') }}" alt="heart" class="svg">
                                </div>
                                <div class="nav-notification__details">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">James</a>
                                        <span>sent you a message</span>
                                    </p>
                                    <p>
                                        <span class="time-posted">5 hours ago</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <a href="" class="dropdown-wrapper__more">See all incoming activity</a>
                    </div>
                </div>
            </li>
            {{-- <li class="nav-settings">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle">
                        <img src="{{ asset('assets/img/setting.png') }}" alt="img">
                    </a>
                    <div class="dropdown-wrapper dropdown-wrapper--large">
                        <ul class="list-settings">
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/mail.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">All Features</a>
                                    </h6>
                                    <p>Introducing Increment subscriptions </p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/color-palette.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Themes</a>
                                    </h6>
                                    <p>Third party themes that are compatible</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/home.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Payments</a>
                                    </h6>
                                    <p>We handle billions of dollars</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/video-camera.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Design Mockups</a>
                                    </h6>
                                    <p>Share planning visuals with clients</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/document.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Content Planner</a>
                                    </h6>
                                    <p>Centralize content gethering and editing</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/microphone.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Diagram Maker</a>
                                    </h6>
                                    <p>Plan user flows & test scenarios</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li> --}}
            <li class="nav-flag-select">
                <div class="dropdown-custom">
                    @switch(app()->getLocale())
                        @case('en')
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/eng.png') }}" alt="" class="rounded-circle"></a>
                            @break
                        @case('ar')
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/iraq.png') }}" alt="" class="rounded-circle"></a>
                            @break
                        @case('gr')
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/ger.png') }}" alt="" class="rounded-circle"></a>
                            @break
                        @default
                            <a href="javascript:;" class="nav-item-toggle">

                                @if(LaravelLocalization::getCurrentLocale() == 'ar')
                                    <img src="{{ asset('assets/img/iraq.png') }}" alt="" class="rounded-circle">
                                @else
                                    <img src="{{ asset('assets/img/eng.png') }}" alt="" class="rounded-circle">
                                @endif
                            </a>
                            @break
                    @endswitch
                    <div class="dropdown-wrapper dropdown-wrapper--small">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a hreflang="{{ $localeCode }}"
                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                @if($localeCode == 'ar')
                                    <img src="{{ asset('assets/img/iraq.png') }}" alt="">
                                @else
                                    <img src="{{ asset('assets/img/eng.png') }}" alt="">
                                @endif
                                {{ $properties['native'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </li>
            <li class="nav-author">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/author-nav.jpg') }}" alt="" class="rounded-circle">
                        @if(Auth::check())
                            <span class="nav-item__title">{{ Auth::user()->name }}<i class="las la-angle-down nav-item__arrow"></i></span>
                        @endif
                    </a>
                    <div class="dropdown-wrapper">
                        <div class="nav-author__info">
                            <div class="author-img">
                                <img src="{{ asset('assets/img/author-nav.jpg') }}" alt="" class="rounded-circle">
                            </div>
                            <div>
                                @if(Auth::check())
                                    <h6 class="text-capitalize">{{ Auth::user()->name }}</h6>
                                @endif
                                <span>UI Designer</span>
                            </div>
                        </div>
                        <div class="nav-author__options">
                            <ul>
                                <li>
                                    <a href="">
                                        <img src="{{ asset('assets/img/svg/user.svg') }}" alt="user" class="svg"> Profile</a>
                                </li>
                                <li>
                                    <a href="">
                                        <img src="{{ asset('assets/img/svg/settings.svg') }}" alt="settings" class="svg"> Settings</a>
                                </li>
                                <li>
                                    <a href="">
                                        <img src="{{ asset('assets/img/svg/key.svg') }}" alt="key" class="svg"> Billing</a>
                                </li>
                                <li>
                                    <a href="">
                                        <img src="{{ asset('assets/img/svg/users.svg') }}" alt="users" class="svg"> Activity</a>
                                </li>
                                <li>
                                    <a href="">
                                        <img src="{{ asset('assets/img/svg/bell.svg') }}" alt="bell" class="svg"> Help</a>
                                </li>
                            </ul>
                            <a href="" class="nav-author__signout" onclick="event.preventDefault();document.getElementById('logout').submit();">
                                <img src="{{ asset('assets/img/svg/log-out.svg') }}" alt="log-out" class="svg">
                                 Sign Out</a>
                                <form style="display:none;" id="logout" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    @method('post')
                                </form>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="navbar-right__mobileAction d-md-none">
            <a href="#" class="btn-search">
                <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg feather-search">
                <img src="{{ asset('assets/img/svg/x.svg') }}" alt="x" class="svg feather-x">
            </a>
            <a href="#" class="btn-author-action">
                <img src="{{ asset('assets/img/svg/more-vertical.svg') }}" alt="more-vertical" class="svg"></a>
        </div>
    </div>
</nav>
