<?php

session_start();
if (isset($_SESSION['success'])) {
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}


// Check if an error message is set and display it
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login Page</title>
    <link rel="icon" href="unnamed.png">
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<style>
body{      background-color:  #111;
}

</style>
<body >
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0" style="height: 500px;border: solid 6px gold;">
                        <!-- Nested Row within Card Body -->
                        <div class="row"style="padding-top: 60px;">
                            <div class="col-lg-6 d-none d-lg-block" style="margin: auto;"><img style="width: inherit;margin:auto;" src="ICON\LOGO.png" alt=""></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center "style="background-color:bl;">
                                        <h1 class="h4 text-gray-900 mb-4">เข้าสู่ระบบ</h1>
                                    </div>
                                    <form action="login.php" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" placeholder="กรอกชื่อผู้ใช้" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="กรอกรหัสผ่าน" required>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" type="submit" name="submit">เข้าสู่ระบบ</button>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="resetpass.php">ลืมรหัสผ่าน?</a>
                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="register.php">สมัครสมาชิก!</a>
                                        </div>
                                        <!-- Display the error message -->
                                        <?php if (!empty($error_message)) : ?>
                                            <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages -->
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
