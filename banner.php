<?php
session_start();
require_once "connection.php";
// Set the timezone to GMT+7
date_default_timezone_set('Asia/Bangkok');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_btn'])) {
    $uploadDir = 'fileupload/';

    $uploadedFileTmp = $_FILES['uploaded_picture']['tmp_name'];
    $originalFileName = $_FILES['uploaded_picture']['name']; // Get the original filename

    // Get the file extension
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    // Generate a new name for the uploaded picture based on current date and time
    $currentDateTime = date('dmY-His'); // Format: ddmmyy-hhmmss
    $newFileName = $currentDateTime . '_' . $originalFileName; // Combine date-time and original filename

    if (move_uploaded_file($uploadedFileTmp, $uploadDir . $newFileName)) {
        $query = "INSERT INTO uploadfile (fileupload) VALUES ('$newFileName')";

        if ($conn->query($query) === TRUE) {
            echo "Picture uploaded successfully!";
        } else {
            echo "Error uploading picture: " . $conn->error;
        }
    }
    mysqli_close($conn);
    header("Location: banner.php");
    exit;
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

               
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                     <!-- Earnings (Monthly) Card Example -->
                     <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                <h2 style="font-weight: bold;">แก้ใขBanner</h2>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form action="delete_selected.php" method="POST">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Picture</th>
                                                                        <th>Select</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    require_once "connection.php";

                                                                    $query = "SELECT * FROM uploadfile";
                                                                    $result = $conn->query($query);

                                                                    while ($row = $result->fetch_assoc()) {
                                                                        echo '<tr>';
                                                                        echo '<td><img src="fileupload/' . $row['fileupload'] . '?' . time() . '" class="img-thumbnail"></td>';
                                                                        echo '<td><input type="checkbox" name="selected_files[]" value="' . $row['fileupload'] . '"></td>';
                                                                        echo '</tr>';
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirmDelete()">ลบรูปที่เลือก</button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form action="banner.php" method="POST"
                                                            enctype="multipart/form-data">
                                                            <input type="file" name="uploaded_picture">
                                                            <button type="submit" name="upload_btn"
                                                                class="btn btn-primary">อัพโหลดรูป</button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <!-- Include your scripts and other content here -->
                                                <script>
                                                    function confirmDelete() {
                                                        var selectedCheckboxes = document.querySelectorAll('input[name="selected_files[]"]:checked');
                                                        if (selectedCheckboxes.length === 0) {
                                                            alert("กรุณาเลือกรูปก่อนลบ");
                                                            return false;
                                                        }
                                                        return confirm("แน่ใจใช่ไหมว่าจะลบ?");
                                                    }   
                                                </script>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


            </div>
            </div>
            <!-- End of Main Content -->

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
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

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