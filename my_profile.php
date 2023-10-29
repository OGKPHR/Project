<?php
session_start();
require_once "connection.php";

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: Shop.php");
    exit();
}

$user_id = $_SESSION['userid'];
$errorMessage = "";

// Function to update user information and phone numbers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $newFirstName = mysqli_real_escape_string($conn, $_POST['first_name']);
        $newLastName = mysqli_real_escape_string($conn, $_POST['last_name']);
        $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
        
        // Add input validation for email
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Invalid email format.";
        } else {
            // Update the user's information in the user table
            $updateUserQuery = "UPDATE user SET firstname = '$newFirstName', lastname = '$newLastName', email = '$newEmail' WHERE id = $user_id";
        
            if (mysqli_query($conn, $updateUserQuery)) {
                // Handle phone number updates here
                // Delete existing phone numbers associated with the user
                $deletePhoneNumbersQuery = "DELETE FROM user_phonenumbers WHERE user_id = $user_id";
                mysqli_query($conn, $deletePhoneNumbersQuery);
        
                // Insert the updated phone numbers
               // Insert the updated phone numbers
// Insert the updated phone numbers
if (isset($_POST['phone_numbers'])) {
    foreach ($_POST['phone_numbers'] as $phoneNumber) {
        $phoneNumber = mysqli_real_escape_string($conn, $phoneNumber);

        // Check if the phone number already exists for the current user
        $checkPhoneNumberQuery = "SELECT * FROM user_phonenumbers WHERE user_id = $user_id AND phone_number = '$phoneNumber'";
        $checkPhoneNumberResult = mysqli_query($conn, $checkPhoneNumberQuery);

        if (mysqli_num_rows($checkPhoneNumberResult) > 0) {
            // Phone number already exists, show a user-friendly warning message
            $errorMessage = "เบอร์นี้ถูกใช้งานแล้ว"; // Set the error message
            echo $errorMessage; // Display the error message
        } else {
            // Phone number doesn't exist, proceed with insertion
            $insertPhoneNumberQuery = "INSERT IGNORE INTO user_phonenumbers (user_id, phone_number) VALUES ($user_id, '$phoneNumber')";

            // Attempt to insert the phone number
            if (mysqli_query($conn, $insertPhoneNumberQuery)) {
                // Phone number inserted successfully
            } else {
                // Handle other errors, if any
                $errorMessage = "Error inserting phone number: " . mysqli_error($conn);
            }
        }
    }
}

   // Handle profile icon selection
                if (isset($_POST['selected_icon'])) {
                    $selectedIcon = mysqli_real_escape_string($conn, $_POST['selected_icon']);
                    // Update the user's profile icon path in the database
                    $updateIconQuery = "UPDATE user SET profile_icon = '$selectedIcon' WHERE id = $user_id";
                    mysqli_query($conn, $updateIconQuery);
                }
        
                header("Location: my_profile.php?success=1");
                exit();
            } else {
                // Update failed
                $errorMessage = "Error updating user information: " . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['change_password'])) {
        $currentPassword = mysqli_real_escape_string($conn, $_POST['current_password']);
        $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
        // Input validation for password change
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $passwordChangeError = "All password fields are required.";
        } elseif ($newPassword !== $confirmPassword) {
            $passwordChangeError = "New password and confirm password do not match.";
        } else {
            // Verify the current password before changing it
            // Replace 'your_password_hashing_function' with your actual password hashing method
            $currentPasswordHash = password_hash($currentPassword, PASSWORD_BCRYPT);
    
            // Query to retrieve the current user's hashed password from the database
            $passwordQuery = "SELECT password FROM user WHERE id = $user_id";
            $passwordResult = mysqli_query($conn, $passwordQuery);
    
            if ($passwordResult) {
                $row = mysqli_fetch_assoc($passwordResult);
                $hashedPassword = $row['password'];
    
                // Verify if the provided current password hash matches the stored hashed password
                if (password_verify($currentPassword, $hashedPassword)) {
                    // Current password is correct, proceed to change the password
    
                    // Hash the new password before storing it
                    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
    
                    // Update the user's password in the database
                    $updatePasswordQuery = "UPDATE user SET password = '$newPasswordHash' WHERE id = $user_id";
    
                    if (mysqli_query($conn, $updatePasswordQuery)) {
                        // Password changed successfully
                        $passwordChangeSuccess = "Password changed successfully!";
                    } else {
                        // Password change failed
                        $passwordChangeError = "Password change failed. Please try again.";
                    }
                } else {
                    // Current password is incorrect
                    $currentPasswordError = "Current password is incorrect.";
                }
            } else {
                // Error while fetching current password
                $passwordChangeError = "An error occurred. Please try again later.";
            }
        }
    }
}

