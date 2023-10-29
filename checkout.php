<?php
session_start();

if (!$_SESSION['userid']) {
    header("Location: index.php");
    exit();
}
if (!isset($_SESSION['selectedProducts']) || count($_SESSION['selectedProducts']) <= 0) {
    // Redirect the user back to the cart page
    header("Location: cart_items.php");
    exit();
}

require_once "connection.php";
$userId = $_SESSION['userid'];
$selectedProducts = $_SESSION['selectedProducts'];

$selectedProductsQuery = "SELECT * FROM product WHERE id IN (" . implode(',', $selectedProducts) . ")";
$selectedProductsResult = mysqli_query($conn, $selectedProductsQuery);

$userQuery = "SELECT * FROM user";
$userResult = mysqli_query($conn, $userQuery);

$locationQuery = "SELECT * FROM location WHERE user_id = $userId";
$locationResult = mysqli_query($conn, $locationQuery);

$phoneNumberQuery = "SELECT * FROM user_phonenumbers WHERE user_id = $userId";
$phoneNumberResult = mysqli_query($conn, $phoneNumberQuery);

$locationData = mysqli_fetch_assoc($locationResult);
$phoneNumberData = mysqli_fetch_assoc($phoneNumberResult);

$transportQuery = "SELECT * FROM transport ";
$transportResult = mysqli_query($conn, $transportQuery);
$transportData = mysqli_fetch_assoc($transportResult);

$promotionQuery = "SELECT * FROM promotion";
$promotionResult = mysqli_query($conn, $promotionQuery);
$promotionData = mysqli_fetch_assoc($promotionResult);

$paymentQuery = "SELECT * FROM payment";
$paymentResult = mysqli_query($conn, $paymentQuery);
$paymentData = mysqli_fetch_assoc($paymentResult);

