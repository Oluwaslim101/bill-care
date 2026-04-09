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
$wallet_id = $user['wallet_id'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';
$balance = number_format($user['balance'], 2);


// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">

    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        
         /* Container */
        .container {
            max-width: 410px;
            width: 100%;
            padding-top: 50px; /* Prevents header overlap */
            padding-bottom: 70px; /* Prevents navbar overlap */
        }


  /* Fixed Header */
        .header {
            position: fixed;
            top: 2px;
            width: 100%;
            max-width: 390px;
            background: #f8f9fc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 70px;
            margin: -11px;
        }

        .header img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        


 .hotel-card {
            background: #fff;
            padding: 9px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 7px;
            margin: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hotel-info {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: -2px;
        }
        .hotel-info img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
        }
        .hotel-details h3 {
            font-size: 18px;
            margin: 9px;
        }
        
        .hotel-details p {
            font-size: 12px;
            margin: -3px;
        }
        .hotel-actions {
            display: flex;
            gap: 4px;
        }
        .hotel-actions a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .call-btn {
            background: #28a745;
            color: white;
        font-size: 15px;
        }
        .chat-btn {
            background: #007bff;
            color: white;
            font-size: 15px;
        }
       

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
   
/* Slider Container */
.advert-slider {
    position: relative;
    width: 100%;
    max-width: 410px; /* Adjust based on your layout */
    height: 175px; /* Fixed size for all images */
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

/* Slide Images */
.slides {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 0.9s ease-in-out;
}

.slides img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures all images fit properly */
    flex-shrink: 0;
}

/* Navigation Buttons */
.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 50%;
    font-size: 1px;
}

.prev { left: 5px; }
.next { right: 10px; }

/* Pagination Dots */
.dots {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 5px;
}

.dots span {
    width: 8px;
    height: 8px;
    background: gray;
    border-radius: 50%;
    cursor: pointer;
}

.dots .active {
    background: white;
}
</style>
  
    
</head>
<body>
<!-- Header -->
<header class="header">
    <div class="user-info">
        <img src="<?= $avatar_url ?>" alt="User Avatar">
        <div>
        <h3>Hotel Booking Services</h3>
          
        </div>
    </div>
    <div style="position: relative;">
        <i class="fas fa-bell" style="font-size: 25px; color: black;"></i>
        <?php if ($unread_count > 0): ?>
            <span style="
                position: absolute;
                top: -5px;
                right: 1px;
                background: red;
                color: white;
                font-size: 8px;
                font-weight: bold;
                padding: 2px 6px;
                border-radius: 50%;
            ">
                <?= $unread_count ?>
            </span>
        <?php endif; ?>
    </div>
</header>

<div class="container">
    <!-- Hotel 1 -->
    <div class="hotel-card">
        <div class="hotel-info">
            <img src="https://th.bing.com/th/id/OIP.18xBtYVqfWCTBwJh51MP0AHaHa?rs=1&pid=ImgDetMain" alt="Hotel 1">
            <div class="hotel-details">
                <h3> Rebatel Suites & Hotels</h3>
                <p>  #6 Chief Egba Strt. Azikoro Yen. BYS Nigeria</p>
            </div>
        </div>
        <div class="hotel-actions">
            <a href="tel:2348148622359" class="call-btn">📞</a>
            <a href="https://swiftaffiliates.cloud/hotels.php" class="chat-btn">View</a>
        </div>
    </div>

    <!-- Hotel 2 -->
    <div class="hotel-card">
        <div class="hotel-info">
            <img src="https://th.bing.com/th/id/OIP.aKArGfc7u-9akinyzZi_BwHaF7?pid=ImgDet&w=191&h=152&c=7" alt="Hotel 2">
            <div class="hotel-details">
                <h3> Ocean View Suites</h3>
                <p> 45 Victoria Island, Lagos, Nigeria</p>
            </div>
        </div>
        <div class="hotel-actions">
            <a href="tel:2348148622359" class="call-btn">📞</a>
             <a href="#" class="chat-btn">View</a>
        </div>
    </div>
   
       <!-- Hotel 3 -->
    <div class="hotel-card">
        <div class="hotel-info">
            <img src="https://th.bing.com/th/id/OIP.9Gzc8EecHdlcYVeRTLXqOgHaFj?w=231&h=180&c=7&r=0&o=5&pid=1.7" alt="Hotel 1">
            <div class="hotel-details">
                <h3> Marvis Suite</h3>
                <p> 196 Brigadier Central Rd, Rivers, Nigeria</p>
            </div>
        </div>
        <div class="hotel-actions">
             <a href="tel:2348148622359" class="call-btn">📞</a>
             <a href="#" class="chat-btn">View</a>
        </div>
    </div>

    <!-- Hotel 4 -->
    <div class="hotel-card">
        <div class="hotel-info">
            <img src="https://th.bing.com/th/id/OIP.XLlJsHjTQNf1Z_S91AAbiQHaHa?pid=ImgDet&w=191&h=191&c=7" alt="Hotel 2">
            <div class="hotel-details">
                <h3> Afri-Home Apartments</h3>
                <p> 84 Pipeline Ave, Abuja Nigeria</p>
            </div>
        </div>
        <div class="hotel-actions">
            <a href="tel:2348148622359" class="call-btn">📞</a>
            <a href="#" class="chat-btn">View</a>
        </div>
    </div>
    <br>
    <br>
    
 

<div class="advert-slider">
    <div class="slides">
        <img src="ads_sliders/ads_7.png" alt="Ad 1">
        <img src="ads_sliders/ads_8.png" alt="Ad 2">
        <img src="ads_sliders/ads_9.png" alt="Ad 3">
         <img src="ads_sliders/ads_10.png" alt="Ad 4">
        <img src="ads_sliders/ads_11.png" alt="Ad 5">
        <img src="ads_sliders/ads_1.png" alt="Ad 6">
    </div>
    
    <!-- Navigation Buttons -->
    <button class="prev"></button>
    <button class="next"></button>

    <!-- Dots for Pagination -->
    <div class="dots"></div>
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
    <a href="transactions.php">
        <i class="fas fa-receipt"></i>
        <span>Transactions</span>
    </a>
    <a href="profile.php">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
</nav>


<script>

document.addEventListener("DOMContentLoaded", function () {
    let slideIndex = 0;
    const slides = document.querySelector(".slides");
    const images = document.querySelectorAll(".slides img");
    const dotsContainer = document.querySelector(".dots");

    // Create Dots Dynamically
    images.forEach((_, index) => {
        let dot = document.createElement("span");
        dot.addEventListener("click", () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll(".dots span");
    dots[slideIndex].classList.add("active");

    // Function to Change Slide
    function goToSlide(index) {
        slideIndex = index;
        slides.style.transform = `translateX(-${index * 100}%)`;
        updateDots();
    }

    function updateDots() {
        dots.forEach(dot => dot.classList.remove("active"));
        dots[slideIndex].classList.add("active");
    }

    function nextSlide() {
        slideIndex = (slideIndex + 1) % images.length;
        goToSlide(slideIndex);
    }

    function prevSlide() {
        slideIndex = (slideIndex - 1 + images.length) % images.length;
        goToSlide(slideIndex);
    }

    document.querySelector(".next").addEventListener("click", nextSlide);
    document.querySelector(".prev").addEventListener("click", prevSlide);

    // Auto-Slide every 3 seconds
    setInterval(nextSlide, 7000);
});


    
   

</script>
</body>
</html>
