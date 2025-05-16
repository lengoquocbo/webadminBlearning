<?php

// controllers/ClassController.php

require_once __DIR__ . '/../API/Auth.php';
require_once __DIR__ . '/../API/ApiClient.php';
require_once __DIR__ . '/../config.php';

class ClassController
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
    public function countClasses()
{
    $page = $_GET['page'] ?? 1;
    $pageSize = $_GET['pageSize'] ?? 10;
    $search = $_GET['search'] ?? '';

    $params = [
        'page' => $page,
        'pageSize' => $pageSize
    ];

    if (!empty($search)) {
        $params['search'] = $search;
    }

    $response = $this->apiClient->get('users/admin/get/class', $params);

    // Đếm số lớp học dựa trên kiểu dữ liệu trả về
    if (is_array($response) && isset($response[0]['classID'])) {
        return count($response); // Trả về tổng số lớp học
    }

    if (isset($response['data'])) {
        return count($response['data']);
    } elseif (isset($response['results'])) {
        return count($response['results']);
    } elseif (isset($response['users'])) {
        return count($response['users']);
    }

    return 0; // Trả về 0 nếu không có dữ liệu
}
  public function getAllclass()
{
    $page = $_GET['page'] ?? 1;
    $pageSize = $_GET['pageSize'] ?? 10;
    $search = $_GET['search'] ?? '';

    $params = [
        'page' => $page,
        'pageSize' => $pageSize
    ];

    if (!empty($search)) {
        $params['search'] = $search;
    }

    $response = $this->apiClient->get('users/admin/get/class', $params);

    // Nếu response là mảng số (array of objects)
    if (is_array($response) && isset($response[0]['classID'])) {
        $totalClasses = count($response); // Đếm số lớp học
        return $response;
    }

    // Các kiểu khác
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
            $classData = [
                'name' => $_POST['name'] ?? '',
                'teacherId' => $_POST['teacherId'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            try {
                // Gọi API tạo lớp học mới
                $response = $this->apiClient->post('/api/admin/classes', $classData);
                header('Location: /admin/classes.php?success=1');
                exit;
            } catch (Exception $e) {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        
        // Lấy danh sách giáo viên cho dropdown
        try {
            $teachers = $this->apiClient->get('/api/admin/teachers', ['pageSize' => 100])['data'] ?? [];
        } catch (Exception $e) {
            $teachers = [];
            $error = "Lỗi khi lấy danh sách giáo viên: " . $e->getMessage();
        }
        
        require_once __DIR__ . '/../views/classes/create.php';
    }
    
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classData = [
                'name' => $_POST['name'] ?? '',
                'teacherId' => $_POST['teacherId'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            try {
                // Gọi API cập nhật thông tin lớp học
                $response = $this->apiClient->put('/api/admin/classes/' . $id, $classData);
                header('Location: /admin/classes.php?success=1');
                exit;
            } catch (Exception $e) {
                $error = "Có lỗi xảy ra: " . $e->getMessage();
            }
        }
        
        try {
            // Lấy thông tin chi tiết lớp học
            $class = $this->apiClient->get('/api/admin/classes/' . $id);
            
            // Lấy danh sách giáo viên cho dropdown
            $teachers = $this->apiClient->get('/api/admin/teachers', ['pageSize' => 100])['data'] ?? [];
            
            require_once __DIR__ . '/../views/classes/edit.php';
        } catch (Exception $e) {
            $error = "Lỗi khi lấy dữ liệu: " . $e->getMessage();
            header('Location: /admin/classes.php?error=' . urlencode($error));
            exit;
        }
    }
    
public function view($id)
{
    try {
        // Lấy thông tin chi tiết lớp học

        // Lấy danh sách học sinh trong lớp theo router class/students/{ID}
        $response = $this->apiClient->get('class/students/' . $id);
        $students = $response;
        if (!is_array($students)) {
    $students = [$students];
      }
        require_once __DIR__ . '/../views/classes/view.php';
    } catch (Exception $e) {
        $error = "Lỗi khi lấy dữ liệu: " . $e->getMessage();
        header('Location: /admin/classes.php?error=' . urlencode($error));
        exit;
    }
}
    
    public function delete($id)
    {
        try {
            // Gọi API xóa lớp học
            $this->apiClient->delete('/api/admin/classes/' . $id);
            header('Location: /admin/classes.php?success=1');
            exit;
        } catch (Exception $e) {
            $error = "Lỗi khi xóa lớp học: " . $e->getMessage();
            header('Location: /admin/classes.php?error=' . urlencode($error));
            exit;
        }
    }
}