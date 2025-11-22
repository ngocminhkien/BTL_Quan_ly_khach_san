<?php
require_once '../../functions/admin_check.php';
require_once '../partials/header.php';
?>

<div class="page-title-card">
    <div>
        <h2>Thêm Người Dùng Mới</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Tạo tài khoản quản trị viên hoặc nhân viên mới</p>
    </div>
    <div style="font-size: 40px; opacity: 0.3;"><i class="fa-solid fa-user-plus"></i></div>
</div>

<div class="form-card">
    <form action="../../handle/user_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label class="form-label">
                <i class="fa-regular fa-id-card" style="color: #667eea; margin-right: 5px;"></i> Họ và Tên <span style="color: red;">*</span>
            </label>
            <input type="text" name="full_name" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-user" style="color: #667eea; margin-right: 5px;"></i> Tên đăng nhập <span style="color: red;">*</span>
            </label>
            <input type="text" name="username" class="form-control" placeholder="Ví dụ: admin01, nhanvien_a..." required>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-lock" style="color: #667eea; margin-right: 5px;"></i> Mật khẩu <span style="color: red;">*</span>
            </label>
            <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu bảo mật..." required>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-shield-halved" style="color: #667eea; margin-right: 5px;"></i> Phân quyền
            </label>
            <select name="role" class="form-control">
                <option value="2">👤 Người dùng (User/Nhân viên)</option>
                <option value="1">👑 Quản trị viên (Admin - Toàn quyền)</option>
            </select>
            <small style="color: #718096; font-size: 12px; margin-top: 5px; display: block;">
                * Admin có quyền xóa sửa mọi dữ liệu. User chỉ có quyền hạn chế.
            </small>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel-form">
                <i class="fa-solid fa-arrow-left"></i> Hủy bỏ
            </a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Lưu người dùng
            </button>
        </div>
    </form>
</div>

<?php 
require_once '../partials/footer.php'; 
?>