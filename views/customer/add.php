<?php
require_once '../../functions/admin_check.php';
require_once '../partials/header.php';
?>

<div class="page-title-card">
    <div>
        <h2>Thêm Khách Hàng Mới</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Lưu thông tin khách hàng để thuận tiện cho việc đặt phòng</p>
    </div>
    <div style="font-size: 40px; opacity: 0.3;"><i class="fa-solid fa-address-book"></i></div>
</div>

<div class="form-card">
    <form action="../../handle/customer_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-user-tag" style="color: #667eea; margin-right: 5px;"></i> Họ và Tên <span style="color: red;">*</span>
            </label>
            <input type="text" name="full_name" class="form-control" placeholder="Ví dụ: Trần Thị B..." required>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-envelope" style="color: #667eea; margin-right: 5px;"></i> Email liên hệ <span style="color: red;">*</span>
            </label>
            <input type="email" name="email" class="form-control" placeholder="khachhang@email.com" required>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-phone" style="color: #667eea; margin-right: 5px;"></i> Số điện thoại <span style="color: red;">*</span>
            </label>
            <input type="text" name="phone" class="form-control" placeholder="09xx xxx xxx" required>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel-form">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Lưu khách hàng
            </button>
        </div>
    </form>
</div>

<?php 
require_once '../partials/footer.php'; 
?>