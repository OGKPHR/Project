
<?php
session_start();
require_once "connection.php";

$order = "SELECT * FROM order_table";
$orderResult = mysqli_query($conn, $order);

if (isset($_POST['submitOrder'])) {
    $orderId = $_POST['orderID'];
    $percelcode = $_POST['percelcode'];
    $query = "INSERT INTO parcel_code (order_id, parcel_code_id) VALUES ('$orderId', '$percelcode')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "บันทึกข้อมูลเรียบร้อย";
        header("refresh:1;url=parcelManage.php");
    } else {
        echo "ไม่สามารถบันทึกข้อมูลได้";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    </head>
<style>
    th{white-space: nowrap;}
</style>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ADMINISTRATION</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
              <!-- Divider -->
              <hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<?php include('menuli.php') ?>
     </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
             
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4" style="min-width: fit-content;">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <h2 style="font-weight: bold;">ระบบจัดการผู้ให้บริการ</h2>
                                    </div>
                                    <div class="container-fluid">

                                    <div class="col-md-6">
                                    <h2>รายการสินค้าที่ต้องจัดส่ง</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="white-space: nowrap;">ออเดอร์ที่</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>สินค้า</th>
                    <th>วันที่</th>
                    <th>ที่อยู่จัดส่ง</th>
                    <th>เบอร์โทร</th>
                    <th>กรอกรหัสพัสดุ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                    $orderId = $orderRow['id'];
                    // Check if the order already has a parcel code in the database
                    $checkQuery = "SELECT * FROM parcel_code WHERE order_id = '$orderId'";
                    $checkResult = mysqli_query($conn, $checkQuery);

                    if (mysqli_num_rows($checkResult) === 0) {
                        // Order does not have a parcel code, display the form
                        $user_id = $orderRow['user_id'];
                        $user_query = "SELECT  	firstname,lastname FROM user WHERE id = '$user_id'";
                        $user_result = mysqli_query($conn, $user_query);
                        $user_row = mysqli_fetch_assoc($user_result);

                        $order_id = $orderRow['id'];
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

                        $address_id = $orderRow['address_id'];
                        $address_query = "SELECT  	houseno 	,villageno 	,subdistrict 	,district 	,province ,	postcode 	 FROM location WHERE id = '$address_id'";
                        $address_result = mysqli_query($conn, $address_query);
                        $address_row = mysqli_fetch_assoc($address_result);

                        $phone_id = $orderRow['phone_number_id'];
                        $phone_query = "SELECT phone_number FROM user_phonenumbers WHERE  phone_number = '$phone_id'";
                        $phone_result = mysqli_query($conn, $phone_query);
                        $phone_row = mysqli_fetch_assoc($phone_result);
                        ?>
                        <tr>
                            <td>
                                <?php echo $orderRow['id']; ?>
                            </td>
                            <td style="white-space: nowrap;"><?php echo $user_row['firstname']." ".$user_row['lastname']; ?></td>

                            <td>
                                <?php echo implode(', ', $products); ?>
                            </td>
                            <td>
                                <?php echo $orderRow['date']; ?>
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
                                รหัสไปรษณีย์:
                                <?php echo $address_row['postcode']; ?>
                            </td>
                            <td>
                                <?php echo $phone_row['phone_number']; ?>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="orderID" value="<?php echo $orderRow['id']; ?>">
                                    <label for="percelcode">Parcel Code:</label>
                                    <input type="text" name="percelcode" id="percelcode">
                                    <button class="btn btn-primary" type="submit" name="submitOrder">Add Parcel</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php include('editparcelled.php'); ?>
</div>
</div>
</div></div></div>
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
<!-- Footer -->
<footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
</body>

</html>
