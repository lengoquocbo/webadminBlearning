<?php
include '../layouts/header.php'; 
require_once __DIR__ . '/../../controllers/ClassController.php';

// Khởi tạo ClassController và lấy danh sách lớp học
$controller = new ClassController();
$classes = $controller->getAllclass();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý lớp học</title>
    <link rel="stylesheet" href="/assets/css/class.css">
</head>
<body>
<div class="main-content">
    <?php $page = 'classes'; $showSidebar = true; ?>

    <!-- Tiêu đề và nút thêm -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Quản lý lớp học</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="/admin/class.php?action=create" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus"></i> Thêm lớp học
            </a>
        </div>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            Thao tác thành công!
        </div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- Form tìm kiếm -->
    <div class="card">
        <div class="card-header">
            <form method="GET" action="/admin/class.php" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm theo tên lớp"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
        </div>

        <!-- Bảng danh sách lớp học -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên lớp</th>
                            <th>Giáo viên phụ trách</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Khóa ghi danh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($classes)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có lớp học nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($class['classID']); ?></td>
                                    <td><?php echo htmlspecialchars($class['className']); ?></td>
                                    <td><?php echo htmlspecialchars($class['teacherName']); ?></td>
                                    <td><?php echo htmlspecialchars($class['description']); ?></td>
                                    <td><?php echo htmlspecialchars($class['createAt']); ?></td>
                                    <td><?php echo htmlspecialchars($class['enrollmentKey']); ?></td>
                                    <td>
                                        <a href="/admin/class.php?action=view&id=<?php echo htmlspecialchars($class['classID']); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        <a href="/admin/class.php?action=edit&id=<?php echo htmlspecialchars($class['classID']); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <form method="POST" action="/admin/class.php?action=delete&id=<?php echo htmlspecialchars($class['classID']); ?>" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?')">
                                                <i class="fas fa-trash-alt"></i> Xóa
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
<?php include '../layouts/footer.php'; ?>
</body>
</html>