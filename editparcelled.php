<?php


// connection.php: ไฟล์เชื่อมต่อกับฐานข้อมูล
include("connection.php");

$query = "SELECT * FROM order_table WHERE payment_channel_id = 2  AND id IN (SELECT order_id FROM parcel_code)";
$result = mysqli_query($conn, $query);

if (isset($_POST['cancel'])) {
    $orderId = $_POST['orderID'];
    $updateStatusQuery = "UPDATE order_table SET status_id = 3 WHERE id = $orderId";
    mysqli_query($conn, $updateStatusQuery);
    header("Location: editparcelled.php"); // Redirect to the appropriate page after canceling the order
    exit();
}
if (isset($_POST['search'])) {
    $searchKeyword = $_POST['searchKeyword'];
    $query = "SELECT ot.*,
                     u.firstname AS firstname,
                     u.lastname AS lastname,
                     l.houseno AS houseno,
                     l.villageno AS villageno,
                     l.subdistrict AS subdistrict,
                     l.district AS district,
                     l.province AS province
              FROM order_table AS ot
              LEFT JOIN user AS u ON ot.user_id = u.id
              LEFT JOIN location AS l ON ot.address_id = l.id
              WHERE ot.payment_channel_id = 2  
              AND ot.id IN (SELECT order_id FROM parcel_code)
              AND (
                ot.id LIKE '%$searchKeyword%' OR
                u.firstname LIKE '%$searchKeyword%' OR
                u.lastname LIKE '%$searchKeyword%' OR
                ot.date LIKE '%$searchKeyword%' OR
                l.houseno LIKE '%$searchKeyword%' OR
                l.villageno LIKE '%$searchKeyword%' OR
                l.subdistrict LIKE '%$searchKeyword%' OR
                l.district LIKE '%$searchKeyword%' OR
                l.province LIKE '%$searchKeyword%'
              )";
    $result = mysqli_query($conn, $query);
}

?>


<style>
    th{white-space: nowrap;}
</style>


    <!-- ส่วนของตารางแสดงรายการสินค้าที่ต้องจัดส่ง -->
    <div class="container mt-5">
        <h2>Search Parcels</h2>
        <!-- Search input and button -->
        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" id="searchKeyword" name="searchKeyword" placeholder="Enter a keyword to search">
            </div>
            <button class="btn btn-primary" type="submit" name="search">Search</button>
        </form>
    </div>

    <div class="container mt-3">
        <h2>รายการสินค้าที่ต้องจัดส่ง</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ออเดอร์ที่</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>สินค้า</th>
                    <th>วันที่</th>
                    <th>ที่อยู่จัดส่ง</th>
                    <th>เบอร์โทร</th>
                    <th>รหัสพัสดุ</th>
                    <th>ยกเลิกพัสดุ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $orderId = $row['id'];
                    $orderQuery = "SELECT * FROM parcel_code WHERE order_id = '$orderId'";
                    $orderResult = mysqli_query($conn, $orderQuery);
                    $orderRow = mysqli_fetch_assoc($orderResult);

                    $parcelCode = $row['id'];
                    $parcel_query = "SELECT * FROM parcel_code WHERE order_id = '$parcelCode'";
                    $parcelResult = mysqli_query($conn, $parcel_query);
                    $parcelRow = mysqli_fetch_assoc($parcelResult);

                    $user_id = $row['user_id'];
                    $user_query = "SELECT * FROM user WHERE id = '$user_id'";
                    $user_result = mysqli_query($conn, $user_query);
                    $user_row = mysqli_fetch_assoc($user_result);

                    $order_id = $row['id'];
                    $orderItem = "SELECT product_id FROM order_item WHERE order_id = '$order_id'";
                    $orderItemResult = mysqli_query($conn, $orderItem);

                    $products = [];
                    while ($orderItemData = mysqli_fetch_assoc($orderItemResult)) {
                        $product_id = $orderItemData['product_id'];
                        $product_query = "SELECT phonenumber FROM product WHERE id = '$product_id'";
                        $product_result = mysqli_query($conn, $product_query);
                        $product_row = mysqli_fetch_assoc($product_result);
                        $products[] = $product_row['phonenumber'];
                    }

                    $address_id = $row['address_id'];
                    $address_query = "SELECT * FROM location WHERE id = '$address_id'";
                    $address_result = mysqli_query($conn, $address_query);
                    $address_row = mysqli_fetch_assoc($address_result);

                    $phone_id = $row['phone_number_id'];
                    $phone_query = "SELECT phone_number FROM user_phonenumbers WHERE phone_number= '$phone_id'";
                    $phone_result = mysqli_query($conn, $phone_query);
                    $phone_row = mysqli_fetch_assoc($phone_result);
                    ?>
                    <tr>
                        <td>
                            <?php echo $row['id']; ?>
                        </td>
                        <td>
                            <?php echo $user_row['firstname']."  " ."  ".$user_row['lastname']; ?>
                        </td>
                        <td>
                            <?php echo implode(', ', $products); ?>
                        </td>
                        <td>
                            <?php echo $row['date']; ?>
                        </td>
                        <td>
                            บ้านเลขที่:
                            <?php echo $address_row['houseno']; ?><br>
                            หมู่:
                            <?php echo $address_row['villageno']; ?><br>
                            ตำบล:
                            <?php echo $address_row['subdistrict']; ?><br>
                            อำเภอ:
                            <?php echo $address_row['district']; ?><br>
                            จังหวัด:
                            <?php echo $address_row['province']; ?>
                        </td>
                        <td>
                            <?php echo $phone_row['phone_number']; ?>
                        </td>
                        <td>
                            <?php echo $parcelRow['parcel_code_id']; ?>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="orderID" value="<?php echo $row['id']; ?>">
                                <button class="btn btn-primary" type="submit" name="cancel">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

   
    <!-- Include Bootstrap JS and dependencies if needed -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


</html>