// Function to display user information and phone numbers
$userQuery = "SELECT * FROM user WHERE id = $user_id";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Database error: " . mysqli_error($conn));
}

$userData = mysqli_fetch_assoc($userResult);

$phoneNumbersQuery = "SELECT phone_number FROM user_phonenumbers WHERE user_id = $user_id";
$phoneNumbersResult = mysqli_query($conn, $phoneNumbersQuery);

if (!$phoneNumbersResult) {
    die("Database error: " . mysqli_error($conn));
}

$phoneNumbers = [];

while ($row = mysqli_fetch_assoc($phoneNumbersResult)) {
    $phoneNumbers[] = $row['phone_number'];
}

// Function to get a list of profile icons from the "profileicon" folder
function getProfileIcons() {
    $icons = glob('profileicon/*.{jpg,png}', GLOB_BRACE);
    return $icons;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Lucky Phone Number Shop</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<style>
    /* Style for the form container */
    main {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    /* Style for form labels and input fields */
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .profile-icon-thumbnail {
        margin: 5px;
        cursor: pointer;
        width: 50px;
        height: 50px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    /* Style for the submit button */
    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 10px 20px;
        cursor: pointer;
    }

    /* Style for success and error messages */
    .success-message {
        color: green;
    }

    .error-message {
        color: red;
    }

    /* Style for the profile icon */
    .profile-icon-container {
        text-align: center;
    }

    .profile-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        cursor: pointer;
    }

    /* Style for the profile icon selection */
    .profile-icon-selection {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 20px;
    }

    .profile-icon-thumbnail {
        margin: 5px;
        cursor: pointer;
    }

    .selected-icon {
        border: 2px solid #007bff;
    }

    /* Style for the "Add Phone Number" button */
    .add-phone-number-button {
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 5px 10px;
        cursor: pointer;
    }

    /* Style for the change password form */
    .change-password-container {
        border-top: 1px solid #ccc;
        margin-top: 20px;
        padding-top: 20px;
    }
</style>
<header>
    <!-- Your header content goes here -->
</header>
<main>
    <div class="column">
        <h1>โปรไฟล์ของฉัน</h1>
        <a href="Shop.php" class="btn btn-danger">กลับสู่หน้าซื้อ</a>
        <form method="post" enctype="multipart/form-data">
            <label for="first_name">ชื่อ:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($userData['firstname']); ?>"><br>

            <label for="last_name">นามกสุล:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($userData['lastname']); ?>"><br>

            <label for="email">อีเมล:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>"><br>

            <label for="phone_numbers">เบอร์โทร:</label>
            <?php foreach ($phoneNumbers as $index => $phoneNumber): ?>
                <div class="phone-number-input">
                    <input type="tel" name="phone_numbers[]" value="<?php echo htmlspecialchars($phoneNumber); ?>" required>
                    <button type="button" class="add-phone-number-button" onclick="removePhoneNumberField(this)">ลบ</button>
                </div>
            <?php endforeach; ?>
            <div id="error-message">
    <?php
    
        echo $errorMessage;
    
    ?>
</div>
            <!-- "Add Phone Number" button for the initial input field -->
            <button style="margin-top: 20px;" type="button" class="add-phone-number-button" onclick="addPhoneNumberField(this)">เพิ่ม</button>

            <!-- Profile Icon Selection -->
            <label for="profile_icon">เลือกรูปโปรไฟล์:</label>
            <div class="profile-icon-container">
                <?php
                // Check if the user already has a profile icon
                if (!empty($userData['profile_icon'])) {
                    echo '<img src="' . htmlspecialchars($userData['profile_icon']) . '" class="profile-icon" alt="Current Profile Icon">';
                } else {
                    echo '<p>No profile icon selected.</p>';
                }
                ?>

                <!-- Profile Icon Selection Options -->
                <h2>รูปโปรไฟล์</h2>
                <div class="profile-icon-selection">
                    <?php
                    $profileIcons = getProfileIcons();
                    foreach ($profileIcons as $icon) {
                        $selectedClass = ($icon === $userData['profile_icon']) ? 'selected-icon' : '';
                        echo '<img src="' . htmlspecialchars($icon) . '" class="profile-icon-thumbnail ' . $selectedClass . '" alt="Profile Icon" data-icon="' . htmlspecialchars($icon) . '">';
                    }
                    ?>
                </div>
            </div>

            <input type="hidden" name="selected_icon" id="selectedIcon" value="<?php echo htmlspecialchars($userData['profile_icon']); ?>">

            <input type="submit" name="update_profile" value="บันทึก">
        </form>
    </div>
    <!-- Display success or error messages for profile update -->
    <?php
    if (isset($errorMessage)) {
        echo '<p class="error-message">' . htmlspecialchars($errorMessage) . '</p>';
    } elseif (isset($_GET['success']) && $_GET['success'] === '1') {
        echo '<p class="success-message">Profile updated successfully!</p>';
    }
    ?>

    <!-- Change Password Form -->
    <div class="change-password-container">
        <h2>เปลี่ยนรหัสผ่าน</h2>
        <div class="column">
            <form method="post">
                <label for="current_password">รหัสผ่านปัจจุบัน:</label>
                <input type="password" name="current_password" required><br>

                <label for="new_password">รหัสผ่านใหม่:</label>
                <input type="password" name="new_password" required><br>

                <label for="confirm_password">ยืนยัน รหัสผ่านใหม่:</label>
                <input type="password" name="confirm_password" required><br>

                <input type="submit" name="change_password" value="Change Password">
            </form>
            <!-- Display success or error messages for password change -->
            <?php
            if (isset($currentPasswordError)) {
                echo '<p class="error-message">' . htmlspecialchars($currentPasswordError) . '</p>';
            } elseif (isset($passwordChangeSuccess)) {
                echo '<p class="success-message">' . htmlspecialchars($passwordChangeSuccess) . '</p>';
            } elseif (isset($passwordChangeError)) {
                echo '<p class="error-message">' . htmlspecialchars($passwordChangeError) . '</p>';
            }
            ?>
        </div>
    </div>
</main>
<footer>
    <!-- Your footer content goes here -->
</footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the profile icon elements
            const profileIcons = document.querySelectorAll('.profile-icon-thumbnail');

            // Get the selected icon input field
            const selectedIconInput = document.getElementById('selectedIcon');

            // Function to handle profile icon selection
            profileIcons.forEach((icon) => {
                icon.addEventListener('click', () => {
                    // Remove the "selected-icon" class from all icons
                    profileIcons.forEach((icon) => {
                        icon.classList.remove('selected-icon');
                    });

                    // Add the "selected-icon" class to the clicked icon
                    icon.classList.add('selected-icon');

                    // Update the selected icon input field
                    selectedIconInput.value = icon.getAttribute('data-icon');
                });
            });
        });
        function addPhoneNumberField(button) {
    // Get all existing phone number input fields
    const existingPhoneInputs = document.querySelectorAll('input[name="phone_numbers[]"]');

    // Check if the maximum limit (6) is reached
    if (existingPhoneInputs.length >= 6) {
        // You can display a message to the user or take any other action here
        alert("เพิ่มสูงสุดได้ 6 หมายเลข");
        return;
    }

    const phoneNumberInput = document.createElement('input');
    phoneNumberInput.type = 'tel';
    phoneNumberInput.name = 'phone_numbers[]';
    phoneNumberInput.required = true;

    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.className = 'add-phone-number-button';
    removeButton.textContent = 'ลบ';
    removeButton.onclick = function () {
        // Check if there's more than one phone number field before removing
        if (existingPhoneInputs.length > 0) {
            removePhoneNumberField(removeButton);
        } else {
            alert("ต้องมีอย่างน้อย 1 หมายเลข");
        }
    };

    const phoneInputDiv = document.createElement('div');
    phoneInputDiv.className = 'phone-number-input';
    phoneInputDiv.appendChild(phoneNumberInput);
    phoneInputDiv.appendChild(removeButton);

    // Insert the new input field and button before the clicked button
    button.parentNode.insertBefore(phoneInputDiv, button);
}

// Function to remove a phone number input field
function removePhoneNumberField(button) {
    // Check if there's more than one phone number field before removing
    if (document.querySelectorAll('input[name="phone_numbers[]"]').length > 1) {
        const phoneInputDiv = button.parentNode;
        phoneInputDiv.parentNode.removeChild(phoneInputDiv);
    } else {
        alert("ต้องมีอย่างน้อย 1 หมายเลข");
    }
}
    </script>
</body>
</html>
