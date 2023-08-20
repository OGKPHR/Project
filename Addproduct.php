<?php
session_start();
require_once "connection.php";

// Fetch provider options from the 'providers' table
$providerOptionsQuery = "SELECT * FROM providers";
$providerOptionsResult = mysqli_query($conn, $providerOptionsQuery);
$providerOptions = mysqli_fetch_all($providerOptionsResult, MYSQLI_ASSOC);

// Fetch type options from the 'types' table
$typeOptionsQuery = "SELECT * FROM TYPES";
$typeOptionsResult = mysqli_query($conn, $typeOptionsQuery);
$typeOptions = mysqli_fetch_all($typeOptionsResult, MYSQLI_ASSOC);
// Handle product addition
if (isset($_POST['submit'])) {
    $phonenumber = $_POST['phonenumber'];
    $provider = $_POST['Provider'];
    $price = $_POST['price'];
    $Type=$_POST['TYPES'];

    $query = "INSERT INTO product (phonenumber, Provider, Price,TYPES)
            VALUES ('$phonenumber', '$provider', '$price', '$Type')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Data inserted successfully!";
        
        header("Location: Addproduct.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle product deletion
if (isset($_POST['delete'])) {
    if (!empty($_POST['selected_products'])) {
        $selectedProducts = $_POST['selected_products'];
        $ids = implode(',', $selectedProducts);
        $deleteQuery = "DELETE FROM product WHERE id IN ($ids)";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "Selected products deleted successfully!";
        } else {
            echo "Error deleting products: " . mysqli_error($conn);
        }
    }mysqli_close($conn);
    header("Location: Addprodu.php");
    exit;

}
if (isset($_POST['delete2'])) {
    if (!empty($_POST['selected_products'])) {
        $selectedProducts = $_POST['selected_products'];
        $ids = implode(',', $selectedProducts);
        $deleteQuery = "DELETE FROM product WHERE id IN ($ids)";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "Selected products deleted successfully!";
        } else {
            echo "Error deleting products: " . mysqli_error($conn);
        }
    }mysqli_close($conn);
    header("Location: Addprodu.php");
    exit;

}

// Fetch products from the database
$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);

// Count total users in the database
$countQuery = "SELECT COUNT(id) AS total_users FROM user";
$countResult = mysqli_query($conn, $countQuery);
$totalUsers = mysqli_fetch_assoc($countResult)['total_users'];

// Search for users by name or ID
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    $searchQuery = "SELECT * FROM user WHERE id = '$searchTerm' OR username = '$searchTerm' OR CONCAT(firstname, ' ', lastname) LIKE '%$searchTerm%'";
    $searchResult = mysqli_query($conn, $searchQuery);
}


