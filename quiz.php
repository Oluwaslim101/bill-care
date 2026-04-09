<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

$avatar_url = !empty($user['avatar_url']) ? htmlspecialchars($user['avatar_url']) : 'default-avatar.png';
$balance = number_format($user['balance'], 2);
$available_points = $user['points'];
$used_points = number_format($user['points_used']);

$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>DtheHub Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- ✅ Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
  />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
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

    .nav a.active i,
    .nav a.active span {
      color: green;
      font-weight: bold;
    }

    .clock-icon {
      animation: waggle 1s infinite;
    }

    @keyframes waggle {
      0% {
        transform: rotate(0deg);
      }
      25% {
        transform: rotate(10deg);
      }
      50% {
        transform: rotate(-10deg);
      }
      75% {
        transform: rotate(10deg);
      }
      100% {
        transform: rotate(0deg);
      }
    }

    .list-group-item {
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .correct-flash {
      animation: flash-green 0.6s ease;
    }

    @keyframes flash-green {
      0% {
        background-color: #28a745;
        color: white;
        transform: scale(1.05);
      }
      50% {
        background-color: #218838;
        transform: scale(1.1);
      }
      100% {
        background-color: #28a745;
        transform: scale(1);
      }
    }

    .wrong-shake {
      animation: shake-red 0.6s ease;
    }

    @keyframes shake-red {
      0% {
        transform: translateX(0);
        background-color: #dc3545;
        color: white;
      }
      25% {
        transform: translateX(-5px);
      }
      50% {
        transform: translateX(5px);
      }
      75% {
        transform: translateX(-5px);
      }
      100% {
        transform: translateX(0);
        background-color: #dc3545;
        color: white;
      }
    }
    @keyframes floatToPoints {
  0% {
    opacity: 1;
    transform: translate(0, 0) scale(1);
  }
  100% {
    opacity: 0;
    transform: translate(var(--move-x), var(--move-y)) scale(0.5);
  }
}

.points-bounce {
  animation: bounce 0.5s ease forwards;
}

@keyframes bounce {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.2); }
}

.floating-points {
  position: absolute;
  color: #28a745;
  font-weight: bold;
  font-size: 1.2rem;
  pointer-events: none;
  animation: floatToPoints 1s forwards;
  z-index: 2000;
}

.flying-star {
  transition: transform 0.6s ease-out;
  will-change: transform, top, left, opacity;
  pointer-events: none;
}
.points-bounce {
  animation: bounceScale 0.5s ease-in-out;
}
@keyframes bounceScale {
  0% { transform: scale(1); }
  40% { transform: scale(1.3); }
  100% { transform: scale(1); }
}


  </style>
</head>
<body>
  <!-- App Header -->
  <div class="appHeader bg-primary text-light">
    <div class="left">
      <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
        <ion-icon name="menu-outline"></ion-icon>
      </a>
    </div>
    <div class="right d-flex align-items-center">
      <div class="form-check form-switch ms-2">
        <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch" />
      </div>
      <a href="#" id="notificationsButton" class="headerButton position-relative">
        <ion-icon class="icon" name="notifications-outline"></ion-icon>
        <span
          class="badge badge-danger position-absolute top-6 start-100 translate-middle p-1 rounded-circle"
          id="notification-count"
          ><?= $unread_count ?></span
        >
      </a>
    </div>
  </div>

  <!-- App Content -->
  <div id="appCapsule">
    <div class="section py-2 px-0">
      <div class="row g-2">
        <div class="col-3">
          <div class="stat-box bg-success text-white p-1 rounded small text-center">
            <div class="title mb-1">Available</div>
            <h5>⭐ <?= $available_points ?></h5>
          </div>
        </div>
        <div class="col-3">
          <div class="stat-box bg-danger text-white p-1 rounded small text-center">
            <div class="title mb-1">Used</div>
            <h5>⭐ <?= $used_points ?></h5>
          </div>
        </div>
        <div class="col-3">
          <div class="stat-box bg-warning text-dark p-1 rounded small text-center">
            <div class="title mb-1">Time Left</div>
            <h5>
              <i class="fas fa-clock clock-icon me-1"></i
              ><span id="countdown">10</span>s
            </h5>
          </div>
        </div>
        <!-- New Answered Count Card -->
<div class="col-3">
  <div class="stat-box bg-info text-white p-1 rounded small text-center">
    <div class="title mb-1">Answered</div>
    <h5 id="answered-count">0/15</h5>
  </div>
