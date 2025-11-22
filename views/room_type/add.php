<?php
require_once '../../functions/admin_check.php';
require_once '../partials/header.php';
?>

<div class="page-title-card">
    <div>
        <h2>Thêm Loại Phòng Mới</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Thiết lập các hạng phòng và đơn giá cho khách sạn</p>
    </div>
    <div style="font-size: 40px; opacity: 0.3;"><i class="fa-solid fa-layer-group"></i></div>
</div>

<div class="form-card">
    <form action="../../handle/room_type_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-tag" style="color: #667eea; margin-right: 5px;"></i> Tên loại phòng <span style="color: red;">*</span>
            </label>
            <input type="text" name="type_name" class="form-control" placeholder="Ví dụ: Deluxe King, Standard Twin..." required>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-money-bill-wave" style="color: #38b2ac; margin-right: 5px;"></i> Giá niêm yết (VND/đêm) <span style="color: red;">*</span>
            </label>
            <input type="number" name="price_per_night" class="form-control" placeholder="Ví dụ: 500000" min="0" step="1000" required>
            <small style="color: #718096; font-size: 12px; margin-top: 5px; display: block;">
                * Giá này sẽ được dùng để tính toán tự động khi đặt phòng.
            </small>
        </div>

        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-circle-info" style="color: #667eea; margin-right: 5px;"></i> Mô tả tiện nghi
            </label>
            <textarea name="description" class="form-control" rows="4" placeholder="Ví dụ: Diện tích 30m2, hướng biển, có bồn tắm..."></textarea>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel-form">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Lưu loại phòng
            </button>
        </div>
    </form>
</div>

<?php 
require_once '../partials/footer.php'; 
?>