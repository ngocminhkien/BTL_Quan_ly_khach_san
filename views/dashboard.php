<?php
require_once '../functions/auth_check.php';
require_once '../functions/db_connection.php';
require_once 'partials/header.php'; 

// --- LẤY SỐ LIỆU ---
$total_rooms = $conn->query("SELECT COUNT(id) AS total FROM rooms")->fetch_assoc()['total'];
$total_occupied = $conn->query("SELECT COUNT(id) AS total FROM rooms WHERE status = 'occupied'")->fetch_assoc()['total'];
$total_available = $conn->query("SELECT COUNT(id) AS total FROM rooms WHERE status = 'available'")->fetch_assoc()['total'];
$total_cleaning = $conn->query("SELECT COUNT(id) AS total FROM rooms WHERE status = 'cleaning'")->fetch_assoc()['total'];

// --- SỐ LIỆU BIỂU ĐỒ ---
$sql_bookings = "SELECT DATE(created_at) AS ngay, COUNT(id) AS so_luong FROM bookings WHERE created_at >= CURDATE() - INTERVAL 7 DAY GROUP BY DATE(created_at) ORDER BY ngay ASC";
$result_chart = $conn->query($sql_bookings);
$labels = []; $data = [];
while ($row = $result_chart->fetch_assoc()) {
    $labels[] = date('d/m', strtotime($row['ngay']));
    $data[] = $row['so_luong'];
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">

    <div class="welcome-banner">
        <div class="welcome-text">
            <h2>Xin chào, <?php echo htmlspecialchars($_SESSION['user_username']); ?>! 🎉</h2>
            <p>Chúc bạn một ngày làm việc năng suất.</p>
        </div>
        <div class="welcome-img">
            <i class="fa-solid fa-hotel"></i>
        </div>
    </div>

    <div class="dashboard-stats-grid">
        <div class="stat-box box-total">
            <div class="stat-icon"><i class="fa-solid fa-building"></i></div>
            <div class="stat-info">
                <h3>Tổng số phòng</h3>
                <p class="stat-number"><?php echo $total_rooms; ?></p>
            </div>
        </div>
        
        <div class="stat-box box-occupied">
            <div class="stat-icon"><i class="fa-solid fa-bed"></i></div>
            <div class="stat-info">
                <h3>Đang có khách</h3>
                <p class="stat-number"><?php echo $total_occupied; ?></p>
            </div>
        </div>

        <div class="stat-box box-available">
            <div class="stat-icon"><i class="fa-solid fa-key"></i></div>
            <div class="stat-info">
                <h3>Phòng trống</h3>
                <p class="stat-number"><?php echo $total_available; ?></p>
            </div>
        </div>

        <div class="stat-box box-cleaning">
            <div class="stat-icon"><i class="fa-solid fa-broom"></i></div>
            <div class="stat-info">
                <h3>Đang dọn dẹp</h3>
                <p class="stat-number"><?php echo $total_cleaning; ?></p>
            </div>
        </div>
    </div>

    <div class="dashboard-charts">
        <div class="chart-container">
            <h3><i class="fa-solid fa-chart-pie" style="color:#764ba2"></i> Tình trạng Phòng</h3>
            <div class="chart-body">
                <canvas id="roomStatusChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <h3><i class="fa-solid fa-chart-simple" style="color:#667eea"></i> Đặt phòng 7 ngày qua</h3>
            <div class="chart-body">
                <canvas id="bookingActivityChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Chart 1: Doughnut (Màu pastel đẹp)
    new Chart(document.getElementById('roomStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Có khách', 'Trống', 'Đang dọn'],
            datasets: [{
                data: [<?php echo $total_occupied; ?>, <?php echo $total_available; ?>, <?php echo $total_cleaning; ?>],
                backgroundColor: ['#ff9a9e', '#4facfe', '#f6d365'], // Màu giống icon
                borderWidth: 0,
                hoverOffset: 5
            }]
        },
        options: { maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } } }
    });

    // Chart 2: Bar (Màu tím xanh)
    new Chart(document.getElementById('bookingActivityChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Lượt đặt',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: '#667eea',
                borderRadius: 8, // Bo tròn cột
                barThickness: 30
            }]
        },
        options: { 
            maintainAspectRatio: false, 
            scales: { 
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>

<?php
$conn->close();
require_once 'partials/footer.php';
?>