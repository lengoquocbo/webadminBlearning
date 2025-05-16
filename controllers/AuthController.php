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
            // Lấy thông tin user sau khi đăng nhập
            $user = $this->auth->getUser(); // Hàm này phải trả về thông tin user, bao gồm 'role'
            if (isset($user['role']) && strtoupper($user['role']) === 'ADMIN') {
                header('Location: /WebAdmin_Blearning/views/dashboard/dashboard.php');
                exit;
            } else {
                echo "<script>alert('Chỉ tài khoản ADMIN mới được đăng nhập!');window.location.href='/WebAdmin_Blearning/views/auth/login.php;</script>";
                $this->auth->logout();
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }
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
        $this->auth->logout();
        
    }
}