</div>

        <!-- New Fail Count Card -->
        <div class="col-3">
          <div class="stat-box bg-danger text-white p-1 rounded small text-center">
            <div class="title mb-1">Total Fails</div>
            <h5 id="fail-count">0/3</h5>
          </div>
        </div>
      </div>
    </div>

    <!-- Quiz Section -->
    <div class="page-content" id="quiz-container">
      <div class="card p-3 mb-4" id="question-card">
        <h5 class="mb-3" id="question-text">Loading...</h5>
        <div class="list-group quiz-options" id="options-container"></div>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer footer-sticky text-center">
      <div class="row p-3">
        <div class="col">
          <button class="btn btn-primary btn-block rounded-pill" id="next-btn">
            Next
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Navigation -->
  <nav class="nav">
    <a href="index.php" class="active"
      ><i class="fas fa-home"></i><span>Home</span></a
    >
    <a href="rewards.php"
      ><i class="fas fa-gift"></i><span>Rewards</span></a
    >
    <a href="#"
      ><i class="fas fa-receipt"></i><span>Quick</span></a
    >
    <a href="transactions.php"
      ><i class="fas fa-receipt"></i><span>Transactions</span></a
    >
    <a href="profile.php"
      ><i class="fas fa-user"></i><span>Profile</span></a
    >
  </nav>

 <script>
let timer;
let timeLeft = 10;
let inactivityTimer;
let inactivityTime = 10;
let quizPaused = false;
let currentCorrectOption = null;
let answeredCount = 0;

// Reset inactivity on interaction
function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  if (quizPaused) return;

  inactivityTimer = setTimeout(() => {
    pauseQuiz();
  }, inactivityTime * 1000);
}

// Pause quiz UI
function pauseQuiz() {
  quizPaused = true;
  clearInterval(timer);
  const overlay = $(`
    <div id="quiz-pause-overlay" style="
      position: fixed; top:0; left:0; width:100%; height:100%;
      background: rgba(0,0,0,0.7); display:flex; flex-direction: column; justify-content:center; align-items:center; z-index:9999;
      color: white; font-size: 1.5rem;">
      <div class="spinner-border text-light mb-3"></div>
      <div>Paused due to inactivity</div>
      <button id="resume-btn" class="btn btn-primary mt-3">Resume Quiz</button>
    </div>`);
  $('body').append(overlay);

  $('#resume-btn').on('click', () => {
    $('#quiz-pause-overlay').remove();
    quizPaused = false;
    startCountdown(currentCorrectOption);
    resetInactivityTimer();
  });
}

function updateFailCountDisplay(failCount) {
  $('#fail-count').text(`${failCount}/3`);
}

function showLockoutMessage(remainingSec, message) {
  const minutes = Math.floor(remainingSec / 60);
  const seconds = remainingSec % 60;
  $('#question-text').text(`⏳ ${message} Please wait ${minutes}m ${seconds}s.`);
  $('#options-container').html('');
  $('#next-btn').hide();
  $('#countdown').text('0');
  clearInterval(timer);
  clearTimeout(inactivityTimer);
}

function startCountdown(correctOpt) {
  currentCorrectOption = correctOpt;
  clearInterval(timer);
  timeLeft = 10;
  $('#countdown').text(timeLeft);

  timer = setInterval(() => {
    if (quizPaused) return;
    timeLeft--;
    $('#countdown').text(timeLeft);

    if (timeLeft <= 0) {
      clearInterval(timer);
      $('.quiz-options .list-group-item').off('click');
      $(`[data-opt="${correctOpt.toLowerCase()}"]`).addClass('correct-flash bg-success text-white');
      answeredCount++;
      $('#answered-count').text(`${answeredCount}/15`);
      setTimeout(loadQuestionInternal, 2000);
    }
  }, 1000);
}

function checkLockoutAndLoadQuestion() {
  $.getJSON('quiz_status.php', function (status) {
    if (!status.success) {
      $('#question-text').text('Error checking quiz status.');
      $('#options-container').html('');
      $('#next-btn').hide();
      $('#countdown').text('0');
      updateFailCountDisplay(0);
      return;
    }

    updateFailCountDisplay(status.fail_count || 0);

    if (status.locked_out) {
      showLockoutMessage(status.lockout_remaining, status.lockout_message || 'You are locked out.');
      return;
    }

    if (status.quiz_finished) {
      showLockoutMessage(status.quiz_lock_remaining, 'You have completed the quiz and are locked out.');
      return;
    }

    $('#next-btn').show();
    loadQuestionInternal();
  });
}

function incrementFailAndCheckLockout(callback) {
  $.post('quiz_status.php', { action: 'increment_fail' }, function (status) {
    if (!status.success) {
      alert('Error updating fail count.');
      return;
    }

    updateFailCountDisplay(status.fail_count || 0);

    if (status.locked_out) {
      showLockoutMessage(status.lockout_remaining, status.lockout_message || 'You are locked out.');
    }

    if (callback) callback(status.locked_out);
  }, 'json');
}

function resetFailCount() {
  $.post('quiz_status.php', { action: 'reset_fail' }, function () {
    updateFailCountDisplay(0);
  }, 'json');
}

