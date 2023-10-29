<?php
session_start();
require_once "connection.php";

// Fetch user data from the database and store it in the $users array
$users = array();
$query = "SELECT * FROM user";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
} else {
    echo "Error fetching user data: " . mysqli_error($conn);
}
// Count total users in the database
$countQuery = "SELECT COUNT(id) AS total_users FROM user";
$countResult = mysqli_query($conn, $countQuery);
$totalUsers = mysqli_fetch_assoc($countResult)['total_users'];

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
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4" style="min-width: fit-content;">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <h2 style="font-weight: bold;">ค้นหาผู้ใช้</h2> 
                                        <br>
                                        <h6 style="font-weight: bold;text-align: right;">ผู้ใช้ในระบบบทั้งหมด: <?php echo $totalUsers ?></h6>
                                    </div>
                                    <div id="noResults" class="alert alert-warning" style="display: none;">
                                    No matching users found.
                                </div>
                                    <input type="text" class="form-control" placeholder="Search for users..."
                                        aria-label="Search" aria-describedby="basic-addon2" id="searchInput"
                                        onkeyup="searchUsers()">
                                   

                                    <table id="userTable" class="table table-bordered dataTable">
                                        <thead>
                                            <tr style="text-align: center;"> 
                                                <th>ID</th>
                                                <th>Username</th>
                                                <!--<th>Password (Hashed)</th>-->
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>User Level</th>
                                                <th>Edit</th>
                                                <th>Delete</th> <!-- Add this column for delete button -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM user";
                                            $result = mysqli_query($conn, $sql);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr style='text-align: center;'>";
                                                echo "<td>{$row['id']}</td>";
                                                echo "<td>{$row['username']}</td>";
                                                //echo "<td>{$row['password']}</td>"; // Display hashed password
                                                echo "<td>{$row['firstname']}</td>";
                                                echo "<td>{$row['lastname']}</td>";
                                                echo "<td>" . ($row['userlevel'] == 'a' ? 'Admin' : ($row['userlevel'] == 'm' ? 'Member' : '')) . "</td>";
                                                echo "<td><a href='edit_user.php?id={$row['id']}'>Edit</a></td>";
                                                echo "<td><a href='delete_user.php?id={$row['id']}'>Delete</a></td>";
                                                echo "</tr>";
                                            }
                                            ?>

                                        </tbody>
                                    </table>

                                    <script>
                                        function searchUsers() {
                                            var input = document.getElementById("searchInput");
                                            var filter = input.value.toUpperCase();
                                            var table = document.getElementById("userTable");
                                            var rows = table.getElementsByTagName("tr");

                                            var found = false; // Flag to check if any user was found

                                            for (var i = 1; i < rows.length; i++) { // Start from index 1 to skip header row
                                                var cells = rows[i].getElementsByTagName("td");
                                                var shouldDisplay = false;

                                                for (var j = 0; j < cells.length; j++) {
                                                    var cell = cells[j];
                                                    if (cell) {
                                                        var cellText = cell.textContent || cell.innerText;
                                                        if (cellText.toUpperCase().indexOf(filter) > -1) {
                                                            shouldDisplay = true;
                                                            found = true; // User found, set the flag to true
                                                            break;
                                                        }
                                                    }
                                                }

                                                rows[i].style.display = shouldDisplay ? "" : "none";
                                            }

                                            // Show/hide the "Not Found" message based on the flag
                                            var noResults = document.getElementById("noResults");
                                            noResults.style.display = found ? "none" : "block";
                                        }
                                    </script>


                                    <div class="col-auto">
                                        <div class="col-auto">
                                            <i class="fas fa-search fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
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