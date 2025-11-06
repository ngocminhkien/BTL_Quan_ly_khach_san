<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
// *** GỌI CẢ 2 FILE FUNCTIONS ***
require_once '../../functions/room_functions.php';
require_once '../../functions/room_type_functions.php';

// 1. Lấy ID phòng cần sửa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID phòng không hợp lệ.");
}
$room_id = $_GET['id'];

// 2. Lấy thông tin cũ của phòng BẰNG HÀM
$room = getRoomById($conn, $room_id);
if ($room === null) {
    die("Không tìm thấy phòng.");
}

// 3. Lấy TẤT CẢ loại phòng BẰNG HÀM
$result_types = getAllRoomTypes($conn);

require_once '../partials/header.php';
?>

<form class="form-layout" action="../../handle/room_process.php" method="POST">
    
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">

    <div class="form-group">
        <label for="room_type_id">Loại phòng:</label>
        <select id="room_type_id" name="room_type_id" required>
            <option value="">-- Chọn loại phòng --</option>
            <?php
            // Code lặp while cho dropdown (để 'selected') giữ nguyên
            if ($result_types->num_rows > 0) {
                while ($type = $result_types->fetch_assoc()) {
                    $selected = ($type['id'] == $room['room_type_id']) ? 'selected' : '';
                    echo "<option value='" . $type['id'] . "' " . $selected . ">" . htmlspecialchars($type['type_name']) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    </form>

<?php
$conn->close();
require_once '../partials/footer.php';
?>