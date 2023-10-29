<?php
session_start();
require_once "connection.php";

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = $_POST['email'];

    // Retrieve phone numbers from the form (as an array)
    $phoneNumbers = $_POST['pnumber'];

    // Validate username length
    if (strlen($username) < 5 || strlen($username) > 12) {
        $_SESSION['error'] = "Username must be 5-12 characters.";
        header("Location: register.php");
        exit();
    }

    // Validate password length
    if (strlen($password) < 7 || strlen($password) > 12) {
        $_SESSION['error'] = "Password must be 7-12 characters.";
        header("Location: register.php");
        exit();
    }

    // Check if password and repeat_password match
    $repeatPassword = $_POST['repeat_password'];
    if ($password !== $repeatPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Check if required fields are empty
    if (empty($firstname) || empty($lastname) || empty($email) || count($phoneNumbers) < 1) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: register.php");
        exit();
    }

    // Check the number of phone numbers
    if (count($phoneNumbers) < 1 || count($phoneNumbers) > 6) {
        $_SESSION['error'] = "ต้องกรอกเบอร์อย่างน้อย 1 สูงสุดที่สามารถกรอกได้คือ 6";
        header("Location: register.php");
        exit();
    }

    $user_check_query = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION['error'] = "Username already exists";
        header("Location: register.php");
        exit();
    }

    // Insert user into the user table
    $query = "INSERT INTO user (username, password, firstname, lastname, email, userlevel)
              VALUES ('$username', '$hashedPassword', '$firstname', '$lastname', '$email', 'm')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Get the ID of the newly inserted user
        $userID = mysqli_insert_id($conn);

        // Insert phone numbers into user_phonenumbers table
        foreach ($phoneNumbers as $phoneNumber) {
            $phoneNumber = mysqli_real_escape_string($conn, $phoneNumber); // Escape input
            $query = "INSERT INTO user_phonenumbers (user_id, phone_number) VALUES ($userID, '$phoneNumber')";
            mysqli_query($conn, $query);
        }

        $_SESSION['success'] = "User registered successfully";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong";
        header("Location: register.php");
        exit();
    }
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
    <title>SB Admin 2 - Register</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block" style="margin:auto;">
                        <img src="ICON\LOGO2.jpg" alt="">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">สร้างบัญชี!</h1>
                            </div>
                            <form class="user" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
                                method="post">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="firstname"
                                        id="firstname" placeholder="First Name" required>
                                    <span id="firstnameError" class="text-danger"></span>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="lastname"
                                        id="lastname" placeholder="Last Name" required>
                                    <span id="lastnameError" class="text-danger"></span>
                                </div>

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="username"
                                        id="username" placeholder="Username" required>
                                    <span id="usernameError" class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email" id="email"
                                        placeholder="Email Address" required>
                                    <span id="emailError" class="text-danger"></span>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="password"
                                            placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            name="repeat_password" placeholder="Repeat Password" required>
                                    </div>
                                </div>
                                <!-- Phone Number Adder -->
                                <div class="form-group" id="phoneNumbers">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="pnumber[]"
                                            placeholder="Phone Number" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="addPhone">เพิ่มเบอร์</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Display error messages -->
                                <?php
                                if (isset($_SESSION['error'])) {
                                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                    unset($_SESSION['error']); // Clear the error message
                                }
                                ?>
                                <!-- End error message display -->
                                <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                    สมัคร
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">ลืมรหัสผ่าน</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">มีบัญชีอยู่แล้วไช่หรือไม่?</a>
                            </div>
                        </div>
                    </div>
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
    <script>
$(document).ready(function () {
    var usernameValid = false; // Set to false initially
    
    var passwordValid = false; // Set to false initially

    function checkForErrors() {
        var hasErrors = false;

        // Check username error
        if (!usernameValid) {
            hasErrors = true;
        }

        // Check email error
        if (!emailValid) {
            hasErrors = true;
        }

        // Check password error
        if (!passwordValid) {
            hasErrors = true;
        }

        // If no errors, enable the button
        if (!hasErrors) {
            $('#registerButton').prop('disabled', false);
        } else {
            $('#registerButton').prop('disabled', true);
        }

        return !hasErrors; // Return true if there are no errors
    }

    // Function to check username and email duplications
    $('#username').on('input', function () {
        checkDuplicates('username', $(this));
    });

    $('#email').on('input', function () {
        checkDuplicates('email', $(this));
    });

    // Function to validate name and last name fields
    $('#firstname, #lastname').on('input', function () {
        validateNameField($(this));
    });

    // Function to validate password matching
    $('#password, #repeat_password').on('input', function () {
        validatePassword($(this));
        checkForErrors(); // Check for errors when the user types
    });

    function hideErrors(inputField) {
        inputField.siblings('span.text-danger').text('');
    }

    function checkDuplicates(field, inputField) {
        var value = inputField.val();

        if (value.length >= 5 && value.length <= 12) {
            // Reset the error if input is valid
            inputField.siblings('span.text-danger').text('');
            if (field === 'username') {
                usernameValid = true;
            } 
        } else {
            if (field === 'username') {
                inputField.siblings('span.text-danger').text('Username ต้องมีตั้งแต่ 5-12 ตัวอักษร');
                usernameValid = false;
            } 
        }

        checkForErrors();
    }

    function validateNameField(nameField) {
        var name = nameField.val();
        if (/^[A-Za-zก-๙]+$/u.test(name)){
            // Reset the error if input is valid
            nameField.siblings('span.text-danger').text('');
        } else {
            nameField.siblings('span.text-danger').text('ตัวอักษรเท่านั้น');
        }
        checkForErrors();
    }

    function validatePassword(passwordField) {
        var password = $('#password').val();
        var repeatPassword = $('#repeat_password').val();

        if (password.length >= 7 && password.length <= 12) {
            // Reset the error if input is within the range
            passwordField.siblings('span.text-danger').text('');
            passwordValid = true;
        } else {
            passwordField.siblings('span.text-danger').text('รหัสจะต้องมี 7-12 ตัว');
            passwordValid = false;
        }

        if (password === repeatPassword) {
            // Reset the error if passwords match
            $('#passwordError').text('');
        } else {
            $('#passwordError').text('รหัสไม่ตรงกัน');
        }
    }
});
</script>


    <script>
        // Phone Number Adder
        $(document).ready(function () {
            $("#addPhone").click(function () {
                var phoneNumbers = $("#phoneNumbers");

                // Check if there are already 6 phone numbers
                if (phoneNumbers.find(".input-group").length >= 6) {
                    alert("คุณเพิ่มเบอร์สูงสุดแล้ว");
                    return; // Do not add more if the limit is reached
                }

                var phoneRow = '<div class="input-group mb-3">' +
                    '<input type="text" class="form-control" name="pnumber[]" placeholder="Phone Number" required>' +
                    '<div class="input-group-append">' +
                    '<button type="button" class="btn btn-danger removePhone">Remove</button>' +
                    '</div>' +
                    '</div>';

                phoneNumbers.append(phoneRow);
            });

            // Remove Phone Number
            $(document).on('click', '.removePhone', function () {
                $(this).closest('.input-group').remove();
            });
        });

    </script>
</body>

</html>