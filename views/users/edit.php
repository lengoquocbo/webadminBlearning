<?php
include '/xampp/htdocs/WebAdmin_Blearning/views/layouts/header.php';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa người dùng</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }

        .edit-container {
            background: #fff;
            max-width: 420px;
            margin: 40px auto;

            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #2d3a4b;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #3b4a5a;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 30px;
            padding:5px;
            border: 1px solid #d1d9e6;
            border-radius: 6px;
            font-size: 15px;
            background: #f9fafc;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #4f8cff;
            outline: none;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 18px;
        }

        button {
            background: #4f8cff;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #2563eb;
        }

        .back-link {
            color: #4f8cff;
            text-decoration: none;
            font-size: 15px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 16px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="edit-container">
        <h2>Chỉnh sửa người dùng</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Tên người dùng</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? $user['username'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Gmail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="sdt">Số điện thoại</label>
                <input type="text" id="sdt" name="sdt" value="<?= htmlspecialchars($user['sdt'] ?? $user['sdt'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" placeholder="Để trống nếu không đổi">
            </div>
            <div class="form-group">
                <label for="sdt">Role</label>
                <input type="text" id="role" name="role" value="<?= htmlspecialchars($user['role'] ?? $user['role'] ?? '') ?>">
            </div>
            <div class="btn-group">
                <button type="submit">Lưu thay đổi</button>
                <a href="/WebAdmin_Blearning/views/users/index.php" class="back-link">Quay lại</a>
            </div>
        </form>
    </div>
</body>

</html>
<?php
include '/xampp/htdocs/WebAdmin_Blearning/views/layouts/footer.php';
?>