<?php
require_once('connection.php');

if (isset($_POST['submit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $optionValue = mysqli_real_escape_string($conn, $_POST['parcelcode']);

    $sql = "UPDATE parcel_code SET 
             parcel_code_id = '$optionValue'
             WHERE parcel_code_id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header('Location: percelcode.php');
        exit();
    } else {
        echo '<script> alert("แก้ไขข้อมูลไม่สำเร็จ")</script>';
        header('Refresh:0; url= ../form-updateparcel.php');
        exit();
    }
}

mysqli_close($conn);
?>