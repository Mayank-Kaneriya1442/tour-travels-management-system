<?php
include('../config/db.php');
if(isset($_POST['code'])) {
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $res = mysqli_query($conn, "SELECT discount_percent FROM coupons WHERE code='$code' AND status=1");
    if($row = mysqli_fetch_assoc($res)) {
        echo $row['discount_percent'];
    } else {
        echo "invalid";
    }
}
?>