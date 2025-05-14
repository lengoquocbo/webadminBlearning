<?php
// user_profile.php
require_once '../config.php';
require_once '../API/ApiClient.php';
require_once '../API/Auth.php';

// Hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Khởi tạo session nếu chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['admin_token'])) {
    echo "Bạn chưa đăng nhập. <a href='debug_login.php'>Đăng nhập ngay</a>";
    exit;
}

$apiClient = new ApiClient(API_BASE_URL, $_SESSION['admin_token']);
$auth = new Auth($apiClient);

// Lấy thông tin người dùng từ token JWT
$token = $_SESSION['admin_token'];
$parts = explode('.', $token);
$payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
$tokenData = json_decode($payload, true);

// Thử lấy thông tin chi tiết người dùng từ API (nếu có endpoint)
$userData = null;
try {
    // Thử lấy thông tin người dùng từ API - thay đổi endpoint nếu cần
    $userId = $tokenData['userID'] ?? '';
    if ($userId) {
        $userData = $apiClient->get("users/$userId");
        echo "<div style='color: green'>Đã lấy thông tin người dùng từ API thành công!</div>";
    }
} catch (Exception $e) {
    echo "<div style='color: orange'>Không thể lấy thông tin chi tiết từ API: " . $e->getMessage() . "</div>";
    echo "<div>Đang sử dụng thông tin từ JWT token</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thông tin người dùng</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .user-info { display: flex; }
        .user-details { flex: 1; }
        .avatar { width: 100px; height: 100px; background: #eee; border-radius: 50%; display: flex; 
                 align-items: center; justify-content: center; margin-right: 20px; }
        .data-row { margin-bottom: 10px; }
        .label { font-weight: bold; display: inline-block; width: 120px; }
        h2 { color: #333; }
        .token-info { background: #f5f5f5; padding: 15px; border-radius: 5px; margin-top: 20px; }
        .token-data { overflow-wrap: break-word; }
        pre { background: #f9f9f9; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Thông tin người dùng</h1>
    
    <div class="card">
        <h2>Thông tin cơ bản</h2>
        <div class="user-info">
            <div class="avatar">
                <?php 
                    $username = isset($tokenData['username']) ? $tokenData['username'] : 'User';
                    echo substr($username, 0, 1); 
                ?>
            </div>
            <div class="user-details">
                <div class="data-row">
                    <span class="label">User ID:</span>
                    <span><?php echo htmlspecialchars($tokenData['userID'] ?? 'N/A'); ?></span>
                </div>
                <div class="data-row">
                    <span class="label">Username:</span>
                    <span><?php echo htmlspecialchars($tokenData['username'] ?? 'N/A'); ?></span>
                </div>
                <div class="data-row">
                    <span class="label">Email:</span>
                    <span><?php echo htmlspecialchars($tokenData['email'] ?? 'N/A'); ?></span>
                </div>
                <div class="data-row">
                    <span class="label">Role:</span>
                    <span><?php echo htmlspecialchars($tokenData['role'] ?? 'N/A'); ?></span>
                </div>
                <?php if (isset($tokenData['sdt'])): ?>
                <div class="data-row">
                    <span class="label">Số điện thoại:</span>
                    <span><?php echo htmlspecialchars($tokenData['sdt']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($userData): ?>
                <!-- Hiển thị thông tin bổ sung từ API nếu có -->
                <div class="data-row">
                    <span class="label">Ngày tạo:</span>
                    <span><?php echo htmlspecialchars($userData['created_at'] ?? 'N/A'); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="card">
        <h2>Thông tin chi tiết từ token</h2>
        <div class="token-info">
            <div class="data-row">
                <span class="label">JWT Token:</span>
                <div class="token-data"><?php echo substr($_SESSION['admin_token'], 0, 20) . '...'; ?></div>
            </div>
            
            <div class="data-row">
                <span class="label">Hết hạn:</span>
                <span>
                    <?php 
                        if (isset($tokenData['exp'])) {
                            $expTime = date('Y-m-d H:i:s', $tokenData['exp']); 
                            $now = time();
                            $remaining = $tokenData['exp'] - $now;
                            $remainingHours = floor($remaining / 3600);
                            $remainingMinutes = floor(($remaining % 3600) / 60);
                            
                            echo $expTime . " (còn $remainingHours giờ $remainingMinutes phút)";
                        } else {
                            echo 'N/A';
                        }
                    ?>
                </span>
            </div>
        </div>
        
        <h3>Dữ liệu JWT đầy đủ:</h3>
        <pre><?php print_r($tokenData); ?></pre>
        
        <?php if ($userData): ?>
        <h3>Dữ liệu người dùng từ API:</h3>
        <pre><?php print_r($userData); ?></pre>
        <?php endif; ?>
    </div>
    
    <p><a href="debug_login.php?logout=1">Đăng xuất</a></p>
</body>
</html>