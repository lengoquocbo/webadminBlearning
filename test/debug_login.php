<?php

require_once '../config.php';
require_once '../API/ApiClient.php';
require_once '../API/Auth.php';

// Hiển thị thông báo lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Tạo form đăng nhập đơn giản
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        // Log thông tin request
        echo "Attempting to login with email: $email<br>";
        echo "API URL: " . API_BASE_URL . "<br>";
        
        try {
            $apiClient = new ApiClient(API_BASE_URL);
            $auth = new Auth($apiClient);
            
            // Thêm debug thông tin raw của request
            echo "Sending request to: " . API_BASE_URL . "users/login<br>";
            echo "Request data: " . json_encode(['email' => $email, 'password' => $password]) . "<br><br>";
            
            $result = $auth->login($email, $password);
            
            if ($result) {
                echo "<div style='color: green; font-weight: bold;'>Login successful!</div>";
                echo "User info: <pre>";
                print_r($_SESSION['admin_user']);
                echo "</pre>";
                echo "Token: " . substr($_SESSION['admin_token'], 0, 20) . "...<br>";
            } else {
                echo "<div style='color: red; font-weight: bold;'>Login failed!</div>";
            }
        } catch (Exception $e) {
            echo "<div style='color: red; font-weight: bold;'>Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type=text], input[type=password] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .debug-section { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <h2>Debug Login Form</h2>
    
    <form method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit">Login</button>
    </form>
    
    <div class="debug-section">
        <h3>Session Information:</h3>
        <pre><?php print_r($_SESSION); ?></pre>
        
        <h3>Server Information:</h3>
        <pre>
API_BASE_URL: <?php echo API_BASE_URL; ?>
        </pre>
    </div>
</body>
</html>