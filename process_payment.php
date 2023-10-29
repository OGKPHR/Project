

<?php
session_start();

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
    <h2>เพิ่มช่องทางการชำระเงิน</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="form-group">
            <label for="paymethod">Payment Method:</label>
            <input type="text" class="form-control" id="paymethod" name="paymethod" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <?php
    require_once('connection.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paymethod'])) {
        $paymethod = mysqli_real_escape_string($conn, $_POST['paymethod']);

        $insertQuery = "INSERT INTO payment (Paymethod) VALUES ('$paymethod')";
        if (mysqli_query($conn, $insertQuery)) {
            echo "<p>Payment method added successfully.</p>";
        } else {
            echo "<p>Error adding payment method: " . mysqli_error($conn) . "</p>";
        }
    }

    // Check if the form is submitted to edit a payment method
    if (isset($_GET['edit'])) {
        $editId = $_GET['edit'];
        $editQuery = "SELECT * FROM payment WHERE id = $editId";
        $editResult = mysqli_query($conn, $editQuery);
        if ($editResult && mysqli_num_rows($editResult) > 0) {
            $editRow = mysqli_fetch_assoc($editResult);
            $editPaymethod = $editRow['Paymethod'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edited_paymethod'])) {
            $editedPaymethod = mysqli_real_escape_string($conn, $_POST['edited_paymethod']);
            $updateQuery = "UPDATE payment SET Paymethod = '$editedPaymethod' WHERE id = $editId";
            if (mysqli_query($conn, $updateQuery)) {
               
                echo "<p>Payment method updated successfully.</p>";

                $editPaymethod = $editedPaymethod; // Update the displayed value
                
                
            } else {
                echo "<p>Error updating payment method: " . mysqli_error($conn) . "</p>";
            }
          
        }
       
        echo '<h3>Edit Payment Method</h3>';
        echo '<form method="post" action="?edit=' . $editId . '">';
        echo '<div class="form-group">';
        echo '<input type="text" class="form-control" name="edited_paymethod" value="' . $editPaymethod . '" required>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Save</button>';
        echo '</form>';
    }

    mysqli_close($conn);
    ?>
</div>
<div class="col-md-6">
    <h2>All Payment Methods</h2>
    <?php
    // Assuming you have a connection to the database
    require('connection.php');
    // Fetch all payment methods
    $result = mysqli_query($conn, "SELECT * FROM payment");
    // Check if the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the form is submitted to delete a payment method
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];
        $deleteQuery = "DELETE FROM payment WHERE id = $deleteId";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "<p>Payment method deleted successfully.</p>";
           
            exit();
        } else {
            echo "<p>Error deleting payment method: " . mysqli_error($conn) . "</p>";
        }
    }

    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table table-bordered" style="min-width:500px;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Payment Method</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row["Paymethod"] . '</td>';
            echo '<td><a href="?edit=' . $row["id"] . '">Edit</a> | <a href="?delete=' . $row["id"] . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "No payment methods found.";
    }

    // Close the connection
    mysqli_close($conn);
    ?>
</div>




            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

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

</body>

</html>
