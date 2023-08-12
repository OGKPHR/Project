<?php 
    
    session_start();

    require_once "connection.php";

    if (isset($_POST['submit'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];

        $user_check = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $user_check);
        $user = mysqli_fetch_assoc($result);

        if ($user['username'] === $username) {
            echo "<script>alert('Username already exists');</script>";
        } else {
            $passwordenc = md5($password);

            $query = "INSERT INTO user (username, password, firstname, lastname, userlevel)
                        VALUE ('$username', '$passwordenc', '$firstname', '$lastname', 'm')";
            $result = mysqli_query($conn, $query);

            if ($result) {
             // $_SESSION['success'] = "Insert user successfully";
                header("Location: index.php");
            } else {
                $_SESSION['error'] = "Something went wrong";
                header("Location: index.php");
            }
        }

    }

 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="register.css">

</head>
<body>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    
        <label for="username">ชื่อผู้ใช้</label>
        <input type="text" name="username" placeholder="กรุณากรอกชื่อผู้ใช้" required>
        <br>
        <label for="password">รหัสผ่าน</label>
        <input type="password" name="password" placeholder="กรุณากรอกรหัสผ่าน" required>
        <br>
        <label for="firstname">ชื่อ</label>
        <input type="text" name="firstname" placeholder="กรุณากรอกชื่อ" required>
        <br>
        <label for="lastname">นามสกุล</label>
        <input type="text" name="lastname" placeholder="กรุณากรอกนามสกุล" required>
        <br>
        <input type="submit" name="submit" value="ยืนยัน">
        <a href="index.php">ไปยังหน้าเข้าสู่ระบบ</a>
    </form>

    
    
</body>
</html>