<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth
{
    private $apiClient;

    public function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }

    // Hàm đăng nhập
    public function login($email, $password)
    {
        try {
            $response = $this->apiClient->post('users/login', [
                'email' => $email,
                'password' => $password
            ]);

            // Debug: Log response để kiểm tra
            error_log("Login response: " . json_encode($response));

            if (isset($response['token'])) {
                $_SESSION['admin_token'] = $response['token'];

                // Lưu thông tin người dùng từ response
                $_SESSION['admin_user'] = $response['user'] ?? $this->extractUserFromToken($response['token']);

                // Thiết lập token cho API client
                $this->apiClient->setToken($response['token']);
                return true;
            }

            return false;
        } catch (Exception $e) {
            error_log("Login failed: " . $e->getMessage());
            return false;
        }
    }

    // Hàm giải mã JWT token để lấy thông tin người dùng
    private function extractUserFromToken($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return [];
        }

        $payload = $parts[1];
        $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $payload));
        $data = json_decode($payload, true);

        if (!$data) {
            return [];
        }

        return [
            'id' => $data['userID'] ?? null,
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'role' => $data['role'] ?? null,
        ];
    }

    // Hàm đăng xuất
    public function logout()
    {
        // Xóa token khỏi API client
        $this->apiClient->setToken(null);

        // Xóa session và cookie
        unset($_SESSION['admin_token'], $_SESSION['admin_user']);
        setcookie('token', '', time() - 3600, '/'); // Xóa cookie

        // Hủy toàn bộ session
        session_destroy();

        // Chuyển hướng về trang đăng nhập (dùng đường dẫn tương đối)
        header('Location: index.php?path=auth/login.php');
        exit;
    }

    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    public function isLoggedIn()
    {
        return isset($_SESSION['admin_token']) && !empty($_SESSION['admin_token']);
    }

    // Lấy thông tin người dùng hiện tại
    public function getUser()
    {
        return $_SESSION['admin_user'] ?? null;
    }

    // Lấy token hiện tại
    public function getToken()
    {
        return $_SESSION['admin_token'] ?? null;
    }

    // Hàm yêu cầu đăng nhập
    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            error_log("Unauthorized access attempt. Redirecting to login page.");
            header('Location: views/auth/login.php');
            exit;
        }
    }
}
