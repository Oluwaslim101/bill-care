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

if (!isset($_GET['shop_id']) || !is_numeric($_GET['shop_id'])) {
    die("Invalid shop.");
}

$shopId = intval($_GET['shop_id']);

try {
    // Fetch shop info
    $stmt = $sql->prepare("SELECT shop_name, shop_description, logo FROM shop_owners WHERE id = ?");
    $stmt->execute([$shopId]);
    $shop = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$shop) {
        die("Shop not found.");
    }

    // Fetch products for this shop
    $stmt2 = $sql->prepare("SELECT product_name, product_description, price, image_url FROM ecommerce_products WHERE shop_id = ?");
    $stmt2->execute([$shopId]);
    $products = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>


<!DOCTYPE html> 
<html lang="en">    
<head> 
<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" /> <meta name="apple-mobile-web-app-capable" content="yes" /> <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> <meta name="theme-color" content="#000000"> <title>YenTown Hub</title> <meta name="description" content="Finapp HTML Mobile Template"> <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" /> <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32"> <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png"> <link rel="stylesheet" href="assets/css/style.css"> <link rel="manifest" href="__manifest.json"> <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script><link rel="stylesheet" href="styles.css"> 
  <style>
  
/* Fixed Bottom Navigation */ .nav { position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 410px; display: flex; justify-content: space-around; background: white; padding: 12px 7px; border-radius: 8px 8px 0 0; box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1); z-index: 1000; }

.nav a { text-decoration: none; color: gray; font-size: 12px; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 3px; flex: 1; transition: color 0.3s ease; }

.nav a i { font-size: 20px; color: gray; transition: color 0.3s ease; }

.nav a span { font-size: 12px; font-weight: 500; }

.nav a.active i, .nav a.active span { color: green; font-weight: bold; }

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


  </style>
</head>
<body><!-- loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>
<!-- * loader -->

 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        
      <img src="<?= htmlspecialchars($shop['logo'] ?? 'default-logo.png') ?>" alt="<?= htmlspecialchars($shop['shop_name']) ?>" class="rounded-circle" style="width: 35px; height: 35px;"><br>

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

  <h3 class="section full mt-1 text-center">Trending Products</h3>
  <?php if (count($products) > 0): ?>
<div class="container my-2">
  <div class="row g-3">
    <?php foreach ($products as $product): ?>
      <div class="col-6">
        <div class="product-card">
          <div class="product-image">
            <img src="<?= htmlspecialchars($product['image_url'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
          </div>
          <div class="product-details">
            <h5 class="product-title"><?= htmlspecialchars($product['product_name']) ?></h5>
            <p class="product-price">₦<?= number_format($product['price'], 2) ?></p>
        <button class="btn btn-primary btn-sm w-100" 
        onclick='showProductModal({
   name: "<?= addslashes($product["product_name"]) ?>",
   price: "<?= $product["price"] ?>",
   description: "<?= addslashes($product["product_description"]) ?>",
   image: "<?= $product["image_url"] ?? "placeholder.png" ?>",
   shop_id: "<?= $shopId ?>"
})'>View Item</button>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>


  <?php else: ?>
    <div class="alert alert-info">No products found in this shop.</div>
  <?php endif; ?>
  
  <div class="text-center mb-3">
    <a href="shops.php" class="btn btn-secondary">Back to Shops</a>
  </div>
</div>
  </div>
</div>

<!-- Product Detail Bottom Sheet Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-bottom sheet modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalProductName"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-left">
        <img id="modalProductImage" src="" class="img-fluid rounded mb-2" alt="" style="max-height: 150px;">
        <p id="modalProductDescription" class="text-muted "></p>
        <h5 class="text-success mb-1" id="modalProductPrice"></h5>
        <button id="confirmAddToCart" class="btn btn-primary w-100">Confirm Add to Cart</button>
      </div>
    </div>
  </div>
</div>


<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1">
  <div class="modal-dialog modal-bottom sheet modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="cartItemsList" class="list-group mb-3"></ul>
        <div class="d-flex justify-content-between">
          <strong>Total:</strong>
          <strong id="cartTotal">₦0.00</strong>
        </div>
      <button class="btn btn-success w-100 mt-3" onclick="showCheckout()">Proceed to Checkout</button>
      </div>
    </div>
  </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-bottom sheet modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="checkoutForm">
        <div class="mb-2">
            <label class="form-label">Delivery Address</label>
            <textarea class="form-control" id="deliveryAddress" required></textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Payment Method</label>
            <select class="form-select" id="paymentMethod" required>
              <option value="full">Pay Full Amount</option>
              <option value="half">Pay 50% Now</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Delivery Fee</label>
            <input type="number" class="form-control" id="deliveryFee" value="100" readonly>
          </div>
          <div class="d-grid">
           <button type="submit" class="btn btn-success" id="checkoutPayButton">Proceed to Checkout</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Bottom Navigation -->
