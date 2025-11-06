<?php
// 1. NGƯỜI GÁC CỔNG, KẾT NỐI, HEADER
require_once '../functions/auth_check.php';
require_once '../functions/db_connection.php';
require_once 'partials/header.php'; // Chỉ gọi 1 lần

// --- 2. BẮT ĐẦU VÙNG NỘI DUNG CHÍNH ---
// Đây là thẻ div quan trọng để đẩy footer xuống
echo '<div class="main-content">'; 

// --- 3. LẤY DỮ LIỆU THỐNG KÊ ---
$total_rooms = $conn->query("SELECT COUNT(id) AS total FROM rooms")->fetch_assoc()['total'];
$total_occupied = $conn->query("SELECT COUNT(id) AS total FROM rooms WHERE status = 'occupied'")->fetch_assoc()['total'];
$total_available = $conn->query("SELECT COUNT(id) AS total FROM rooms WHERE status = 'available'")->fetch_assoc()['total'];
$total_cleaning = $conn->query("SELECT COUNT(id) AS total FROM rooms WHERE status = 'cleaning'")->fetch_assoc()['total'];

// Dữ liệu cho Biểu đồ Cột (7 ngày qua)
$sql_bookings_chart = "SELECT 
                            DATE(created_at) AS ngay, 
                            COUNT(id) AS so_luong 
                       FROM bookings 
                       WHERE created_at >= CURDATE() - INTERVAL 7 DAY 
                       GROUP BY DATE(created_at) 
                       ORDER BY ngay ASC";
$result_bookings_chart = $conn->query($sql_bookings_chart);

$booking_labels = [];
$booking_data = [];
while ($row = $result_bookings_chart->fetch_assoc()) {
    $booking_labels[] = $row['ngay'];
    $booking_data[] = $row['so_luong'];
}
?>

<!-- 4. HIỂN THỊ HTML NỘI DUNG TRANG -->
<div class="page-title">
    <h2>Tổng quan</h2>
</div>

<div class="dashboard-stats-grid">
    <div class="stat-box box-total">
        <h3>Tổng số phòng</h3>
        <p><?php echo $total_rooms; ?></p>
    </div>
    <div class="stat-box box-occupied">
        <h3>Đang có khách</h3>
        <p><?php echo $total_occupied; ?></p>
    </div>
    <div class="stat-box box-available">
        <h3>Có sẵn</h3>
        <p><?php echo $total_available; ?></p>
    </div>
    <div class="stat-box box-cleaning">
        <h3>Đang dọn</h3>
        <p><?php echo $total_cleaning; ?></p>
    </div>
</div>

<!-- Vùng chứa biểu đồ -->
<div class="dashboard-charts">
    
    <div class="chart-container">
        <h3>Tình trạng Phòng</h3>
        <canvas id="roomStatusChart"></canvas>
    </div>
    
    <div class="chart-container">
        <h3>Lượt đặt phòng (7 ngày qua)</h3>
        <canvas id="bookingActivityChart"></canvas>
    </div>

</div>


<!-- 5. JAVASCRIPT CHO BIỂU ĐỒ -->
<!-- (Lưu ý: Thư viện Chart.js phải được nhúng ở header.php) -->
<script>
    // Đảm bảo code này chạy sau khi DOM đã tải
    document.addEventListener("DOMContentLoaded", function() {

        // --- DỮ LIỆU "CẦU NỐI" TỪ PHP SANG JAVASCRIPT ---
        
        // 1. Dữ liệu cho Biểu đồ Tròn (Tình trạng phòng)
        const roomStatusData = {
            labels: ['Đang có khách', 'Có sẵn', 'Đang dọn'],
            data: [
                <?php echo $total_occupied; ?>, 
                <?php echo $total_available; ?>, 
                <?php echo $total_cleaning; ?>
            ]
        };
        
        // 2. Dữ liệu cho Biểu đồ Cột (Lượt đặt phòng)
        const bookingActivityData = {
            labels: <?php echo json_encode($booking_labels); ?>,
            datasets: [{
                label: 'Số lượt đặt phòng mới',
                data: <?php echo json_encode($booking_data); ?>,
                backgroundColor: 'rgba(44, 62, 80, 0.8)', // Màu xanh đậm
                borderColor: 'rgba(44, 62, 80, 1)',
                borderWidth: 1
            }]
        };
        
        // --- VẼ BIỂU ĐỒ ---
        
        // 1. Vẽ Biểu đồ Tròn
        const ctxRoom = document.getElementById('roomStatusChart');
        if (ctxRoom) { // Kiểm tra canvas tồn tại
            const roomStatusChart = new Chart(ctxRoom.getContext('2d'), {
                type: 'doughnut', 
                data: {
                    labels: roomStatusData.labels,
                    datasets: [{
                        label: 'Tình trạng Phòng',
                        data: roomStatusData.data,
                        backgroundColor: [
                            '#e74c3c', // Đỏ (Có khách)
                            '#27ae60', // Xanh lá (Có sẵn)
                            '#f39c12'  // Cam (Đang dọn)
                        ],
                        hoverOffset: 4
                    }]
                }
            });
        }

        // 2. Vẽ Biểu đồ Cột
        const ctxBooking = document.getElementById('bookingActivityChart');
        if (ctxBooking) { // Kiểm tra canvas tồn tại
            const bookingActivityChart = new Chart(ctxBooking.getContext('2d'), {
                type: 'bar', 
                data: bookingActivityData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Chỉ hiển thị số nguyên
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Ẩn label
                        }
                    }
                }
            });
        }
    });
</script>

<?php
// --- 6. KẾT THÚC VÙNG NỘI DUNG CHÍNH ---
echo '</div>'; // Đóng thẻ .main-content

// --- 7. ĐÓNG KẾT NỐI VÀ NHÚNG FOOTER ---
$conn->close();
require_once 'partials/footer.php'; // Chỉ gọi 1 lần
?>