<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="container">
        <div class="login-form" >
            <h1 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif">LUCKY PHONE NUMBER SHOP</h1>
            <form method="post" action="login.php">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" name="login">เข้าสู่ระบบ</button>
            </form>
            <p>คุณไม่มีบัญชีใช่หรือไม่? <a href="register.php">ลงทะเบียน</a></p>
        </div>
    </div>
</body>
</html>

<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace these with your actual username and password
    $validUsername = 'your_username';
    $validPassword = 'your_password';

    if ($username === $validUsername && $password === $validPassword) {
        // Redirect to a success page or perform further actions
        echo '<p>Login successful.</p>';
    } else {
        echo '<p>Login failed. Invalid credentials.</p>';
    }
}
?>
