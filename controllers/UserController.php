<?php
// controllers/UserController.php

require_once __DIR__ . '/../API/Auth.php';
require_once __DIR__ . '/../API/ApiClient.php';
require_once __DIR__ . '/../config.php';

class UserController
{
    private $auth;
    private $apiClient;
    
    public function __construct()
    {
        $this->apiClient = new ApiClient(API_BASE_URL);
        $this->auth = new Auth($this->apiClient);
        $this->auth->requireLogin();
        $this->apiClient->setToken($this->auth->getToken());
    }
    
public function countTeachers()
{
    $page = $_GET['page'] ?? 1;
    $pageSize = $_GET['pageSize'] ?? 1000; // tăng để lấy được đủ dữ liệu
    $search = $_GET['search'] ?? '';

    $params = [
        'page' => $page,
        'pageSize' => $pageSize
    ];

    if (!empty($search)) {
        $params['search'] = $search;
    }

    $response = $this->apiClient->get('users/admin/get/users', $params);

    // Lấy danh sách người dùng từ các kiểu cấu trúc phổ biến
    $users = [];

    if (isset($response['data']) && is_array($response['data'])) {
        $users = $response['data'];
    } elseif (isset($response['results']) && is_array($response['results'])) {
        $users = $response['results'];
    } elseif (isset($response['users']) && is_array($response['users'])) {
        $users = $response['users'];
    } elseif (is_array($response) && isset($response[0]['role'])) {
        $users = $response;
    }

    // Lọc ra các người dùng có role là TEACHER
    $teachers = array_filter($users, function ($user) {
        return isset($user['role']) && strtoupper(trim($user['role'])) === 'TEACHER';
    });

    return count($teachers);
}

public function countStudents()
{
    $page = $_GET['page'] ?? 1;
    $pageSize = $_GET['pageSize'] ?? 1000;
    $search = $_GET['search'] ?? '';

    $params = [
        'page' => $page,
        'pageSize' => $pageSize
    ];

    if (!empty($search)) {
        $params['search'] = $search;
    }

    $response = $this->apiClient->get('users/admin/get/users', $params);

    // Xác định danh sách user từ phản hồi API
    $users = [];

    if (isset($response['data']) && is_array($response['data'])) {
        $users = $response['data'];
    } elseif (isset($response['results']) && is_array($response['results'])) {
        $users = $response['results'];
    } elseif (isset($response['users']) && is_array($response['users'])) {
        $users = $response['users'];
    } elseif (is_array($response) && isset($response[0]['role'])) {
        $users = $response;
    }

    // Lọc theo role STUDENT
    $students = array_filter($users, function ($user) {
        return isset($user['role']) && strtoupper(trim($user['role'])) === 'STUDENT';
    });

    return count($students);
}
public function getAlluser()
{
    $page = $_GET['page'] ?? 1;
    $pageSize = $_GET['pageSize'] ?? 10;
    $search = $_GET['search'] ?? '';
    $type = $_GET['type'] ?? '';

    $params = [
        'page' => $page,
        'pageSize' => $pageSize
    ];

    if (!empty($search)) {
        $params['search'] = $search;
    }

    if (!empty($type)) {
        $params['type'] = $type;
    }

    $response = $this->apiClient->get('users/admin/get/users', $params);

    // Tùy vào cấu trúc JSON từ API, điều chỉnh dòng này
    if (isset($response['data'])) {
        return $response['data'];
    } elseif (isset($response['results'])) {
        return $response['results'];
    } elseif (isset($response['users'])) {
        return $response['users'];
    }

    return $response;
}



    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'username' => $_POST['username'] ?? '',
                'sdt' => $_POST['sdt'] ?? '',
                'role' => $_POST['role'] ?? 'STUDENT'|| 'TEACHER',

            ];
            
            try {
                $response = $this->apiClient->post('/admin/create/users', $userData);
                header('Location: /admin/users.php?success=1');
                exit;
            } catch (Exception $e) {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        
        require_once 'views/users/create.php';
    }
    
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'fullName' => $_POST['fullName'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
            ];
            
            try {
                $response = $this->apiClient->put('/api/admin/users/' . $id, $userData);
                header('Location: /admin/users.php?success=1');
                exit;
            } catch (Exception $e) {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        
        $user = $this->apiClient->get('/api/admin/users/' . $id);
        require_once 'views/users/edit.php';
    }
    
    public function view($id)
    {
        $user = $this->apiClient->get('/api/admin/users/' . $id);
        require_once 'views/users/view.php';
    }
    
    // public function changeStatus($id)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $active = $_POST['active'] ?? '0';
    //         $active = $active === '1';
            
    //         try {
    //             $response = $this->apiClient->patch('/api/admin/users/' . $id . '/status', [
    //                 'active' => $active
    //             ]);
    //             header('Location: /admin/users.php?success=1');
    //             exit;
    //         } catch (Exception $e) {
    //             $error = "Có lỗi xảy ra: " . $e->getMessage();
    //             require_once 'views/users/index.php';
    //         }
    //     }
    // }
}