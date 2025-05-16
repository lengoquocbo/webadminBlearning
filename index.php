<?php
session_start();
require_once 'config.php';
require_once 'API/ApiClient.php';
require_once 'API/Auth.php';


$apiClient = new ApiClient(API_BASE_URL);

$auth = new Auth($apiClient);

// Đặt múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Lấy tham số điều hướng
$path = $_GET['path'] ?? 'dashboard/dashboard.php';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Router
switch ($path) {
    case 'login.php':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout.php':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard.php':
        require_once 'controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
        break;

   case 'users':
    require_once 'controllers/UserController.php';
    $controller = new UserController();

    switch ($action) {
        case 'edit':
            $controller->edit($id);
            break;
        case 'view':
            $controller->view($id);
            break;
        case 'delete':
            $controller->delete($id);
            break;
        default:
            $controller->getAlluser();
    }
    break;

    case 'classes.php':
        require_once 'controllers/ClassController.php';
        $controller = new ClassController();

        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'view':
                $controller->view($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                $controller->getAllclass();
        }
        break;

    default:
    
    assert($path !== 'index.php', 'Không được phép truy cập trực tiếp vào index.php');
    header('Location: /WebAdmin_Blearning/views/auth/login.php');
    exit;
    // Nếu không khớp route nào → redirect về dashboard

}