// Handle user deletion
if (isset($_POST['delete_users'])) {
    if (!empty($_POST['selected_users'])) {
        $selectedUsers = $_POST['selected_users'];
        $ids = implode(',', $selectedUsers);
        $deleteQuery = "DELETE FROM user WHERE id IN ($ids)";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "ลบผู้ใช้งานสำเร็จ",
                          type: "success"
                        }, function() {
                            window.location = "Addproduct.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                     }, 1000);
                </script>';
        } else {
            '<script>
                     setTimeout(function() {
                      swal({
                          title: "เกิดข้อผิดพลาด. mysqli_error($conn);",
                          type: "success"
                        }, function() {
                            window.location = "Addproduct.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                     }, 1000);
                </script>';
        }
    }
}

if (isset($_POST['searchnumber'])) {
    // Get the search term from the form
    $searchTerm = $_POST['search_term'];

    // Construct the search query
    $searchQuery = "SELECT * FROM product WHERE phonenumber LIKE '%$searchTerm%' OR Price LIKE '%$searchTerm%'";

    // Execute the query and fetch the results
    $searchnumResult = mysqli_query($conn, $searchQuery);
}
?>

<?php
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
    }mysqli_close($conn);
    header("Location: Addproduct.php");
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
<link rel="icon" href="unnamed.png">
    <title>Product&Users Management</title>
   
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

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
            <li class="nav-item active">
                <a class="nav-link" href="Addproduct.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Product-Management</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="Addproduct.php">
                    <i class="fas-phone"></i>
                    <span>PhonenumberList</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>PhonenumberList</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message 
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components,
                    and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to
                    Pro!</a>
            </div>-->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg" alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg" alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg" alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['fname']; ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">User and Product</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
    <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                               <h2 style="font-weight: bold;">แก้ใขBanner</h2></div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">   <div class="row">
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
                <button type="submit" class="btn btn-danger" onclick="return confirmDelete()">Delete Selected</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="Addproduct.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="uploaded_picture">
                <button type="submit" name="upload_btn" class="btn btn-primary">Upload Picture</button>
            </form>
        </div>
    </div>

    <!-- Include your scripts and other content here -->
    <script>
    function confirmDelete() {
        var selectedCheckboxes = document.querySelectorAll('input[name="selected_files[]"]:checked');
        if (selectedCheckboxes.length === 0) {
            alert("Please select at least one picture to delete.");
            return false;
        }
        return confirm("Are you sure you want to delete the selected pictures?");
    }
    </script></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <h2 style="font-weight: bold;">เพิ่มสินค้า</h2>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                                    <label for="phonenumber">หมายเลขเบอร์โทร</label>
                                                    <input type="text" class="form-control bg-light border-0 small"
                                                        name="phonenumber" placeholder="กรอกเบอร์โทร" required>
                                                    <br>
                                                    <label for="Provider">ผู้ให้บริการ</label>
                                                    <select class="custom-select custom-select-sm form-control form-control-sm" id="Provider" name="Provider" required>
                                                        <?php foreach ($providerOptions as $providerOption): ?>
                                                            <option value="<?php echo $providerOption['option_value']; ?>"><?php echo $providerOption['option_value']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <a href="#" class="fa fa-plus-circle" data-toggle="modal" data-target="#addProviderModal"></a>

                                                    <a href="#" class="fa fa-minus-circle" onclick="deleteSelectedOption('providers')"></a>


                                                      <br>
                                                    <label for="TYPES">ประเภท  </label>
                                                    <select class="custom-select custom-select-sm form-control form-control-sm" id="TYPES" name="TYPES" required>
                                                        <?php foreach ($typeOptions as $typeOption): ?>
                                                            <option value="<?php echo $typeOption['option_value']; ?>"><?php echo $typeOption['option_value']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <br>
                                                    <a href="#" class="fa fa-plus-circle" data-toggle="modal" data-target="#addTypeModal"></a>

                                                    <a href="#" class="fa fa-minus-circle" onclick="deleteSelectedOptionT('types')"></a>

                                                    <br>
                                                    <label for="price">ราคา</label>
                                                    <input type="text" class="form-control bg-light border-0 small" name="price" placeholder="กรอกราคา" required>
                                                    <button class="btn btn-success mt-2" type="submit" name="submit">ยืนยัน</button>
                                                </form>
                                            </div>

                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-donate fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="addProviderModal" tabindex="-1" role="dialog" aria-labelledby="addProviderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProviderModalLabel">Add Provider Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="newProviderOption">New Option:</label>
                    <input type="text" class="form-control" id="newProviderOption" name="newProviderOption" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addNewOption('newProviderOption', 'providers', 'providers')">Add Option</button>
            </div>
        </div>
    </div>
</div>

                        <div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTypeModalLabel">Add Type Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="newTypeOption">New Option:</label>
                    <input type="text" class="form-control" id="newTypeOption" name="newTypeOption" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addNewOption('newTypeOption', 'types')">Add Option</button>
            </div>
        </div>
    </div>
</div>


                        <div style="min-width: fit-content;" class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        <h2>ลบสินค้า</h2>
                    </div>
                
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <!-- Display search form -->
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                    <label for="search_term">ค้นหาด้วยหมายเลขโทรศัพท์หรือราคา</label>
                                    <input type="text" class="form-control bg-light border-0 small" name="search_term" placeholder="ค้นหาเบอร์หรือราคา" required>
                                    <button class="btn btn-success mt-2" type="submit" name="searchnumber">ค้นหา</button>
                                </form>

                               

                          

                            
                             <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="table-responsive">
                                 <!-- Display search results -->
                                 <?php if (isset($searchnumResult)) : ?>
                                    <?php if (mysqli_num_rows($searchnumResult) > 0) : ?>
                                        <h2>ผลการค้นหา</h2>
                                        <table class="table table-bordered dataTable">
                                            <thead>
                                            <tr>
                                                <th>เลือก</th>
                                                <th>เบอร์</th>
                                                <th>ผู้ให้บริการ</th>
                                                <th>ราคา</th>
                                                <th>เสริมด้าน</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($searchnumResult)) : ?>
                                                    <tr>
                                                    <td><input type="checkbox" name="selected_products[]" value="<?php echo $row['id']; ?>">
                                                            </td>
                                                    <td><?php echo $row['phonenumber']; ?></td>
                                                    <td><?php echo $row['Provider']; ?></td>
                                                    <td><?php echo $row['Price']; ?></td>
                                                    <td><?php echo $row['TYPES']; ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                        <button class="btn btn-danger mt-2" type="submit" name="delete2">
                                            ลบสินค้าที่เลือก
                                        </button>
                                    <?php else : ?>
                                        <p>ไม่พบผลการค้นหา</p>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                            </div>
                           
                        </form>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-table fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4" style="min-width: fit-content;">
                        <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <h2 style="font-weight: bold;">ค้นหาผู้ใช้</h2>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <!-- Search users by name or ID -->
                                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                                    <h3>
                                                        
                                                        <div class="usamount">จำนวนผู้ใช้ในระบบ:
                                                            <?php echo $totalUsers; ?>
                                                        </div>
                                                    </h3>

                                                    <label for="search_term">ค้นหาด้วยชื่อหรือรหัสผู้ใช้</label>
                                                    
                                                     <input type="text" class="form-control bg-light border-0 small" name="search_term" placeholder="กรอกชื่อหรือรหัสผู้ใช้" required>
                                                    <button class="btn btn-success mt-2" type="submit" name="search">ค้นหา</button>

                                                </form>
                                               

                                                <!-- Display searched users -->
                                                <?php if (isset($_POST['search'])): ?>

                                                    <?php if (mysqli_num_rows($searchResult) > 0): ?>
                                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                                            <h2>ผลการค้นหาผู้ใช้</h2>
                                                            <table class="table table-bordered dataTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>เลือก</th>
                                                                        <th>ID</th>
                                                                        <th>ชื่อ</th>
                                                                        <th>นามสกุล</th>
                                                                        <th>สถานะ</th>
                                                                        <th>ชื่อผู้ใช้</th>
                                                                        
                                                                        <!-- Add more columns if needed... -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php while ($row = mysqli_fetch_assoc($searchResult)): ?>
                                                                        <tr>
                                                                            <td><input type="checkbox" name="selected_users[]"
                                                                                    value="<?php echo $row['id']; ?>"></td>
                                                                            <td>
                                                                                <?php echo $row['id']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row['firstname']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row['lastname']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo ($row['userlevel'] == 'a') ? 'ADMIN' : (($row['userlevel'] == 'm') ? 'USER' : ''); ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row['username']; ?>
                                                                            </td>
                                                                            

                                                                            <!-- Add more columns if needed... -->
                                                                        </tr>
                                                                    <?php endwhile; ?>
                                                                </tbody>
                                                            </table>
                                                            <button class="btn btn-danger  mt-2" type="submit" name="delete_users">
                                                                ลบผู้ใช้ที่เลือก
                                                            </button>
                                                            

                                                        </form>
                                                        
                                                    <?php else: ?>
                                                        <div class="red">ไม่พบผู้ใช้ที่ค้นหา</div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                        <div class="col-auto">
                                                <i class="fas fa-search fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example 
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                        <!-- Earnings (Monthly) Card Example 
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                        <!-- Pending Requests Card Example -->
               


                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                                </div>
                                <div class="card-body">
                                    <h4 class="small font-weight-bold">Server Migration <span
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Sales Tracking <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Customer Database <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Payout Details <span
                                            class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account Setup <span
                                            class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Color System -->
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-primary text-white shadow">
                                        <div class="card-body">
                                            Primary
                                            <div class="text-white-50 small">#4e73df</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-success text-white shadow">
                                        <div class="card-body">
                                            Success
                                            <div class="text-white-50 small">#1cc88a</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-info text-white shadow">
                                        <div class="card-body">
                                            Info
                                            <div class="text-white-50 small">#36b9cc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-warning text-white shadow">
                                        <div class="card-body">
                                            Warning
                                            <div class="text-white-50 small">#f6c23e</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-danger text-white shadow">
                                        <div class="card-body">
                                            Danger
                                            <div class="text-white-50 small">#e74a3b</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-secondary text-white shadow">
                                        <div class="card-body">
                                            Secondary
                                            <div class="text-white-50 small">#858796</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-light text-black shadow">
                                        <div class="card-body">
                                            Light
                                            <div class="text-black-50 small">#f8f9fc</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="card bg-dark text-white shadow">
                                        <div class="card-body">
                                            Dark
                                            <div class="text-white-50 small">#5a5c69</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Illustrations</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                            src="img/undraw_posting_photo.svg" alt="...">
                                    </div>
                                    <p>Add some quality, svg illustrations to your project courtesy of <a
                                            target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                                        constantly updated collection of beautiful svg images that you can use
                                        completely free and without attribution!</p>
                                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                                        unDraw &rarr;</a>
                                </div>
                            </div>

                            <!-- Approach -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                                </div>
                                <div class="card-body">
                                    <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                                        CSS bloat and poor page performance. Custom CSS classes are used to create
                                        custom components and custom utility classes.</p>
                                    <p class="mb-0">Before working with this theme, you should become familiar with the
                                        Bootstrap framework, especially the utility classes.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
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
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="aaw.js"></script>
   <!-- Bootstrap core JavaScript-->
   <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>