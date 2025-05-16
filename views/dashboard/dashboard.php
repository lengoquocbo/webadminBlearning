<?php
session_start(); // Thêm dòng này ở đầu file

include_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';
$apiClient = new ApiClient(API_BASE_URL);
$auth = new Auth($apiClient);
if (!$auth->isLoggedIn()) {
    header('Location: /WebAdmin_Blearning/views/auth/login.php');
    exit;
}

// Khởi tạo DashboardController
$dashboardController = new DashboardController();

// Gọi phương thức index() để lấy dữ liệu thống kê
$stats = $dashboardController->index();
?>

<div class="container mt-4">
    <h2 class="mb-4">Tổng Quan Hệ Thống</h2>
    <div class="row">
        <!-- Tổng số lớp học -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Tổng số lớp học</h5>
                    <h2><?= $stats['totalClasses'] ?? '...' ?></h2>
                </div>
            </div>
        </div>

        <!-- Tổng số giáo viên -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Tổng số giáo viên</h5>
                    <h2><?= $stats['totalTeachers'] ?? '...' ?></h2>
                </div>
            </div>
        </div>

        <!-- Tổng số học sinh -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Tổng số học sinh</h5>
                    <h2><?= $stats['totalStudents'] ?? '...' ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>