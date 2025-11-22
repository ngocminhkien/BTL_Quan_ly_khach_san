<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../../functions/booking_functions.php';
require_once '../partials/header.php';

$result = getAllBookings($conn);
?>

<div class="page-title-card">
    <div>
        <h2>Quản lý Đặt phòng</h2>
        <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">Theo dõi và xử lý đơn đặt phòng</p>
    </div>
    <a href="add.php" class="btn-add-header">
        <i class="fa-solid fa-plus"></i> Tạo Đơn Mới
    </a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert-box alert-success" style="margin-bottom: 20px; padding: 15px; background: #d1fae5; color: #065f46; border-radius: 10px;">
        <i class="fa-solid fa-check-circle"></i> Thao tác thành công!
    </div>
<?php endif; ?>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Phòng</th>
                <th>Check-in / Out</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    
                    <tr class="row-<?php echo $row['status']; ?>">
                        
                        <td><span class="col-id">#<?php echo $row['id']; ?></span></td>
                        
                        <td>
                            <div class="customer-group">
                                <img class="customer-avatar" 
                                     src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['customer_name']); ?>&background=random&color=fff&size=32">
                                <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong>
                            </div>
                        </td>

                        <td>
                            <?php if ($row['room_number']): ?>
                                <span style="font-weight: 700; color:#2d3748"><?php echo $row['room_number']; ?></span><br>
                                <small style="color:#718096"><?php echo $row['room_type']; ?></small>
                            <?php else: ?>
                                <span style="color:#e53e3e; font-size:12px;">Chưa xếp</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="time-group">
                                <i class="fa-regular fa-calendar-check" style="color:#38b2ac"></i> <?php echo date('d/m', strtotime($row['check_in_date'])); ?><br>
                                <i class="fa-regular fa-calendar-xmark" style="color:#e53e3e"></i> <?php echo date('d/m', strtotime($row['check_out_date'])); ?>
                            </div>
                        </td>

                        <td class="col-price"><?php echo number_format($row['total_price']); ?> ₫</td>

                        <td>
                            <?php
                            $st = $row['status'];
                            $badges = [
                                'pending' => ['bg'=>'#fff7ed', 'col'=>'#c05621', 'icon'=>'fa-spinner', 'txt'=>'Chờ duyệt'],
                                'confirmed' => ['bg'=>'#f0fdf4', 'col'=>'#15803d', 'icon'=>'fa-check', 'txt'=>'Đã xác nhận'],
                                'checked_in' => ['bg'=>'#fdf2f8', 'col'=>'#db2777', 'icon'=>'fa-key', 'txt'=>'Đang ở'],
                                'checked_out' => ['bg'=>'#f1f5f9', 'col'=>'#475569', 'icon'=>'fa-house', 'txt'=>'Đã trả phòng'],
                                'cancelled' => ['bg'=>'#fef2f2', 'col'=>'#dc2626', 'icon'=>'fa-ban', 'txt'=>'Đã hủy'],
                            ];
                            $b = $badges[$st] ?? ['bg'=>'#fff', 'col'=>'#000', 'icon'=>'fa-circle', 'txt'=>$st];
                            echo "<span class='status-badge' style='background:{$b['bg']}; color:{$b['col']}; border: 1px solid {$b['col']}20;'>
                                    <i class='fa-solid {$b['icon']}'></i> {$b['txt']}
                                  </span>";
                            ?>
                        </td>

                        <td>
                            <div class="action-group">
                                <a href="invoice.php?id=<?php echo $row['id']; ?>" target="_blank" class="btn-edit btn-icon-only btn-print"><i class="fa-solid fa-print"></i></a>
                                
                                <?php if ($st == 'confirmed'): ?>
                                    <a href="../../handle/booking_process.php?action=check_in&id=<?php echo $row['id']; ?>" class="btn-action-sm bg-checkin" onclick="return confirm('Khách đã đến?');">Check-in</a>
                                <?php endif; ?>

                                <?php if ($st == 'checked_in'): ?>
                                    <a href="../../handle/booking_process.php?action=check_out&id=<?php echo $row['id']; ?>" class="btn-action-sm bg-checkout" onclick="return confirm('Trả phòng?');">Check-out</a>
                                <?php endif; ?>

                                <?php if ($st != 'checked_out' && $st != 'cancelled'): ?>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit btn-icon-only"><i class="fa-solid fa-pen"></i></a>
                                    <a href="../../handle/booking_process.php?action=cancel&id=<?php echo $row['id']; ?>" class="btn-delete btn-icon-only" onclick="return confirm('Hủy đơn?');"><i class="fa-solid fa-trash"></i></a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center; padding:30px;">Chưa có dữ liệu.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php $conn->close(); require_once '../partials/footer.php'; ?>