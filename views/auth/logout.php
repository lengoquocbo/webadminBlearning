<?php
session_start();

if (isset($_SESSION['token'])) {
    unset($_SESSION['token']);
}
if (isset($_COOKIE['token'])) {
    setcookie('token', '', time() - 3600, '/');
}
session_destroy();

// Chuyển hướng về trang đăng nhập qua router
header('Location: /WebAdmin_Blearning/views/auth/login.php');
exit;