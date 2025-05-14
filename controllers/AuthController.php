<?php

require_once __DIR__ . '/../API/Auth.php';
require_once __DIR__ . '/../API/ApiClient.php';
require_once __DIR__ . '/../config.php';

class AuthController
{
    private $auth;
    private $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient(API_BASE_URL);
        $this->auth = new Auth($this->apiClient);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Vui lòng nhập email và mật khẩu";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            if ($this->auth->login($email, $password)) {
header('Location: /WebAdmin_Blearning/views/dashboard/dashboard.php');                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng";
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout()
    {
        // Xóa token khỏi session
        if (isset($_SESSION['token'])) {
            unset($_SESSION['token']);
        }

        // Xóa token khỏi cookie
        if (isset($_COOKIE['token'])) {
            setcookie('token', '', time() - 3600, '/'); // Xóa cookie
        }

        // Xóa toàn bộ session
        session_destroy();

        // Chuyển hướng về trang đăng nhập
        header('Location: views/auth/login.php');
        exit;
    }
}
