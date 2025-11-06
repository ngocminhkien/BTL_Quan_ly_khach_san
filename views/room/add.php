<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
// *** GỌI FILE FUNCTIONS CỦA ROOM_TYPE ***
require_once '../../functions/room_type_functions.php'; 

// Lấy danh sách LOẠI PHÒNG BẰNG HÀM
$result_types = getAllRoomTypes($conn);

require_once '../partials/header.php';
?>

<form class="form-layout" action="../../handle/room_process.php" method="POST">
    
    <input type="hidden" name="action" value="add">

    <div class="form-group">
        <label for="room_type_id">Loại phòng:</label>
        <select id="room_type_id" name="room_type_id" required>
            <option value="">-- Chọn loại phòng --</option>
            <?php
            // Code lặp while cho dropdown giữ nguyên
            if ($result_types->num_rows > 0) {
                while ($type = $result_types->fetch_assoc()) {
                    echo "<option value='" . $type['id'] . "'>" . htmlspecialchars($type['type_name']) . "</option>";
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