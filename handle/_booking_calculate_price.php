<?php
// FILE NÀY KHÔNG CHẠY ĐỘC LẬP
// Nó yêu cầu $conn, $room_type_id, $check_in_date, $check_out_date
// Nó cung cấp $total_price

// 1. Lấy giá tiền/đêm
$stmt_price = $conn->prepare("SELECT price_per_night FROM room_types WHERE id = ?");
$stmt_price->bind_param("i", $room_type_id);
$stmt_price->execute();
$price_per_night = $stmt_price->get_result()->fetch_assoc()['price_per_night'];
$stmt_price->close();

// 2. Tính số đêm
$date_in = new DateTime($check_in_date);
$date_out = new DateTime($check_out_date);
$interval = $date_in->diff($date_out);
$number_of_nights = $interval->days;

// 3. Đảm bảo tối thiểu 1 đêm
if ($number_of_nights == 0) {
    $number_of_nights = 1;
}

// 4. Tính tổng tiền
$total_price = $price_per_night * $number_of_nights;
?>