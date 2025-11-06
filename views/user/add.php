<?php
require_once '../../functions/admin_check.php';
require_once '../partials/header.php';
?>

<div class="page-title">
    <h2>Thêm người dùng mới</h2>
</div>

<form class="form-layout" action="../../handle/user_process.php" method="POST">
    <input type="hidden" name="action" value="add">
    
    <div class="form-group">
        <button type="submit" class="btn-submit">Lưu người dùng</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>
</form>

<?php
require_once '../partials/footer.php';
?>