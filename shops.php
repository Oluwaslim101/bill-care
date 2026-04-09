<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Redirect if user not found
if (!$user) {
    header('Location: login.php');
    exit();
}

// Assign user data
$full_name = $user['full_name'];
$balance = $user['balance'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';

// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

try {
    // Fetch all shops
    $stmt = $sql->query("SELECT id, shop_name, shop_description, address, logo, ceo_name FROM shop_owners ORDER BY shop_name ASC");
    $shops = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching shops: " . $e->getMessage());
}
?>


<!DOCTYPE html> 

<html lang="en">    

<head> 
<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" /> <meta name="apple-mobile-web-app-capable" content="yes" /> <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> <meta name="theme-color" content="#000000"> <title>DtheHub</title> <meta name="description" content="Finapp HTML Mobile Template"> <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" /> <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32"> <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png"> <link rel="stylesheet" href="assets/css/style.css"> <link rel="manifest" href="__manifest.json"> <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script><link rel="stylesheet" href="styles.css"> 
  <style>
  
/* Fixed Bottom Navigation */
.nav {
position: fixed;
bottom: 0;
left: 50%;
transform: translateX(-50%);
width: 100%;
max-width: 410px;
display: flex;
justify-content: space-around;
background: white;
padding: 12px 7px;
border-radius: 8px 8px 0 0;
box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1);
z-index: 1000;
}

.nav a {
text-decoration: none;
color: gray;
font-size: 12px;
text-align: center;
display: flex;
flex-direction: column;
align-items: center;
gap: 3px;
flex: 1;
transition: color 0.3s ease;
}

.nav a i {
font-size: 20px;
color: gray;
transition: color 0.3s ease;
}

.nav a span {
font-size: 12px;
font-weight: 500;
}

.nav a.active i,
.nav a.active span {
color: green;
font-weight: bold;
}


.product-card {
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-image img {
  width: 100%;
  height: 160px;
  object-fit: cover;
}

.product-details {
  padding: 10px;
  text-align: center;
}

.product-title {
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 5px;
  color: #333;
}

.product-price {
  font-size: 13px;
  font-weight: bold;
  color: #28a745;
  margin-bottom: 10px;
}
.chat-modal {
    display: flex;
    flex-direction: column;
    height: 100vh;
    max-height: 100vh;
    overflow: hidden;
}

.chat-header,
.chat-footer {
    flex-shrink: 0;
}

.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #e5ddd5;
}

  </style>
</head>
<body><!-- loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>
<!-- * loader -->

 <!-- App Header -->
  <div class="appHeader bg-primary text-light">
    <div class="left">
      <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
        <ion-icon name="menu-outline"></ion-icon>
      </a>
    </div>
    <div class="pageTitle">
     <h3> Online Stores</h3>
    </div>
   <div class="right">
    <div style="position: relative; padding-right: 12px;" onclick="showCartModal()">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span id="cartCountBadge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="right: -6px;">
            0
        </span>
    </div>
</div>

       
</div>
<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-1 full">
        <div class="section-title">Available Shops</div>
        <div class="transactions">
<?php foreach ($shops as $shop): ?>
    <a href="shop_products.php?shop_id=<?= $shop['id'] ?>" class="item">
        <div class="detail">
            <?php if (!empty($shop['logo']) && file_exists($shop['logo'])): ?>
                <img src="<?= htmlspecialchars($shop['logo']) ?>" alt="Logo" class="image-block imaged w48 rounded-circle">
            <?php else: ?>
                <div class="image-block imaged w48 rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white">
                    <i class="fas fa-store"></i>
                </div>
            <?php endif; ?>
            <div>
                <strong><?= htmlspecialchars($shop['shop_name']); ?></strong>
                <p class="mb-0">
                    <?= htmlspecialchars($shop['shop_description']); ?><br>
                    <small class="text-muted"><?= htmlspecialchars($shop['address']); ?></small>
                </p>
            </div>
        </div>
    </a>
<?php endforeach; ?>
        </div>
                <!-- Chat Button -->
        <div class="text-end mt-1 me-3">
           <button class="btn btn-sm btn-outline-primary"
  onclick="loadShopChat(<?= $shop['id'] ?>, '<?= htmlspecialchars($shop['shop_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($shop['logo']) ?>')">
  Chat with Shop
</button>
        </div>
    <!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- profile box -->
                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">
                           <img src="<?= $avatar_url ?>" alt="User Avatar" class="imaged w32" style='h32 w32'>
                        </div>
                        <div class="in">
                             <span>Welcome, <b><?= htmlspecialchars($user['nick_name']) ?></b></span>
                         
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                    <!-- * profile box -->
                    <!-- balance -->
                    <div class="sidebar-balance">
                        <div class="listview-title">Balance</div>
                        <div class="in">
                        <h2 id="balance-amount" style="font-size: 20px; margin: 2px 0;">₦<?= number_format($balance, 2); ?></h2>
                        </div>
                    </div>
                    <!-- * balance -->




                   <!-- User Purchase Sidebar -->
