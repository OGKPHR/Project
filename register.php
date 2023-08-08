<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="Re.css">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h1>Register</h1>
            <form method="post" action="register.php">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
                <label for="confirm-password">ยืนยันรหัสผ่าน:</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
                <label for="phone">เบอร์โทรศัพย์:</label>
                <input type="tel" id="phone" name="phone" required>
                <label for="province">จังหวัด:</label>
                <input type="text" id="state" name="state">
                <label for="district">อำเภอ:</label>
                <input type="text" id="district" name="district">
                <label for="sub-district">ตำบล:</label>
                <input type="text" id="sub-district" name="sub-district">
                <label for="H.no">บ้านเลขที่:</label>
                <input type="text" id="H.no" name="H.no">
                <button type="submit" name="register">สมัครสมาชิก</button>
            </form>
            <p>คุณมีบัญชีอยู่แล้วใช่ไหม? <a href="Login.php">เข้าสู่ระบบ</a></p>
            <script src="Re.js"></script>
        </div>
    </div>
</body>
</html>
