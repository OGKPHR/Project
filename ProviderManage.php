<?php
session_start();
require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_btn'])) {
    $uploadDir = 'provider/'; // Change to the appropriate folder name

    $uploadedFileTmp = $_FILES['uploaded_picture']['tmp_name'];
    $originalFileName = $_FILES['uploaded_picture']['name']; // Get the original filename

    // Get the provider name from the form
    $newProviderName = $_POST['newProviderName'];

    // Sanitize the provider name
    $newProviderName = preg_replace("/[^a-zA-Z0-9]/", "", $newProviderName);

    // Modify the original filename to match the provider name
    $newFileName = $newProviderName . '.' . pathinfo($originalFileName, PATHINFO_EXTENSION);

    if (move_uploaded_file($uploadedFileTmp, $uploadDir . $newFileName)) {
        // Insert the provider and its logo into the database
        $query = "INSERT INTO providers (option_value, providerlogo) VALUES ('$newProviderName', '$newFileName')";

        if ($conn->query($query) === TRUE) {
            echo "Provider and logo uploaded successfully!";
        } else {
            error_log("Error uploading provider and logo: " . $conn->error);
        }
    } else {
        error_log("Error moving uploaded file.");
    }
    mysqli_close($conn);
    header("Location: ProviderManage.php");
    exit;
}

$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);

$providerOptionsQuery = "SELECT * FROM providers";
$providerOptionsResult = mysqli_query($conn, $providerOptionsQuery);
$providerOptions = mysqli_fetch_all($providerOptionsResult, MYSQLI_ASSOC);



// Handle Edit Provider
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editProviderName"])) {
    $editProviderName = $_POST["editProviderName"];
    $editProviderId = $_POST["editProviderId"]; // Retrieve the editProviderId from the form

    $sql = "UPDATE providers SET option_value = '$editProviderName' WHERE id = $editProviderId";

    if (mysqli_query($conn, $sql)) {
        header("Location: ProviderManage.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $deleteProviderId = $_GET['delete'];

    // Check if the provider is referenced by products
    $checkQuery = "SELECT COUNT(*) FROM product WHERE provider_id = $deleteProviderId";
    $checkResult = mysqli_query($conn, $checkQuery);
    $referenceCount = mysqli_fetch_row($checkResult)[0];

    if ($referenceCount > 0) {
        $_SESSION['error_message'] = "ไม่สามารถลบได้เนื่องจากยังมีเบอร์ที่ใช้ผู้บริการนี้อยู่ในคลัง!!!";
    } else {
        // Get the provider logo filename
        $providerLogoQuery = "SELECT providerlogo FROM providers WHERE id = $deleteProviderId";
        $providerLogoResult = mysqli_query($conn, $providerLogoQuery);
        $providerLogoRow = mysqli_fetch_assoc($providerLogoResult);
        $providerLogoFilename = $providerLogoRow['providerlogo'];

        // Delete the provider logo file
        if ($providerLogoFilename) {
            $providerLogoPath = "provider/" . $providerLogoFilename;
            if (file_exists($providerLogoPath)) {
                unlink($providerLogoPath); // Delete the file
            }
        }

        // Delete the provider from the database
        $deleteQuery = "DELETE FROM providers WHERE id = $deleteProviderId";
        mysqli_query($conn, $deleteQuery);

        $_SESSION['success_message'] = "ลบผู้ให้บริการสำเร็จ";
    }

    header("Location: ProviderManage.php");
    exit();
}




$sql = "SELECT * FROM providers";
$result = mysqli_query($conn, $sql);
$providerOptions = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ADMINISTRATION</title>
    <link rel="icon" href="unnamed.png">
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
                                        <h2 style="font-weight: bold;">ระบบจัดการผู้ให้บริการ</h2>
                                    </div>
                                       <?php
                                                if (isset($_SESSION['success_message'])) {
                                                    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                                                    unset($_SESSION['success_message']);
                                                }

                                                if (isset($_SESSION['error_message'])) {
                                                    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                                                    unset($_SESSION['error_message']);
                                                }
                                                ?>
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"
                                        enctype="multipart/form-data">
                                        <!-- Input fields for provider name and logo -->
                                        <input type="text" name="newProviderName" placeholder="New Provider Name"
                                            required>
                                        <input type="file" class="btn-primary" name="uploaded_picture"
                                            accept=".jpg, .jpeg, .png" required>

                                        <!-- Button to submit the form -->
                                        <button type="submit" name="upload_btn" class="btn-success">Upload Provider and
                                            Logo</button>
                                    </form>


                                    <!-- Provider List -->
                                    <table class="table table-bordered dataTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Provider Name</th>
                                                <th>Provider Logo</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($providerOptions as $providerOption): ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $providerOption['id']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $providerOption['option_value']; ?>
                                                    </td>
                                                    <td><img style="max-width: 215px;max-height: 215px;"
                                                            src="provider/<?php echo $providerOption['providerlogo']; ?>"
                                                            class="img-thumbnail"></td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#editProviderModal"
                                                            onclick="setEditProviderId(<?php echo $providerOption['id']; ?>)">
                                                            Edit
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="ProviderManage.php?delete=<?php echo $providerOption['id']; ?>"
                                                            onclick="return confirm('Are you sure you want to delete this provider?')">
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <div class="modal fade" id="editProviderModal" tabindex="-1" role="dialog"
                                        aria-labelledby="editProviderModalLabel" aria-hidden="true">
                                        <!-- Add your modal content here -->
                                        <form method="post">
                                            <input type="hidden" name="editProviderId" id="editProviderId">
                                            <!-- Hidden input for the provider ID -->
                                            <label for="editProviderName">Edit Provider Name:</label>
                                            <input type="text" name="editProviderName" id="editProviderName">
                                            <input type="submit" value="Save Changes"
                                                onclick="return validateEditProviderForm();">

                                        </form>
                                    </div>

                                    <script>
                                        function validateEditProviderForm() {
                                            var editProviderName = document.getElementById("editProviderName").value;

                                            if (editProviderName.trim() === "") {
                                                alert("Provider name cannot be empty.");
                                                return false; // Prevent form submission
                                            }

                                            // Form is valid, allow submission
                                            return true;
                                        }

                                        function setEditProviderId(id) {
                                            document.getElementById("editProviderId").value = id;
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