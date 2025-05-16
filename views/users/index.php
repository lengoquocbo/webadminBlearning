<?php
include '../../views/layouts/header.php';
require_once __DIR__ . '/../../controllers/UserController.php';

$controller = new UserController();
$users = $controller->getAlluser();
?>

<div class="main-content">
    <?php
    $page = 'users';
    $showSidebar = true;
    ?>

    <!-- Tiêu đề và nút thêm -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý tài khoản</h1>

    </div>

    <!-- Thông báo -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Thao tác thành công!</div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Form tìm kiếm -->
    <div class="card">
        <div class="card-header">
            <form method="GET" action="/admin/users.php" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Tìm theo tên hoặc email" value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="" <?php echo (isset($type) && $type === '') ? 'selected' : ''; ?>>Tất cả</option>
                        <option value="teacher" <?php echo (isset($type) && $type === 'teacher') ? 'selected' : ''; ?>>Giáo viên</option>
                        <option value="student" <?php echo (isset($type) && $type === 'student') ? 'selected' : ''; ?>>Học sinh</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <!-- Bảng người dùng -->
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['userID']); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['sdt']); ?></td>
                                    <td>
                                        <?php
                                        $role = strtoupper($user['role']);
                                        if ($role === 'TEACHER') {
                                            echo '<span class="badge bg-primary">Giáo viên</span>';
                                        } elseif ($role === 'STUDENT') {
                                            echo '<span class="badge bg-info">Học sinh</span>';
                                        } elseif ($role === 'ADMIN') {
                                            echo '<span class="badge bg-warning">Admin</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Khác</span>';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <a href="/WebAdmin_Blearning/index.php?path=users&action=edit&id=<?php echo urlencode($user['userID']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <form method="POST" action="/WebAdmin_Blearning/index.php?path=users&action=delete&id=<?php echo urlencode($user['userID']); ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                                <i class="fas fa-trash"></i> Vô hiệu hóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scrollbar đẹp -->
<style>
    .table-responsive::-webkit-scrollbar {
        width: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 3px;
    }
</style>

<?php include '../../views/layouts/footer.php'; ?>