<?php
// test_other_endpoints.php
require_once '../config.php';
require_once '../API/ApiClient.php';
require_once '../API/Auth.php';

// Hiển thị thông báo lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Khởi tạo session nếu chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = $_SESSION['admin_token'] ?? null;
$apiClient = new ApiClient(API_BASE_URL, $token);

// Danh sách các endpoint cần kiểm tra
$endpoints = [
    // Các endpoint người dùng
    'users' => 'GET',
    'users/profile' => 'GET',
    
    // Các endpoint khóa học 
    'courses' => 'GET',
    'courses/{id}' => 'GET',
    
    // Các endpoint khác có thể có
    'categories' => 'GET',
    'students' => 'GET',
    'statistics' => 'GET',
];

echo "<h2>Kiểm tra các endpoint với token</h2>";

// Kiểm tra xem có token không
if (!$token) {
    echo "<div style='color: red; font-weight: bold;'>Chưa đăng nhập! Vui lòng <a href='debug_login.php'>đăng nhập</a> trước khi kiểm tra.</div>";
    exit;
}

echo "<div style='color: green;'>Đã đăng nhập với token: " . substr($token, 0, 20) . "...</div>";
echo "<pre>";

foreach ($endpoints as $endpoint => $method) {
    // Thay thế {id} bằng 1 để kiểm tra
    $testEndpoint = str_replace('{id}', '1', $endpoint);
    
    echo "Kiểm tra endpoint: " . API_BASE_URL . $testEndpoint . " [" . $method . "]\n";
    
    try {
        $response = null;
        if ($method === 'GET') {
            $response = $apiClient->get($testEndpoint);
        } elseif ($method === 'POST') {
            $response = $apiClient->post($testEndpoint, ['test' => true]);
        }
        
        echo "✓ SUCCESS: $testEndpoint trả về response!\n";
        echo "Response data: " . json_encode(array_slice($response, 0, 3)) . "...\n";
    } catch (Exception $e) {
        $message = $e->getMessage();
        
        if (strpos($message, "HTTP Error: 401") !== false) {
            echo "✗ UNAUTHORIZED: $testEndpoint yêu cầu quyền truy cập khác\n";
        } elseif (strpos($message, "HTTP Error: 404") !== false) {
            echo "✗ NOT FOUND: $testEndpoint không tồn tại\n";
        } elseif (strpos($message, "HTTP Error: 403") !== false) {
            echo "✗ FORBIDDEN: $testEndpoint không có quyền truy cập\n";
        } else {
            echo "? ERROR: $testEndpoint - " . $message . "\n";
        }
    }
    
    echo "\n";
}

echo "</pre>";

echo "<p><a href='debug_login.php'>Quay lại trang đăng nhập</a></p>";
?>