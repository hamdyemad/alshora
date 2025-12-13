<li class="nav-notification">
    <div class="dropdown-custom">
        <a href="javascript:;" class="nav-item-toggle icon-active" id="notification-toggle">
            <img src="{{ asset('assets/img/svg/alarm.svg') }}" alt="img" class="svg">
            <span class="badge-circle badge-success ms-2" id="notification-count" style="display: none;">0</span>
        </a>
        <div class="dropdown-parent-wrapper">
            <div class="dropdown-wrapper">
                <h2 class="dropdown-wrapper__title">{{ trans('common.notifications') }}
                    <span class="badge-circle badge-success ms-2" id="notification-badge" style="display: none;">0</span>
                </h2>
                <div class="dropdown-wrapper__body" id="notifications-container">
                    <div class="text-center py-4" id="loading-notifications">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="text-center py-4" id="no-notifications" style="display: none;">
                        <p class="text-muted">{{ trans('common.no_notifications') }}</p>
                    </div>
                </div>
                <div class="dropdown-wrapper__body_bottom">
                    <button class="btn btn-sm btn-outline-primary me-2" id="mark-all-read" style="display: none;">
                        {{ trans('common.mark_all_read') }}
                    </button>
                    <a href="#" class="btn btn-primary btn-default btn-squared text-capitalize">
                        {{ trans('common.all_notifications') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</li>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationToggle = document.getElementById('notification-toggle');
    const notificationCount = document.getElementById('notification-count');
    const notificationBadge = document.getElementById('notification-badge');
    const notificationsContainer = document.getElementById('notifications-container');
    const loadingNotifications = document.getElementById('loading-notifications');
    const noNotifications = document.getElementById('no-notifications');
    const markAllReadBtn = document.getElementById('mark-all-read');

    // Load notifications when dropdown is opened
    notificationToggle.addEventListener('click', function() {
        loadNotifications();
    });

    // Mark all as read
    markAllReadBtn.addEventListener('click', function() {
        markAllAsRead();
    });

    function loadNotifications() {
        loadingNotifications.style.display = 'block';
        noNotifications.style.display = 'none';

        fetch('/{{ LaravelLocalization::getCurrentLocale() }}/admin/notifications/unread', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingNotifications.style.display = 'none';

            if (data.success) {
                displayNotifications(data.notifications);
                updateNotificationCount(data.unread_count);
            } else {
                showNoNotifications();
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            loadingNotifications.style.display = 'none';
            showNoNotifications();
        });
    }

    function displayNotifications(notifications) {
        if (notifications.length === 0) {
            showNoNotifications();
            return;
        }

        let html = '';
        notifications.forEach(notification => {
            const iconClass = getNotificationIcon(notification.type);
            const bgClass = getNotificationBgClass(notification.type);

            html += `
                <div class="dropdown-wrapper__item">
                    <div class="nav-notification__single nav-notification__single--unread">
                        <div class="nav-notification__type ${bgClass}">
                            <i class="uil ${iconClass}"></i>
                        </div>
                        <div class="nav-notification__details">
                            <p>
                                <a href="#" class="subject stretched-link text-truncate" style="max-width: 180px;" onclick="markAsRead('${notification.id}')">
                                    ${getNotificationTitle(notification)}
                                </a>
                            </p>
                            <p>
                                <span class="time-posted">${notification.created_at}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });

        notificationsContainer.innerHTML = html;
        markAllReadBtn.style.display = notifications.length > 0 ? 'inline-block' : 'none';
    }

    function showNoNotifications() {
        notificationsContainer.innerHTML = '';
        noNotifications.style.display = 'block';
        markAllReadBtn.style.display = 'none';
    }

    function updateNotificationCount(count) {
        if (count > 0) {
            notificationCount.textContent = count;
            notificationCount.style.display = 'inline-block';
            notificationBadge.textContent = count;
            notificationBadge.style.display = 'inline-block';
        } else {
            notificationCount.style.display = 'none';
            notificationBadge.style.display = 'none';
        }
    }

    function getNotificationIcon(type) {
        switch(type) {
            case 'App\\Notifications\\NewBookingNotification':
                return 'uil-calendar-alt';
            default:
                return 'uil-bell';
        }
    }

    function getNotificationBgClass(type) {
        switch(type) {
            case 'App\\Notifications\\NewBookingNotification':
                return 'nav-notification__type--bg-primary';
            default:
                return 'nav-notification__type--bg-secondary';
        }
    }

    function getNotificationTitle(notification) {
        switch(notification.type) {
            case 'App\\Notifications\\NewBookingNotification':
                return `{{ trans('notification.new_appointment_from') }} ${notification.data.customer_name}`;
            default:
                return notification.data.message || '{{ trans('notification.new_notification') }}';
        }
    }

    function markAsRead(notificationId) {
        fetch(`/{{ LaravelLocalization::getCurrentLocale() }}/admin/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications(); // Reload notifications
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }

    function markAllAsRead() {
        fetch('/{{ LaravelLocalization::getCurrentLocale() }}/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications(); // Reload notifications
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
        });
    }

    // Load initial notification count
    loadNotifications();
});
</script>
