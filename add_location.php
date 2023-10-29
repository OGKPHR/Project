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
$successMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_main_location'])) {
    // Loop through the submitted checkboxes
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'main_location_') === 0) {
            $locationId = mysqli_real_escape_string($conn, $value);

            // Update the main location for the user
            $updateMainLocationQuery = "UPDATE user SET main_location_id = '$locationId' WHERE id = '$user_id'";

            if (mysqli_query($conn, $updateMainLocationQuery)) {
                // Main location updated successfully
                $successMessage = "เลือกที่อยู่หลักสำเร็จ!!";
            } else {
                // Main location update failed
                $errorMessage = "เกิดข้อผิดพลาดในการเลือก!!: " . mysqli_error($conn);
            }
        }
    }
}
// Function to add a location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_location'])) {
    $houseno = mysqli_real_escape_string($conn, $_POST['houseno']);
    $villageno = mysqli_real_escape_string($conn, $_POST['villageno']);
    $subdistrict = mysqli_real_escape_string($conn, $_POST['subdistrict']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $postcode = mysqli_real_escape_string($conn, $_POST['postcode']);

    // Check if the location already exists for the user
    $checkLocationQuery = "SELECT * FROM location 
                            WHERE user_id = '$user_id' 
                            AND houseno = '$houseno' 
                            AND villageno = '$villageno' 
                            AND subdistrict = '$subdistrict' 
                            AND district = '$district' 
                            AND province = '$province' 
                            AND postcode = '$postcode'";
    
    $existingLocationResult = mysqli_query($conn, $checkLocationQuery);

    if (mysqli_num_rows($existingLocationResult) > 0) {
        // Location already exists
        $errorMessage = "ไม่สามารถเพิ่มที่อยู่ซ้ำได้";
    } else {
        // Insert the location data into the database
        $insertLocationQuery = "INSERT INTO location (user_id, houseno, villageno, subdistrict, district, province, postcode)
                                VALUES ('$user_id', '$houseno', '$villageno', '$subdistrict', '$district', '$province', '$postcode')";

        if (mysqli_query($conn, $insertLocationQuery)) {
            // Location added successfully
            $successMessage = "ที่อยู่ถูกเพิ่มสำเร็จ!";
        } else {
            // Location insertion failed
            $errorMessage = "เพิ่มที่อยู่ไม่สำเร็จเนื่องจาก: " . mysqli_error($conn);
        }
    }
}

// Function to delete a location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_location'])) {
    // Check if the user has at least one location before allowing deletion
    $checkLocationCountQuery = "SELECT COUNT(*) as location_count FROM location WHERE user_id = '$user_id'";
    $locationCountResult = mysqli_query($conn, $checkLocationCountQuery);

    if ($locationCountResult && $row = mysqli_fetch_assoc($locationCountResult)) {
        $locationCount = $row['location_count'];
        if ($locationCount > 1) {
            // The user has more than one location, proceed with deletion
            $delete_houseno = mysqli_real_escape_string($conn, $_POST['delete_houseno']);
            $delete_villageno = mysqli_real_escape_string($conn, $_POST['delete_villageno']);
            $delete_subdistrict = mysqli_real_escape_string($conn, $_POST['delete_subdistrict']);
            $delete_district = mysqli_real_escape_string($conn, $_POST['delete_district']);
            $delete_province = mysqli_real_escape_string($conn, $_POST['delete_province']);
            $delete_postcode = mysqli_real_escape_string($conn, $_POST['delete_postcode']);

            // Construct and execute the DELETE query
            $deleteLocationQuery = "DELETE FROM location 
                                WHERE user_id = '$user_id' 
                                AND houseno = '$delete_houseno' 
                                AND villageno = '$delete_villageno' 
                                AND subdistrict = '$delete_subdistrict' 
                                AND district = '$delete_district' 
                                AND province = '$delete_province' 
                                AND postcode = '$delete_postcode'";

            if (mysqli_query($conn, $deleteLocationQuery)) {
                // Location deleted successfully
                $successMessage = "ลบที่อยู่สำเร็จ!";
            } else {
                // Location deletion failed
                $errorMessage = "เกิดข้อผิดพลาด: " . mysqli_error($conn);
            }
        } else {
            // The user has only one location, cannot delete
            $errorMessage = "ต้องมีขั้นต่ำ 1 ที่อยู่";
        }
    }
}

// Retrieve all user locations
$getLocationsQuery = "SELECT * FROM location WHERE user_id = '$user_id'";
$locationsResult = mysqli_query($conn, $getLocationsQuery);

if (!$locationsResult) {
    die("Database error: " . mysqli_error($conn));
}