if (isset($_POST['submitOrder'])) {
    $transportId = $_POST['transport'];
    $paymentChannelId = $_POST['payment'];
    $selectedPhoneNumberId = $_POST['selected_phone_number'];  // Make sure this is the correct ID
    $selectedAddressId = $_POST['selected_location'];  // Make sure this is the correct ID

    // Set status to 2 for payment method 1, otherwise set to 1
    $statusId = ($paymentChannelId == 1) ? 2 : 1;

    // Insert data into order_table with current date and time
    $orderInsertQuery = "INSERT INTO order_table (date, order_time, status_id, transport_id, payment_channel_id, promotion_id, user_id, phone_number_id, address_id) 
    VALUES (CURDATE(), CURTIME(), $statusId, $transportId, $paymentChannelId, NULL, $userId, $selectedPhoneNumberId, $selectedAddressId)";

    if (mysqli_query($conn, $orderInsertQuery)) {
        $orderTableId = mysqli_insert_id($conn);
        // Insert data into order_item
        foreach ($_SESSION['selectedProducts'] as $productId) {
            // Update table name
            $orderItemInsertQuery = "INSERT INTO order_item (order_id, product_id) VALUES ($orderTableId, $productId)";
            mysqli_query($conn, $orderItemInsertQuery);
            // Update table name
            $deleteCartItemQuery = "DELETE FROM cart_item WHERE product_id = $productId";
            mysqli_query($conn, $deleteCartItemQuery);
        }

        // Clear selected products from the session
        unset($_SESSION['selectedProducts']);

        // Redirect to the appropriate page based on payment_id
        if ($paymentChannelId == 1) {
            // Set status to 2 for payment method 1
            $statusId = 2;

            // Update the order status in the database
            $updateStatusQuery = "UPDATE order_table SET status_id = $statusId WHERE id = $orderTableId";
            mysqli_query($conn, $updateStatusQuery);

            // Redirect to confirm_payment.php
            header("Location: confirm_payment.php?order_id=" . $orderTableId);
        } else {
            // For other payment methods, set status to 1
            $statusId = 1;

            // Update the order status in the database
            $updateStatusQuery = "UPDATE order_table SET status_id = $statusId WHERE id = $orderTableId";
            mysqli_query($conn, $updateStatusQuery);

            // Redirect to Shop.php
            header("Location: Shop.php");
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}




if (!$_SESSION['userid']) {
    header("Location: index.php");
    exit();
} else {
    require_once "connection.php";
    $userId = $_SESSION['userid'];
    
    // Define the product ID you want to check for in the order_item table
    $productIdToCheck = 123; // Replace with the actual product ID you want to check

    // Check if the product ID exists in the order_item table
    $checkProductQuery = "SELECT id FROM order_item WHERE product_id = '$productIdToCheck'";
    $checkProductResult = mysqli_query($conn, $checkProductQuery);

    if ($checkProductResult && mysqli_num_rows($checkProductResult) > 0) {
        // The product ID exists in the order_item table, delete it from every user's cart
        $deleteFromCartQuery = "DELETE FROM cart_item WHERE product_id = '$productIdToCheck'";
        mysqli_query($conn, $deleteFromCartQuery);
    }
    
    // Retrieve the user's cart after the product removal
    $cartQuery = "SELECT p.* FROM product p INNER JOIN cart_item c ON p.id = c.product_id WHERE c.user_id = '$userId'";
    $cartResult = mysqli_query($conn, $cartQuery);
}

if (isset($_POST['delete_cart'])) {
    $userId = $_SESSION['userid'];
    $productId = $_POST['delete_cart'];
    $delete_query = "DELETE FROM cart_item WHERE user_id = '$userId' AND product_id = '$productId'";
    // Execute the SQL delete query here
    mysqli_query($conn, $delete_query);
    header("Location: cart_items.php");
    // Redirect back to the cart page or refresh the cart
    exit();
}

if (isset($_POST['submitCheckout'])) {
    // Set the session variable
    $_SESSION['selectedProducts'] = $_POST['selectedProducts'];
    // Redirect to the checkout page
    header("Location: checkout.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lucky Phone Number Shop</title>
    <link rel="icon" href="unnamed.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="shop.css">
</head>

<style>
    body {
        padding-top: 0px;
        background-color: #111;
        color: white;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .btn-gradient-border {
        color: rgb(var(--text-color));
        border: 2px double transparent;
        background-image: linear-gradient(rgb(13, 14, 33), rgb(13, 14, 33)),
            radial-gradient(circle at left top, rgb(1, 110, 218), rgb(217, 0, 192));
        background-origin: border-box;
        background-clip: padding-box, border-box;
    }

    .text-glow {
        text-shadow: 0 0 80px rgb(192 219 255 / 75%), 0 0 32px rgb(65 120 255 / 24%);
    }

    .text-gradient {
        background: linear-gradient(to right, #30CFD0, #c43ad6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .input-group {
        padding-left: 50px;
        padding-bottom: 50%;
    }
    tr th{
        text-align: center;
    }
    td{
        text-align: center;
    }
    table{
        border: 0px;
    }
    .grayed-out {
        background-color: rgba(128, 128, 128, 0.5); /* Gray with transparency */
    }
    main{
        padding-left: 10px;
    }
    .info-icon {
    cursor: pointer;
    font-size: 24px;
    color: blue;
}
/* Add these styles to your external CSS file */
body {
    font-family: Arial, sans-serif;
    background-color: #111;
    color: white;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 0;
    margin: 0;
}

/* Header styles */
h2 {
    color: #007bff;
    text-shadow: 2px 2px 4px #000;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    color: white;
}

table, th, td {
    border: 1px solid #333;
}

th, td {
    padding: 10px;
    text-align: center;
}

th {
    background-color: #007bff;
}

/* Button styles */
button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
}

button:hover {
    background-color: #0056b3;
}

/* Select styles */
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #333;
    border-radius: 5px;
    background-color: #333;
    color: white;
    font-size: 16px;
    margin-top: 10px;
}

/* Link styles */
a {padding-top: 20px;
    color: #007bff;
    text-decoration: none;
}

a:hover {
    color: #0056b3;
}

/* Alert box styles */
.alert {
    background-color: #ffcc00;
    color: #333;
    padding: 10px;
    margin-top: 20px;
    border-radius: 5px;
    display: none;
}

/* Product image styles */
.product-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
}

/* Main content styles */
main {
    padding: 20px;
}

select {
    width: 500px; /* Adjust the width as needed */
    padding: 5px;
    margin-right: 10px; /* Add margin for spacing between elements */
    font-size: 16px; /* Adjust the font size as needed */
}
</style>

<body style="padding-top: 130px;background-color: #111;color: white;">
    <?php include('newnav.php'); ?>
    
    <main>
 
   <!-- Table to display selected products -->
   <h2>รายการสินค้าที่เลือก</h2>
    <table>
        <thead>
            <tr>
            <th>ผู้ให้บริการ</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>ราคา</th>
            </tr>
        </thead>
        <tbody>
            <!-- Display data from the product table with IDs in $_SESSION['selectedProducts'] -->
            <?php
            while ($selectedProductsRow = mysqli_fetch_assoc($selectedProductsResult)):
                ?>
                <tr>
                    <td>
                    <?php
                                
                                $providerName = $selectedProductsRow['provider_id'];
                                // Fetch and display the provider's image
                                $providerQuery = "SELECT providerlogo FROM providers WHERE id = '$providerName'";
                                $providerResult = mysqli_query($conn, $providerQuery);

                                if ($providerResult && mysqli_num_rows($providerResult) > 0) {
                                    $providerData = mysqli_fetch_assoc($providerResult);
                                    $providerImage = $providerData['providerlogo'];
                                    echo '<img style="width: 80px;" src="provider/' . $providerImage . '" alt="Provider Image">';
                                } else {
                                    echo 'Provider Image Not Found'; // Add an error message or default image here
                                }
                            
                            ?>
                    </td>
                    <td>
                        <?php echo $selectedProductsRow['phonenumber']; ?>
                    </td>
                    <td>
                        <?php echo $selectedProductsRow['Price']; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Display total price of selected products -->
    <?php
    $selectedTotalPrice = 0;
    mysqli_data_seek($selectedProductsResult, 0); // Reset to the first row
    while ($selectedProductRow = mysqli_fetch_assoc($selectedProductsResult)) {
        $selectedTotalPrice += $selectedProductRow['Price'];
    }
    ?>
    <h2>ราคารวมที่เลือก:
        <?php echo number_format($selectedTotalPrice, 2); ?> บาท
    </h2>
    <form method="POST">
        <!-- Display transportation options -->
        <h2>ช่องทางการส่ง</h2>
        <select name="transport">
            <?php
            $transportResult = mysqli_query($conn, $transportQuery);
            while ($transportRow = mysqli_fetch_assoc($transportResult)) {
                echo "<option value='" . $transportRow['id'] . "'>" . $transportRow['transportname'] . " - " . $transportRow['price'] . " บาท</option>";
            }
            ?>
        </select>

        <!-- Display payment channels -->
        <h2>ช่องทางการชำระเงิน</h2>
        <select name="payment">
            <?php
            // Fetch payment channel data for accurate looping
            $paymentResult = mysqli_query($conn, $paymentQuery);
            while ($paymentRow = mysqli_fetch_assoc($paymentResult)) {
                echo "<option value='" . $paymentRow['id'] . "'>" . $paymentRow['Paymethod'] . "</option>";
            }
            ?>
        </select>

        <!-- Display selected phone number -->
        <h2>เบอร์ติดต่อ</h2>
        <select name="selected_phone_number" id="selected_phone_number">
   
            <?php
            // Fetch phone number data for accurate looping
            $phoneNumberResult = mysqli_query($conn, $phoneNumberQuery);
            while ($phoneRow = mysqli_fetch_assoc($phoneNumberResult)) {
                echo "<option value='" . $phoneRow['phone_number'] . "'>" . $phoneRow['phone_number'] . "</option>";
            }
            ?>
        </select>
        <p id="phone_number_message" class="error-message"></p> <!-- Error message placeholder -->
        <!-- Display selected location -->
        <h2>ที่อยู่จัดส่ง</h2>
        <select name="selected_location" id="selected_location">
        
            <?php
            // Fetch location data for accurate looping
            $locationResult = mysqli_query($conn, $locationQuery);
            while ($locationRow = mysqli_fetch_assoc($locationResult)) {
                echo "<option value='" . $locationRow['id'] . "'>" . $locationRow['houseno'] . " " . $locationRow['villageno'] . " " . $locationRow['subdistrict'] . " " . $locationRow['district'] . " " . $locationRow['province'] . "" . $locationRow['postcode'] . "</option>";
            }
            ?>
        </select>
        <p id="location_message" class="error-message"></p> <!-- Error message placeholder -->

        <button style="margin-top: 20px;" type="submit" class="btn btn-success" name="submitOrder" id="submitOrder" disabled>ยืนยันการสั่งซื้อ</button>
    </form>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const selectedPhoneNumber = document.getElementById('selected_phone_number');
    const selectedLocation = document.getElementById('selected_location');
    const submitOrderButton = document.getElementById('submitOrder');
    const phoneNumberMessage = document.getElementById('phone_number_message');
    const locationMessage = document.getElementById('location_message');

    // Add event listeners to the select elements
    selectedPhoneNumber.addEventListener('change', validateSelections);
    selectedLocation.addEventListener('change', validateSelections);

    function validateSelections() {
        // Check if a phone number is selected
        if (selectedPhoneNumber.value === '') {
            phoneNumberMessage.textContent = 'ไม่มีเบอร์มือถือ(สามารถเพิ่มได้ที่เมนูโปรไฟล์ของฉัน)';
        } else {
            phoneNumberMessage.textContent = '';
        }

        // Check if a location is selected
        if (selectedLocation.value === '') {
            locationMessage.textContent = 'ไม่มีที่อยู่จัดส่ง(สามารถเพิ่มได้ที่เมนูจัดการที่อยู่ในการส่ง)';
        } else {
            locationMessage.textContent = '';
        }

        // Enable or disable the "ยืนยันการสั่งซื้อ" button based on selections
        if (selectedPhoneNumber.value !== '' && selectedLocation.value !== '') {
            submitOrderButton.disabled = false;
        } else {
            submitOrderButton.disabled = true;
        }
    }

    // Initial validation when the page loads
    validateSelections();
});
</script>


    <a class="btn btn-danger"  style="margin-top: 20px; "href="Shop.php">กลับไปหน้าขายสินค้า</a>
<script src="cartcount.js" >
   
</script>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script><!-- Include Bootstrap CSS -->
