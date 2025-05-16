


<?php include_once __DIR__ . '/../layouts/header.php'; 
// Debug xem dữ liệu có đúng là mảng không

?>

<style>
    .student-card {
        max-width: 420px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 32px 28px 24px 28px;
    }
    .student-title {
        text-align: center;
        color: #2563eb;
        margin-bottom: 18px;
        font-weight: 600;
        font-size: 1.5rem;
    }
    .student-info {
        font-size: 1.1rem;
        color: #2d3a4b;
        margin-bottom: 8px;
    }
    .back-btn {
        display: block;
        margin: 24px auto 0 auto;
        padding: 10px 28px;
        background: #4f8cff;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
    }
    .back-btn:hover {
        background: #2563eb;
        color: #fff;
    }
</style>

<div class="student-card">
    <div class="student-title">Danh sách sinh viên</div>
    <?php if (!empty($students)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã sinh viên</th>
                    <th>Họ tên</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['studentID'] ?? '') ?></td>
                        <td><?= htmlspecialchars($student['studentname'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Chưa có sinh viên nào trong lớp này.</div>
    <?php endif; ?>
<a href="/WebAdmin_Blearning/views/classes/index.php" class="back-btn">Quay lại</a></div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
