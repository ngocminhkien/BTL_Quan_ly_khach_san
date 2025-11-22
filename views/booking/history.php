<?php
require_once '../../functions/auth_check.php'; // Bắt buộc đăng nhập
require_once '../../functions/db_connection.php';
require_once '../../functions/booking_functions.php';

// Vì đây là trang dành cho khách hàng, ta có thể dùng Header khác hoặc Header chung nhưng ẩn menu Admin
// Ở đây tôi sẽ dùng một cấu trúc HTML đơn giản, sạch sẽ, kế thừa style của trang chủ hoặc admin tùy bạn chọn.
// Để nhanh chóng, tôi dùng lại style của Admin nhưng tắt menu Admin đi.

$user_id = $_SESSION['user_id'];
$result = getBookingsByCustomerId($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt phòng - Mellow Hotel</title>
    <link rel="stylesheet" href="/BTL/assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Tùy chỉnh riêng cho trang này */
        body { background-color: #f4f7fa; }
        .container-history { max-width: 1000px; margin: 50px auto; padding: 0 20px; }
        .history-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-back { text-decoration: none; color: #667eea; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .btn-cancel-booking {
            background-color: #fff5f5; color: #e53e3e;
            border: 1px solid #e53e3e;
            padding: 5px 10px; border-radius: 5px;
            text-decoration: none; font-size: 12px; font-weight: 600;
            transition: 0.2s;
        }
        .btn-cancel-booking:hover { background-color: #e53e3e; color: white; }
        .disabled-link { opacity: 0.5; pointer-events: none; cursor: default; filter: grayscale(1); }
    </style>
</head>
<body>

<div class="container-history">
    <div class="history-header">
        <div>
            <a href="/BTL/index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Quay về Trang chủ</a>
            <h2 style="margin-top: 10px; color: #2d3748;">Lịch sử đặt phòng của bạn</h2>
        </div>
        <div class="user-info">
            Xin chào, <strong><?php echo htmlspecialchars($_SESSION['user_username']); ?></strong>
            <a href="/BTL/logout.php" class="btn-logout" style="font-size: 14px;">Đăng xuất</a>
        </div>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'cancelled'): ?>
        <div class="alert-box alert-success" style="margin-bottom: 20px; padding: 15px; background: #d1fae5; color: #065f46; border-radius: 8px;">
            <i class="fa-solid fa-check-circle"></i> Đã hủy đơn đặt phòng thành công!
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Phòng</th>
                    <th>Thời gian</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><span class="col-id">#<?php echo $row['id']; ?></span></td>
                            <td>
                                <strong><?php echo $row['room_type']; ?></strong><br>
                                <small style="color: #718096;">Phòng: <?php echo $row['room_number'] ? $row['room_number'] : 'Đang xếp'; ?></small>
                            </td>
                            <td>
                                <div style="font-size: 13px; color: #4a5568;">
                                    <?php echo date('d/m/Y', strtotime($row['check_in_date'])); ?> <i class="fa-solid fa-arrow-right" style="font-size: 10px; color:#ccc"></i> <?php echo date('d/m/Y', strtotime($row['check_out_date'])); ?>
                                </div>
                            </td>
                            <td class="col-price"><?php echo number_format($row['total_price']); ?> ₫</td>
                            <td>
                                <?php
                                $st = $row['status'];
                                if ($st == 'pending') echo '<span class="status-badge status-pending">Chờ duyệt</span>';
                                elseif ($st == 'confirmed') echo '<span class="status-badge status-confirmed">Đã xác nhận</span>';
                                elseif ($st == 'checked_in') echo '<span class="status-badge status-occupied">Đang ở</span>';
                                elseif ($st == 'checked_out') echo '<span class="status-badge status-checked-out">Hoàn tất</span>';
                                elseif ($st == 'cancelled') echo '<span class="status-badge status-cancelled">Đã hủy</span>';
                                ?>
                            </td>
                            <td>
                                <?php if ($st == 'pending'): ?>
                                    <a href="../../handle/booking_process.php?action=cancel&id=<?php echo $row['id']; ?>" 
                                       class="btn-cancel-booking"
                                       onclick="return confirm('Bạn chắc chắn muốn hủy đơn đặt phòng này?');">
                                       <i class="fa-solid fa-xmark"></i> Hủy phòng
                                    </a>
                                <?php else: ?>
                                    <span class="btn-cancel-booking disabled-link">Hủy phòng</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 30px; color: #718096;">Bạn chưa có đơn đặt phòng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>