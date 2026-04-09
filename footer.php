
<!-- Notification Modal (Finapp Style) -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Notifications will be dynamically inserted here -->
                <div id="notification-list">
                    <p>Loading notifications...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="notificationDetailsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notification Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content is dynamically inserted here -->
      </div>
    </div>
  </div>
</div>


<!-- Bottom Navigation -->
<nav class="nav">   
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">    
        <i class="fas fa-home"></i>    
        <span>Home</span>    
    </a>    
    <a href="rewards.php" class="<?= basename($_SERVER['PHP_SELF']) == 'rewards.php' ? 'active' : '' ?>">    
        <i class="fas fa-gift"></i>    
        <span>Rewards</span>    
    </a>    
    <a href="fixed_savings.php" class="d-none <?= basename($_SERVER['PHP_SELF']) == 'fixed_savings.php' ? 'active' : '' ?>">    
        <i class="fas fa-lock"></i>     
        <span>Savings</span>
       
    </a>   
    <a href="transactions.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transactions.php' ? 'active' : '' ?>">    
        <i class="fas fa-receipt"></i>    
        <span>Transactions</span>    
    </a>    
    <a href="profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">    
        <i class="fas fa-user"></i>    
        <span>Profile</span>    
    </a>
</nav>    



<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="assets/js/plugins/splide/splide.min.js"></script>
<script src="assets/js/plugins/apexcharts/apexcharts.min.js"></script>
<script src="assets/js/base.js"></script>

<script>
    // Fetch unread notifications
    function fetchUnreadNotifications() {
        $.ajax({
            url: 'get_unread_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const count = data.unread_count;
                const notifBadge = $('#notification-count');
                
                if (count > 0) {
                    notifBadge.text(count).show();
                } else {
                    notifBadge.hide();
                }
            }
        });
    }

$('#notificationsButton').on('click', function () {
    $.ajax({
        url: 'get_all_notifications.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            const notifications = data.notifications;
            let notificationHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <button class="btn btn-sm btn-outline-primary" id="viewAllBtn">View All</button>
                    <button class="btn btn-sm btn-outline-danger" id="clearAllBtn">Clear All</button>
                </div>
            `;

            if (notifications.length === 0) {
                notificationHTML += `<p class="text-muted text-center mt-3">No notifications available.</p>`;
            } else {
                notifications.forEach(notification => {
                    notificationHTML += `
                        <div class="notification-item clickable-notification" 
                             data-id="${notification.id}" 
                             data-type="${notification.action_type}"
                             style="border-bottom: 1px solid #e0e0e0; padding: 8px 0; cursor: pointer;">
                            <p class="mb-0">${notification.message}</p>
                            <small class="text-muted d-block mt-0">${notification.created_at}</small>
                        </div>
                    `;
                });
            }

            $('#notification-list').html(notificationHTML);
            $('#notificationsModal').modal('show');
        }
    });
});

// View All and Clear All
$('#notification-list').on('click', '#viewAllBtn', function () {
    window.location.href = 'notifications.php';
});

$('#notification-list').on('click', '#clearAllBtn', function () {
    if (confirm('Are you sure you want to delete all notifications?')) {
        $.post('mark-notification-read.php', function (res) {
            if (res.success) {
                $('#notification-list').html('<p class="text-center text-muted mt-3">All notifications cleared.</p>');
            } else {
                alert('Failed to clear notifications.');
            }
        }, 'json');
    }
});

// 🔍 Handle clicking on individual notification
$('#notification-list').on('click', '.clickable-notification', function () {
    const notifId = $(this).data('id');
    const actionType = $(this).data('type');

    // Example: AJAX fetch details based on type/id
    $.get('get_notification_details.php', { id: notifId, type: actionType }, function (res) {
        if (res.success) {
            $('#notificationDetailsModal .modal-body').html(`
                <h5 class="mb-2">${res.title}</h5>
                <p>${res.message}</p>
                <small class="text-muted">${res.created_at}</small>
            `);
            $('#notificationDetailsModal').modal('show');
        } else {
            alert('Failed to load notification details.');
        }
    }, 'json');
});

    // Initial fetch of unread notifications
    $(document).ready(function() {
        fetchUnreadNotifications();
    });
</script>


<!-- PWA Service Worker -->
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
    .then((registration) => {
        console.log('Service Worker registered with scope:', registration.scope);
    })
    .catch((err) => {
        console.error('Service Worker registration failed:', err);
    });
}
</script>

<!-- Pusher Notifications -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
Pusher.logToConsole = true;

const pusher = new Pusher('d721521425aa5a667ee5', {
    cluster: 'us2'
});

const channel = pusher.subscribe('notifications');

channel.bind('fcm-token', function(data) {
    alert('New Notification: ' + data.message);
    console.log('Notification received:', data);
    fetchNotifications(); // Optional: refresh notifications automatically
});
</script>
