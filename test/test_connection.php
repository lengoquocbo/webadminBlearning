<?php
// test_connection.php
require_once '../config.php';
require_once '../API/ApiClient.php';
require_once '../API/Auth.php';
// Hiển thị thông báo lỗi để dễ debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Tạo ApiClient
$apiClient = new ApiClient(API_BASE_URL);

// Log thông tin 
echo "Attempting to connect to: " . API_BASE_URL . "<br>";

// Thử gọi một endpoint đơn giản (ví dụ: health check hoặc endpoint tương tự)
try {
    // Thử gọi một endpoint cơ bản (thay đổi 'health' thành endpoint thực tế của bạn)
    $response = $apiClient->get('health');
    echo "Connection successful!<br>";
    echo "Response: <pre>";
    print_r($response);
    echo "</pre>";
} catch (Exception $e) {
    echo "Connection failed!<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    
    // Thêm thông tin chi tiết về lỗi nếu có
    if ($e instanceof ErrorException) {
        echo "Error code: " . $e->getCode() . "<br>";
    }
}

// Thử kết nối TCP trực tiếp để kiểm tra xem máy chủ có hoạt động không
echo "<br>Testing direct TCP connection to 192.168.1.5:8080...<br>";
$socket = @fsockopen('192.168.1.5', 8080, $errno, $errstr, 5);
if ($socket) {
    echo "TCP connection successful!<br>";
    fclose($socket);
} else {
    echo "TCP connection failed: $errstr ($errno)<br>";
}
?>