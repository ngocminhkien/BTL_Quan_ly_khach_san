<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../../functions/room_type_functions.php'; 
require_once '../partials/header.php';

// Lấy danh sách loại phòng để hiển thị trong dropdown
$types = getAllRoomTypes($conn);
?>

<div class="page-title-card">
    <div>
        <h2>Thêm Phòng Mới</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Nhập thông tin để tạo phòng mới vào hệ thống</p>
    </div>
</div>

<div class="form-card">
    <form action="../../handle/room_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label class="form-label">Số phòng / Tên phòng <span style="color: red;">*</span></label>
            <input type="text" name="room_number" class="form-control" placeholder="Ví dụ: 101, 202, VIP-01..." required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Loại phòng <span style="color: red;">*</span></label>
            <select name="room_type_id" class="form-control" required>
                <option value="">-- Chọn loại phòng --</option>
                <?php while($t = $types->fetch_assoc()): ?>
                    <option value="<?php echo $t['id']; ?>">
                        <?php echo htmlspecialchars($t['type_name']) . ' (' . number_format($t['price_per_night']) . ' VND)'; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Trạng thái ban đầu</label>
            <select name="status" class="form-control">
                <option value="available">🟢 Có sẵn (Available)</option>
                <option value="maintenance">🟠 Đang bảo trì (Maintenance)</option>
                <option value="cleaning">🟡 Đang dọn dẹp (Cleaning)</option>
            </select>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel-form">Hủy bỏ</a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-save"></i> Lưu phòng
            </button>
        </div>
    </form>
</div>

<?php 
$conn->close(); 
require_once '../partials/footer.php'; 
?>