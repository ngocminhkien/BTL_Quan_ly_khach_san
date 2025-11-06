<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI FILE FUNCTIONS CỦA ROOM_TYPE VÀ CUSTOMER ***
require_once '../../functions/room_type_functions.php';
require_once '../../functions/customer_functions.php'; // (Giả sử bạn đã tạo file này)

// Lấy dữ liệu BẰNG HÀM
$result_customers = getAllCustomers($conn); // (Giả sử bạn đã tạo hàm này)
$result_room_types = getAllRoomTypes($conn); // (Hàm chúng ta đã tạo)
?>

<form class="form-layout" action="../../handle/booking_process.php" method="POST">
    
    <input type="hidden" name="action" value="add">
    
    </form>

<?php
$conn->close();
require_once '../partials/footer.php';
?>