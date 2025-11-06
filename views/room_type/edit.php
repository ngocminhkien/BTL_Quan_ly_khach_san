<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/room_type_functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID không hợp lệ.");
}
$type_id = $_GET['id'];

// Lấy thông tin cũ BẰNG HÀM
$type = getRoomTypeById($conn, $type_id); 

if ($type === null) {
    die("Không tìm thấy loại phòng.");
}

require_once '../partials/header.php';
?>

<div class="page-title">
    <h2>Sửa Loại phòng: <?php echo htmlspecialchars($type['type_name']); ?></h2>
</div>

<form class="form-layout" action="../../handle/room_type_process.php" method="POST">
    
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="type_id" value="<?php echo $type_id; ?>">

    <div class="form-group">
        <label for="type_name">Tên loại phòng:</label>
        <input type="text" id="type_name" name="type_name" 
               value="<?php echo htmlspecialchars($type['type_name']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="price_per_night">Giá (VND/đêm):</label>
        <input type="number" id="price_per_night" name="price_per_night" 
               value="<?php echo htmlspecialchars($type['price_per_night']); ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <input type="text" id="description" name="description"
               value="<?php echo htmlspecialchars($type['description']); ?>">
    </div>

    <div class="form-group">
        <button type="submit" class="btn-submit">Cập nhật</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>

</form>

<?php
$conn->close();
require_once '../partials/footer.php';
?>