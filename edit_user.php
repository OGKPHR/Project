<?php
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $username = $_POST["username"];
    $password = $_POST["password"]; // Plain-text password
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $userlevel = $_POST["userlevel"];

    // Hash the password before storing in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE user SET username='$username', password='$hashedPassword', firstname='$firstname', lastname='$lastname', userlevel='$userlevel' WHERE id=$id";
    mysqli_query($conn, $sql);

    // Redirect back to user list
    header("Location: UserManage.php");
    exit();
} else {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT * FROM user WHERE id=$id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
        } else {
            echo "User not found.";
            exit();
        }
    } else {
        echo "Invalid user ID.";
        exit();
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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

<div id="wrapper">

    <!-- ... Sidebar and Navigation code (SB Admin 2) ... -->

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- ... Topbar and Navbar code (SB Admin 2) ... -->

            <div class="container-fluid">

                <h1 class="h3 mb-4 text-gray-800">Edit User</h1>

                <form class="user" method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="username"
                               placeholder="Username" value="<?php echo $user['username']; ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-user" name="password"
                               placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="firstname"
                               placeholder="First Name" value="<?php echo $user['firstname']; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="lastname"
                               placeholder="Last Name" value="<?php echo $user['lastname']; ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="userlevel"
                               placeholder="User Level" value="<?php echo $user['userlevel']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Save
                    </button>
                </form>

            </div>

        </div>

        <!-- ... Footer code (SB Admin 2) ... -->
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
