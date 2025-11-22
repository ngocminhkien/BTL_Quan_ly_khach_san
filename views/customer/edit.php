<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/customer_functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID không hợp lệ.");
}
$customer_id = $_GET['id'];

// Lấy thông tin cũ BẰNG HÀM
$customer = getCustomerById($conn, $customer_id);
if ($customer === null) {
    die("Không tìm thấy khách hàng.");
}
$conn->close();

require_once '../partials/header.php';
?>

<div class="page-title">
    <h2>Sửa thông tin: <?php echo htmlspecialchars($customer['full_name']); ?></h2>
</div>

<form class="form-layout" action="../../handle/customer_process.php" method="POST">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

    <div class="form-group">
        <label for="full_name">Họ và Tên:</label>
        <input type="text" id="full_name" name="full_name" 
               value="<?php echo htmlspecialchars($customer['full_name']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" 
               value="<?php echo htmlspecialchars($customer['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" 
               value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn-submit">Cập nhật</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>
</form>

<?php require_once '../partials/footer.php'; ?>