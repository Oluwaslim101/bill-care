<?php
session_start();
include('db.php'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Fetch the user's spin count and last spin date
$query = "SELECT spin_count, last_spin_date FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Check if the user has spun twice today
    $currentDate = date('Y-m-d');
    if ($user['last_spin_date'] === $currentDate && $user['spin_count'] >= 2) {
        echo json_encode(['error' => 'You have reached the daily limit of spins. Please try again tomorrow.']);
        exit();
    }
}

// Handle the POST request from the frontend after the wheel spin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the points sent by the frontend
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['points'])) {
        $points = $data['points'];

        // Update the user's points in the database
        $query = "UPDATE users SET points = points + ? WHERE id = ?";
        $stmt = $sql->prepare($query);
        $stmt->execute([$points, $user_id]);

        // Check if today is the same as the last spin date
        if ($user['last_spin_date'] === $currentDate) {
            // Update the spin count if the user has spun today
            $query = "UPDATE users SET spin_count = spin_count + 1 WHERE id = ?";
            $stmt = $sql->prepare($query);
            $stmt->execute([$user_id]);
        } else {
            // Reset spin count if it's a new day
            $query = "UPDATE users SET spin_count = 1, last_spin_date = ? WHERE id = ?";
            $stmt = $sql->prepare($query);
            $stmt->execute([$currentDate, $user_id]);
        }

        // Fetch the updated points to return to the frontend
        $query = "SELECT points FROM users WHERE id = ?";
        $stmt = $sql->prepare($query);
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode(['success' => true, 'points' => $user['points']]);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid data']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin the Wheel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #333;
        }

        .container {
            position: relative;
            width: 400px;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinBtn {
            position: absolute;
            width: 80px;
            height: 80px;
            background: #fff;
            border-radius: 50%;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            text-transform: uppercase;
            font-weight: 600;
            color: #333;
            letter-spacing: .1em;
            border: 4px solid rgba(0, 0, 0, 0.75);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.3s ease;
        }

        .spinBtn:hover {
            background-color: #f0f0f0;
        }

        .wheel {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #333;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 0 0 5px #333, 0 0 0 15px #fff, 0 0 0 18px #111;
            transition: transform 5s ease-in-out;
        }

        .number {
            position: absolute;
            width: 50%;
            height: 50%;
            background: var(--clr);
            transform-origin: bottom right;
            transform: rotate(calc(45deg * var(--i)));
            clip-path: polygon(0 0, 56% 0, 100% 100%, 0 56%);
            display: flex;
            justify-content: center;
            align-items: center;
            user-select: none;
        }

        .number span {
            position: relative;
            transform: rotate(45deg);
            font-size: 2em;
            font-weight: 700;
            color: #fff;
            text-shadow: 3px 5px 2px rgba(0, 0, 0, 0.15);
        }

        .star {
            position: absolute;
            font-size: 2em;
            color: #FFD700;
            display: none;
            z-index: 20;
            animation: rise 2s ease-out forwards;
        }

        @keyframes rise {
            from {
                bottom: 0;
                opacity: 1;
            }
            to {
                bottom: 80px;
                opacity: 0;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="spinBtn">Spin</div>
    <div class="wheel">
        <div class="number" style="--i:1;--clr:#db7093"><span>100</span></div>
        <div class="number" style="--i:2;--clr:#20b2aa"><span>1</span></div>
        <div class="number" style="--i:3;--clr:#d63e92"><span>50</span></div>
        <div class="number" style="--i:4;--clr:#daa520"><span>0</span></div>
        <div class="number" style="--i:5;--clr:#ff34f0"><span>1000</span></div>
        <div class="number" style="--i:6;--clr:#ff7f50"><span>10</span></div>
        <div class="number" style="--i:7;--clr:#3cb371"><span>5</span></div>
        <div class="number" style="--i:8;--clr:#4169e1"><span>20</span></div>
    </div>
    <div class="star">⭐</div>
</div>

<script>
let wheel = document.querySelector('.wheel');
let spinBtn = document.querySelector('.spinBtn');
let star = document.querySelector('.star');
let spinning = false;

spinBtn.onclick = function() {
    if (spinning) return;

    spinning = true;
    let randomRotation = Math.floor(Math.random() * 3600) + 3600;
    let rotationDuration = Math.floor(Math.random() * 5) + 5;

    wheel.style.transition = `transform ${rotationDuration}s cubic-bezier(0.34, 1.56, 0.64, 1)`;
    wheel.style.transform = `rotate(${randomRotation}deg)`;

    setTimeout(function() {
        spinning = false;
        let handAngle = randomRotation % 90;
        
        let points = [100, 1, 50, 0, 1000, 10, 5, 20];
        let sectionIndex = Math.floor(handAngle / 45);
        let earnedPoints = points[sectionIndex];

        star.textContent = `⭐ ${earnedPoints} points`;
        star.style.display = 'block';
        setTimeout(function() {
            star.style.display = 'none';
        }, 2000);

        fetch('', {
            method: 'POST',
            body: JSON.stringify({ points: earnedPoints }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("You won: " + earnedPoints + " points! Your total points: " + data.points);
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }, rotationDuration * 1000);
};
</script>

</body>
</html>