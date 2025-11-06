<form class="form-layout" action="../../handle/customer_process.php" method="POST">
    
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">

    <button type="submit">Lưu thay đổi</button>
</form>