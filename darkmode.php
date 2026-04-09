<?php
session_start();

if (isset($_POST['dark_mode'])) {
    $_SESSION['dark_mode'] = $_POST['dark_mode'];
    echo "Dark mode preference saved.";
}
?>