// Retrieve the current main location for the user
$getMainLocationQuery = "SELECT main_location_id FROM user WHERE id = '$user_id'";
$mainLocationResult = mysqli_query($conn, $getMainLocationQuery);

if ($mainLocationResult && mysqli_num_rows($mainLocationResult) > 0) {
    $mainLocationData = mysqli_fetch_assoc($mainLocationResult);
    $mainLocationId = $mainLocationData['main_location_id'];
} else {
    $mainLocationId = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Add Location</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<style>
    /* Add your CSS styles here */

    /* Style for the form container */
 

    /* Style for form labels and input fields */
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"] {
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

    main {
        display: flex;
        justify-content: space-between; /* Split into two columns */
        max-width: 900px; /* Adjust the width as needed */
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        padding-top: 200px;
    }

    .column {
        width: 48%; /* Adjust the width of each column */
    }

    /* Style for success and error messages */
    .success-message {
        color: green;
    }

    .error-message {
        color: red;
    }

    /* Style for location list */
    .location-list {
        list-style-type: none;
        padding: 0;
    }

    .location-item {
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
    }

    /* Style for delete button */
    .delete-button {
        background-color: red;
        color: white;
        border: none;
        border-radius: 3px;
        padding: 5px 10px;
        cursor: pointer;
    }
</style>
<?php include('newnav.php'); ?>
<header>
    <!-- Your header content goes here -->
</header>

<main>
    <div class="column">
        <h1>เพิ่มที่อยู่</h1>
        <form method="post">
            <label for="houseno">บ้านเลขที่(ตัวเลข):</label>
            <input type="text" name="houseno" required><br>

            <label for="villageno">หมู่ที่(ตัวเลข):</label>
            <input type="text" name="villageno" required><br>

            <label for="subdistrict">ตำบล:</label>
            <input type="text" name="subdistrict" required><br>

            <label for="district">อำเภอ:</label>
            <input type="text" name="district" required><br>

            <label for="province">จังหวัด:</label>
            <input type="text" name="province" required><br>

            <label for="postcode">รหัสไปรษณีย์:</label>
            <input type="text" name="postcode" required><br>

            <input type="submit" name="submit_location" value="บันทึก">
        </form>

       
    </div>
    <?php echo $errorMessage; ?>

    <div class="column">
        
        <!-- Display user locations with checkboxes for choosing the main location -->
        <h2>ที่อยู่ของคุณ</h2>
         <!-- Display success or error messages for location submission -->
 <?php
        if (isset($successMessage)) {
            echo '<p class="success-message">' . $successMessage . '</p>';
        } elseif (isset($errorMessage)) {
            echo '<p class="error-message">' . $errorMessage . '</p>';
        }
        ?>
        <form method="post">
    <ul class="location-list">
        <?php
        while ($row = mysqli_fetch_assoc($locationsResult)) {
            echo '<li class="location-item">';
            echo 'บ้านเลขที่: ' . $row['houseno'] . '<br>';
            echo 'หมู่: ' . $row['villageno'] . '<br>';
            echo 'ตำบล: ' . $row['subdistrict'] . '<br>';
            echo 'อำเภอ: ' . $row['district'] . '<br>';
            echo 'จังหวัด: ' . $row['province'] . '<br>';
            echo 'รหัสไปรษณีย์: ' . $row['postcode'] . '<br>';
            
            // Checkbox for selecting the main location
            echo '<input type="checkbox" name="main_location_' . $row['id'] . '" value="' . $row['id'] . '"';
            
            // Check if this location is the main location
            if ($row['id'] == $mainLocationId) {
                echo ' checked';
            }
            
            echo '> ที่อยู่หลัก<br>';

            // Hidden inputs for deletion
            echo '<input type="hidden" name="delete_houseno" value="' . $row['houseno'] . '">';
            echo '<input type="hidden" name="delete_villageno" value="' . $row['villageno'] . '">';
            echo '<input type="hidden" name="delete_subdistrict" value="' . $row['subdistrict'] . '">';
            echo '<input type="hidden" name="delete_district" value="' . $row['district'] . '">';
            echo '<input type="hidden" name="delete_province" value="' . $row['province'] . '">';
            echo '<input type="hidden" name="delete_postcode" value="' . $row['postcode'] . '">';

            echo '<button type="submit" class="delete-button" name="delete_location">ลบ</button>';
            echo '</li>';
        }
        ?>
    </ul>
    <input type="submit" name="set_main_location" value="บันทึกที่อยู่หลัก">
</form>

    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Handle checkbox changes
        $('input[type="checkbox"]').change(function() {
            // Uncheck other checkboxes
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });
    });
</script>
<script src="cartcount.js"></script>
<footer>
    <!-- Your footer content goes here -->
</footer>
</body>
</html>
