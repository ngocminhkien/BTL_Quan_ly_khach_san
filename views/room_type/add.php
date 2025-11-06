<?php
require_once '../../functions/admin_check.php';
require_once '../partials/header.php';
?>

<div class="page-title">
    <h2>Thêm Loại phòng mới</h2>
</div>

<form class="form-layout" action="../../handle/room_type_process.php" method="POST">
    
    <input type="hidden" name="action" value="add">

    <div class="form-group">
        <label for="type_name">Tên loại phòng:</label>
        <input type="text" id="type_name" name="type_name" required>
    </div>
    
    <div class="form-group">
        <label for="price_per_night">Giá (VND/đêm):</label>
        <input type="number" id="price_per_night" name="price_per_night" required>
    </div>

    <div class="form-group">
        <label for="description">Mô tả:</label>
        <input type="text" id="description" name="description">
    </div>

    <div class="form-group">
        <button type="submit" class="btn-submit">Lưu</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>

</form>

<?php
require_once '../partials/footer.php';
?>