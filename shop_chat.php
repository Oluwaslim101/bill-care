<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$shopId = intval($_GET['shop_id'] ?? 0);

if (!$shopId) {
    die("Invalid shop.");
}

$shop = $sql->prepare("SELECT shop_name, logo FROM shop_owners WHERE id = ?");
$shop->execute([$shopId]);
$shopData = $shop->fetch(PDO::FETCH_ASSOC);
$shopName = $shopData['shop_name'] ?? 'Shop';
$shopLogo = $shopData['logo'] ?? 'default_logo.png';
?>
<?php include 'header.php'; ?>  

    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }

    .chat-modal {
        border: 1px solid #ccc;
        border-radius: 8px;
        max-width: 800px;
        margin: auto;
        background-color: #f4f4f4;
        display: flex;
        flex-direction: column;
        height: 80vh;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        position: fixed;
        top: 10vh;
        left: 0;
        right: 0;
        z-index: 1000;
        overflow: hidden;
    }

    .chat-header {
        background-color: #008069;
        color: white;
        padding: 10px 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-header img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
    }

    .chat-header span {
        font-weight: bold;
        font-size: 16px;
        flex-grow: 1;
    }

    .chat-header button {
        background: transparent;
        border: none;
        font-size: 18px;
        color: white;
        cursor: pointer;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        background: #e5ddd5;
        display: flex;
        flex-direction: column;
    }

    .chat-footer {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ccc;
        background-color: #f0f0f0;
    }

    .chat-footer textarea {
        flex: 1;
        border-radius: 20px;
        padding: 8px 12px;
        resize: none;
        border: 1px solid #ccc;
    }

    .chat-footer button {
        border-radius: 20px;
        margin-left: 8px;
        padding: 8px 16px;
        background-color: #008069;
        border: none;
        color: white;
        cursor: pointer;
    }

    .chat-bubble {
        max-width: 70%;
        padding: 10px 12px;
        margin: 6px 0;
        border-radius: 10px;
        position: relative;
        font-size: 14px;
        word-wrap: break-word;
        line-height: 1.4;
    }

    .chat-bubble.shop_owner {
        background-color: #dcf8c6;
        align-self: flex-end;
        border-bottom-right-radius: 0;
    }

    .chat-bubble.customer {
        background-color: white;
        align-self: flex-start;
        border-bottom-left-radius: 0;
    }

    .chat-bubble small {
        display: block;
        text-align: right;
        font-size: 11px;
        color: gray;
        margin-top: 4px;
    }

    .chat-bubble .tick {
        margin-left: 4px;
        font-size: 12px;
    }

    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }
    </style>
</head>
<body>

<div class="chat-modal" id="chatModal">
    <div class="chat-header">
        <img src="uploads/<?= htmlspecialchars($shopLogo) ?>" alt="Logo">
        <span>Chat with <?= htmlspecialchars($shopName) ?></span>
        <button onclick="closeChat()">✖</button>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="text-center mt-4">Loading chats...</div>
    </div>
    <div class="chat-footer">
        <textarea id="messageInput" rows="1" placeholder="Type a message..."></textarea>
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const shopId = <?= $shopId ?>;

function formatTime(datetime) {
    const date = new Date(datetime);
    let h = date.getHours();
    let m = date.getMinutes();
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12;
    h = h || 12;
    m = m < 10 ? '0' + m : m;
    return `${h}:${m} ${ampm}`;
}

function scrollChatToBottom() {
    const el = document.getElementById('chatBody');
    el.scrollTop = el.scrollHeight;
}

function loadChats() {
    $.get('fetch_shop_chat.php', { shop_id: shopId }, function(res) {
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
                    <div class="chat-bubble ${bubbleClass}">
                        ${chat.message}
                        <small>${time} ${ticks}</small>
                    </div>`;
            });

            $('#chatBody').html(html);
            scrollChatToBottom();

            // Mark as read
            $.post('mark_as_read.php', { shop_id: shopId });
        } else {
            $('#chatBody').html('<div class="text-center text-muted">Failed to load chats.</div>');
        }
    }, 'json');
}

function sendMessage() {
    const msg = $('#messageInput').val().trim();
    if (!msg) return;

    $.post('send_shop_chat.php', {
        shop_id: shopId,
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

function closeChat() {
    $('#chatModal').hide();
}

$('#messageInput').keypress(function(e) {
    if (e.which === 13 && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

loadChats();
setInterval(loadChats, 3000);
</script>
</body>
</html>