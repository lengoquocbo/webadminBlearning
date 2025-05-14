<?php
// check_ktor_routes.php
require_once '../config.php';
require_once '../API/ApiClient.php';
require_once '../API/Auth.php';

// Hiển thị thông báo lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Danh sách các endpoint có thể có để kiểm tra
$endpoints = [
    'users/login',
    'api/users/login',
    'auth/login',
    'login',
    'v1/users/login',
    'api/v1/users/login',
    'auth',
    'health',
    // Thêm các biến thể khác của endpoint đăng nhập
];

$apiClient = new ApiClient(API_BASE_URL);

echo "<h2>Kiểm tra các endpoint có thể có</h2>";
echo "<pre>";

foreach ($endpoints as $endpoint) {
    echo "Kiểm tra endpoint: " . API_BASE_URL . $endpoint . "\n";
    
    try {
        // Sử dụng phương thức GET thay vì POST để kiểm tra các đường dẫn
        // (Ngay cả khi endpoint yêu cầu POST, việc này sẽ cho ta biết nó có tồn tại không)
        $apiClient->get($endpoint);
        echo "✓ FOUND: $endpoint trả về response!\n";
    } catch (Exception $e) {
        $message = $e->getMessage();
        
        // Phân tích thông báo lỗi
        // Lỗi 404 có nghĩa là đường dẫn không tồn tại
        // Lỗi 405 có nghĩa là đường dẫn tồn tại nhưng phương thức không được phép (đây là dấu hiệu tốt!)
        if (strpos($message, "HTTP Error: 405") !== false) {
            echo "✓ FOUND: $endpoint tồn tại (phương thức không được phép - có thể cần POST)!\n";
        } elseif (strpos($message, "HTTP Error: 401") !== false) {
            echo "✓ FOUND: $endpoint tồn tại (yêu cầu xác thực)!\n";
        } elseif (strpos($message, "HTTP Error: 404") !== false) {
            echo "✗ NOT FOUND: $endpoint không tồn tại\n";
        } else {
            echo "? ERROR: $endpoint - " . $message . "\n";
        }
    }
    
    echo "\n";
}

echo "</pre>";
?>