function loadQuestionInternal() {
  if (quizPaused) return;
  if (answeredCount >= 15) {
    showQuizSummary();
    return;
  }

  $.getJSON('get_question.php', function (data) {
    if (!data.success) {
      $('#question-text').text("🎉 You've completed the quiz!");
      $('#options-container').html('');
      $('#next-btn').hide();
      $('#countdown').text('0');
      resetFailCount();
      return;
    }

    const q = data.question;
    $('#question-text').html(q.question);
    $('#options-container').html('');
    const options = ['a', 'b', 'c', 'd'];

    options.forEach((opt) => {
      const value = q[`option_${opt}`];
      const btn = `<button class="list-group-item list-group-item-action quiz-option" data-opt="${opt}" data-correct="${q.correct_option}">${opt.toUpperCase()}. ${value}</button>`;
      $('#options-container').append(btn);
    });

    $('.quiz-options .list-group-item').removeClass('correct-flash wrong-shake bg-success bg-danger text-white');

    $('.quiz-option').on('click', function () {
      if (quizPaused) return;

      const selected = $(this).attr('data-opt').toLowerCase();
      const correct = $(this).attr('data-correct').toLowerCase();
      const points = parseInt(q.points) || 1;

      $('.quiz-option').off('click').removeClass('bg-success bg-danger text-white');

      answeredCount++;
      $('#answered-count').text(`${answeredCount}/15`);

      if (selected === correct) {
        $(this).addClass('correct-flash bg-success text-white');
        resetFailCount();
        clearInterval(timer);
        animatePointsGain($(this), points);
        setTimeout(loadQuestionInternal, 2000);
      } else {
        $(this).addClass('wrong-shake bg-danger text-white');
        $(`[data-opt="${correct}"]`).addClass('correct-flash bg-success text-white');
        clearInterval(timer);
        incrementFailAndCheckLockout((lockedOut) => {
          if (!lockedOut) {
            setTimeout(loadQuestionInternal, 2000);
          }
        });
      }
    });

    startCountdown(q.correct_option);
    resetInactivityTimer();
  });
}

function animatePointsGain(clickedElem, pointsToAdd = 1) {
  const pointsCard = $('.stat-box.bg-success');
  const startPos = clickedElem.offset();
  const endPos = pointsCard.offset();

  for (let i = 0; i < Math.min(pointsToAdd, 8); i++) {
    const star = $(`<div class="flying-star">⭐</div>`);
    $('body').append(star);

    star.css({
      top: startPos.top + clickedElem.height() / 2,
      left: startPos.left + clickedElem.width() / 2,
      position: 'absolute',
      fontSize: '24px',
      color: '#ffc107',
      textShadow: '0 0 5px white, 0 0 10px #ffd700',
      opacity: 0.8,
      zIndex: 9999,
    });

    star.animate({
      top: endPos.top + 10 + Math.random() * 20,
      left: endPos.left + 10 + Math.random() * 20,
      opacity: 0.2,
      fontSize: '12px',
    }, 1000 + Math.random() * 400, 'swing', function () {
      star.remove();
    });
  }

  pointsCard.addClass('points-bounce');
  setTimeout(() => pointsCard.removeClass('points-bounce'), 600);

  const currentPoints = parseInt(pointsCard.find('h5').text().replace(/[^0-9]/g, '')) || 0;
  const newPoints = currentPoints + pointsToAdd;
  pointsCard.find('h5').text(`⭐ ${newPoints}`);
}

function showQuizSummary() {
  $.getJSON('quiz_summary.php', function (summary) {
    if (!summary.success) {
      $('#question-text').text('Error fetching summary.');
      return;
    }

    const html = `
      <div class="card p-3 mb-4 text-center">
        <h4 class="mb-3">🎯 Quiz Summary</h4>
        <p><strong>Answered:</strong> ${summary.answered}</p>
        <p><strong>Correct:</strong> ${summary.correct}</p>
        <p><strong>Failed:</strong> ${summary.failed}</p>
        <p><strong>Total Points Earned:</strong> ⭐ ${summary.earned_points}</p>
        ${summary.can_claim
          ? '<button id="claim-btn" class="btn btn-success mt-3">Claim Points</button>'
          : '<div class="alert alert-warning mt-3">You are currently locked out. Points will be stored and claimable after a successful 15-question session.</div>'}
      </div>`;

    $('#question-text').html('');
    $('#options-container').html(html);
    $('#countdown').text('0');

    $('#claim-btn').on('click', function () {
      $.post('claim_points.php', {}, function (res) {
        if (res.success) {
          alert('✅ Points claimed successfully!');
          location.reload();
        } else {
          alert('❌ ' + (res.message || 'Unable to claim points.'));
        }
      }, 'json');
    });
  });
}

// Next button
$('#next-btn').on('click', function () {
  if (!quizPaused) {
    clearInterval(timer);
    checkLockoutAndLoadQuestion();
  }
});

// Inactivity tracking
$(document).on('click keypress mousemove touchstart', () => {
  resetInactivityTimer();
});

// Start quiz
$(document).ready(() => {
  $('#answered-count').text(`${answeredCount}/15`);
  checkLockoutAndLoadQuestion();
});
</script>




    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Apex Charts -->
    <script src="assets/js/plugins/apexcharts/apexcharts.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>


</body>
</html>


