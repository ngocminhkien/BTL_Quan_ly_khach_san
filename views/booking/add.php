<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../../functions/customer_functions.php';  // Cần để lấy danh sách khách
require_once '../../functions/room_type_functions.php'; // Cần để lấy loại phòng
require_once '../partials/header.php';

// Lấy dữ liệu để điền vào ô chọn (Dropdown)
$customers = getAllCustomers($conn);
$room_types = getAllRoomTypes($conn);
?>

<div class="page-title-card">
    <div>
        <h2>Tạo Đơn Đặt Phòng Mới</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Nhập thông tin để kiểm tra phòng trống và tạo đơn</p>
    </div>
    <div style="font-size: 40px; opacity: 0.3;"><i class="fa-solid fa-calendar-plus"></i></div>
</div>

<div class="form-card">
    
    <?php if (isset($_GET['error']) && $_GET['error'] == 'no_rooms'): ?>
        <div class="alert-box" style="background:#fff5f5; color:#e53e3e; border:1px solid #feb2b2; padding:15px; border-radius:10px; margin-bottom:20px;">
            <i class="fa-solid fa-circle-exclamation"></i> <strong>Rất tiếc!</strong> Không còn phòng trống cho lựa chọn này.
        </div>
    <?php endif; ?>

    <form action="../../handle/booking_process.php" method="POST">
        <input type="hidden" name="action" value="add">
        
        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-user" style="color: #667eea; margin-right: 5px;"></i> Khách hàng <span style="color: red;">*</span>
            </label>
            <select name="customer_id" class="form-control" required>
                <option value="">-- Chọn khách hàng --</option>
                <?php while ($c = $customers->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>">
                        <?php echo htmlspecialchars($c['full_name']) . ' (' . htmlspecialchars($c['phone']) . ')'; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <small style="color: #718096; font-size: 12px; margin-top: 5px; display: block;">
                Chưa có khách hàng? <a href="../customer/add.php" style="color: #667eea; font-weight: 600;">Thêm mới tại đây</a>
            </small>
        </div>
        
        <div class="form-group">
            <label class="form-label">
                <i class="fa-solid fa-bed" style="color: #667eea; margin-right: 5px;"></i> Loại phòng mong muốn <span style="color: red;">*</span>
            </label>
            <select name="room_type_id" class="form-control" required>
                <option value="">-- Chọn loại phòng --</option>
                <?php while ($rt = $room_types->fetch_assoc()): ?>
                    <option value="<?php echo $rt['id']; ?>">
                        <?php echo htmlspecialchars($rt['type_name']) . ' - ' . number_format($rt['price_per_night']) . ' VND/đêm'; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-calendar-check" style="color: #38b2ac; margin-right: 5px;"></i> Ngày Check-in <span style="color: red;">*</span>
                </label>
                <input type="date" name="check_in_date" class="form-control" 
                       value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-calendar-xmark" style="color: #e53e3e; margin-right: 5px;"></i> Ngày Check-out <span style="color: red;">*</span>
                </label>
                <input type="date" name="check_out_date" class="form-control" 
                       value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <a href="list.php" class="btn-cancel-form">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-magnifying-glass-dollar"></i> Kiểm tra & Đặt phòng
            </button>
        </div>
    </form>
</div>

<?php 
$conn->close(); 
require_once '../partials/footer.php'; 
?>