<div class="listview-title mt-1">Shop & Purchase</div>
<ul class="listview flush transparent no-line image-listview">

  <li>
    <a href="shops.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="home-outline"></ion-icon>
      </div>
      <div class="in">Home / Shops</div>
    </a>
  </li>

  <li>
    <a href="my_orders.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="clipboard-outline"></ion-icon>
      </div>
      <div class="in">My Orders</div>
    </a>
  </li>

  <li>
    <a href="cart.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="cart-outline"></ion-icon>
      </div>
      <div class="in">Cart</div>
    </a>
  </li>

  <li>
    <a href="wallet.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="wallet-outline"></ion-icon>
      </div>
      <div class="in">Wallet</div>
    </a>
  </li>

  <li>
    <a href="profile.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="person-outline"></ion-icon>
      </div>
      <div class="in">Profile</div>
    </a>
  </li>

  <li>
    <a href="support.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
      </div>
      <div class="in">Support</div>
    </a>
  </li>

  <li>
    <a href="logout.php" class="item">
      <div class="icon-box bg-primary">
        <ion-icon name="log-out-outline"></ion-icon>
      </div>
      <div class="in">Log Out</div>
    </a>
  </li>

</ul>

                   

                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="chat-modal" style="height: 100vh; display: flex; flex-direction: column;">
        <div class="chat-header bg-success text-white d-flex align-items-center justify-content-between px-3 py-2">
          <div class="d-flex align-items-center gap-2">
            <img id="chatLogo" src="" class="rounded-circle" width="40" height="40">
            <strong id="chatShopName">Shop</strong>
          </div>
          <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

     <div class="chat-body" id="chatBody">
          <div class="text-center text-muted mt-4">Loading chat...</div>
        </div>

        <div class="chat-footer d-flex border-top p-2 bg-light">
          <textarea id="messageInput" class="form-control me-2" rows="1" placeholder="Type a message..." style="resize: none;"></textarea>
          <button class="btn btn-success" onclick="sendMessage()">Send</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentShopId = 0;

function loadShopChat(shopId, shopName, shopLogo) {
  currentShopId = shopId;
  $('#chatShopName').text(shopName);
  $('#chatLogo').attr('src', shopLogo || 'default_logo.png');
  $('#chatModal').modal('show');
  loadChats();
}

function formatTime(datetime) {
  const date = new Date(datetime);
  let h = date.getHours(), m = date.getMinutes();
  const ampm = h >= 12 ? 'PM' : 'AM';
  h = h % 12 || 12;
  return `${h}:${m < 10 ? '0' + m : m} ${ampm}`;
}

function scrollChatToBottom() {
  const el = document.getElementById('chatBody');
  el.scrollTop = el.scrollHeight;
}

function loadChats() {
  if (!currentShopId) return;
  $.get('fetch_shop_chat.php', { shop_id: currentShopId }, function(res) {
    if (res.success) {
      let html = '';
      res.data.forEach(chat => {
        const bubbleClass = chat.sender === 'customer' ? 'shop_owner' : 'customer';
        const time = formatTime(chat.created_at);
        let ticks = '';
        if (chat.sender === 'customer') {
          ticks = chat.is_read == 1
            ? '<span class="tick" style="color:#34b7f1;">&#10003;&#10003;</span>'
            : '<span class="tick" style="color:gray;">&#10003;</span>';
        }
        html += `
          <div class="chat-bubble ${bubbleClass}" style="max-width:70%; margin:6px 0; padding:10px; border-radius:10px; background:${bubbleClass==='shop_owner'?'#dcf8c6':'#fff'}; align-self:${bubbleClass==='shop_owner'?'flex-end':'flex-start'};">
            ${chat.message}
            <small class="d-block text-end text-muted" style="font-size:11px;">${time} ${ticks}</small>
          </div>`;
      });
      $('#chatBody').html(html);
      scrollChatToBottom();
      $.post('mark_as_read.php', { shop_id: currentShopId });
    } else {
      $('#chatBody').html('<div class="text-center text-muted">Failed to load chats.</div>');
    }
  }, 'json');
}

function sendMessage() {
  const msg = $('#messageInput').val().trim();
  if (!msg || !currentShopId) return;
  $.post('send_shop_chat.php', {
    shop_id: currentShopId,
    message: msg
  }, function(res) {
    if (res.success) {
      $('#messageInput').val('');
      loadChats();
    } else {
      alert('Failed to send message.');
    }
  }, 'json');
}

$('#messageInput').keypress(function(e) {
  if (e.which === 13 && !e.shiftKey) {
    e.preventDefault();
    sendMessage();
  }
});

setInterval(loadChats, 3000);
</script>


<?php include 'footer.php'; ?>
</body>
</html>