<nav class="nav">   
<a href="index.php" class="active">    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="cards.html">    
    <i class="fas fa-receipt"></i>    
    <span>Cards</span>    
</a>    <a href="transactions.php">    
    <i class="fas fa-receipt"></i>    
    <span>Transactions</span>    
</a>    
<a href="profile.php">    
    <i class="fas fa-user"></i>    
    <span>Profile</span>    
</a>
</nav>   

    <!-- * App Bottom Menu -->
    
  <script>
  let selectedProduct = {};

  function showProductModal(product) {
      product.shop_id = product.shop_id;
    selectedProduct = product;

    document.getElementById('modalProductName').textContent = product.name;
    document.getElementById('modalProductPrice').textContent = "₦" + parseFloat(product.price).toLocaleString();
    document.getElementById('modalProductDescription').textContent = product.description;
    document.getElementById('modalProductImage').src = product.image || 'placeholder.png';

    const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
    modal.show();
  }

  document.getElementById('confirmAddToCart').addEventListener('click', function () {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existing = cart.find(item => item.name === selectedProduct.name);

    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ ...selectedProduct, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();

    setTimeout(() => {
      location.reload(); // Reload after modal closes
    }, 300);
  });

  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cartCountBadge').textContent = totalItems;
  }

  function showCartModal() {
    renderCartItems();
    const modal = new bootstrap.Modal(document.getElementById('cartModal'));
    modal.show();

    // Trigger reload on modal close
    const cartModalEl = document.getElementById('cartModal');
    cartModalEl.addEventListener('hidden.bs.modal', function () {
      location.reload();
    }, { once: true }); // only once to prevent multiple reloads
  }

  function renderCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const list = document.getElementById('cartItemsList');
    const totalEl = document.getElementById('cartTotal');

    list.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
      const li = document.createElement('li');
      li.className = "list-group-item d-flex justify-content-between align-items-center";

      li.innerHTML = `
        <div>
          <strong>${item.name}</strong><br>
          ₦${parseFloat(item.price).toLocaleString()}
        </div>
        <div class="d-flex align-items-center">
          <button class="btn btn-sm btn-outline-secondary me-1" onclick="changeQty(${index}, -1)">−</button>
          <span>${item.quantity}</span>
          <button class="btn btn-sm btn-outline-secondary ms-1" onclick="changeQty(${index}, 1)">+</button>
        </div>
      `;

      list.appendChild(li);
      total += item.price * item.quantity;
    });

    totalEl.textContent = '₦' + total.toLocaleString(undefined, { minimumFractionDigits: 2 });
  }

  function changeQty(index, delta) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart[index].quantity += delta;

    if (cart[index].quantity <= 0) {
      cart.splice(index, 1);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    renderCartItems();
  }

  document.addEventListener('DOMContentLoaded', updateCartCount);
  
  function showCheckout() {
  new bootstrap.Modal(document.getElementById('checkoutModal')).show();
}

</script>



<script>
document.getElementById("checkoutForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const address = document.getElementById("deliveryAddress").value.trim();
  const paymentMethod = document.getElementById("paymentMethod").value;
  const deliveryFee = parseFloat(document.getElementById("deliveryFee").value);
  const cart = JSON.parse(localStorage.getItem("cart")) || [];

  if (!address || cart.length === 0) {
    alert("Please complete all fields and make sure cart is not empty.");
    return;
  }

  // ✅ Dynamically extract shop ID from cart
  const uniqueShops = [...new Set(cart.map(item => item.shop_id))];
  if (uniqueShops.length > 1) {
    alert("You can only checkout products from one shop at a time.");
    return;
  }
  const shopId = uniqueShops[0]; // ✅ This is now reliable

  let subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  let fullAmount = subtotal + deliveryFee;
  let payAmount = paymentMethod === 'half' ? fullAmount * 0.5 : fullAmount;

  const reference = 'INV' + Date.now(); // Generate reference

  fetch("process_checkout.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      reference,
      cart,
      address,
      paymentMethod,
      deliveryFee,
      payAmount,
      fullAmount,
      status: "pending",
      shop_id: shopId // ✅ now correctly derived and passed
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      localStorage.removeItem("cart");
      window.location.href = "invoice.php?ref=" + reference;
    } else {
      alert("Error: " + data.message);
    }
  });
});


</script>

<!-- ========= JS Files =========  --> <!-- Bootstrap -->

<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Splide -->
<script src="assets/js/plugins/splide/splide.min.js"></script>
<!-- Base Js File -->
<script src="assets/js/base.js"></script>

</body>
</html>