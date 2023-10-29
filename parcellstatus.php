<?php
session_start();
require_once "connection.php";

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: Shop.php"); // Redirect to the login page if not logged in
    exit();
}

$user_id = $_SESSION['userid'];

// Fetch order information with details
$query = "SELECT
    ot.id AS OrderID,
    ot.date AS OrderDate,
    s.status_name AS PaymentStatus,
    ot.payment_channel_id AS PaymentChannelID,
    ot.transport_id AS TransportID,
    ot.order_time AS Odtime,
    SUM(p.Price) AS SumPrice,
    GROUP_CONCAT(DISTINCT pr.providerlogo, ' หมายเลข ', p.phonenumber, ' ราคา ',p.Price ORDER BY oi.id ASC SEPARATOR '<br>') AS Products,
    CONCAT(l.houseno, ', ', l.villageno, ', ', l.subdistrict, ', ', l.district, ', ', l.province, ' ', l.postcode) AS Location
FROM order_table ot
LEFT JOIN order_item oi ON ot.id = oi.order_id
LEFT JOIN product p ON oi.product_id = p.id
LEFT JOIN status s ON ot.status_id = s.id
LEFT JOIN location l ON ot.address_id = l.id
LEFT JOIN providers pr ON p.provider_id = pr.id
WHERE ot.user_id = $user_id
GROUP BY ot.id, ot.date, ot.payment_channel_id, ot.transport_id, l.houseno, l.villageno, l.subdistrict, l.district, l.province, l.postcode, s.status_name";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database error: " . mysqli_error($conn));
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
<body style="background-color: black;">
<style>
 
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
        width: fit-content; /* Adjust the width as needed */
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #313133;
        padding-top: 200px;
        height: 100vh;
        color: white;
    }

    /* Style for the table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,td {color: #fff;
        border: 1px solid #ccc;
        padding: 5px;
        
    }

    th {
        background-color: #f2f2f2;
       
        color: #fff;
    }
</style>
<?php include('newnav.php'); ?>
<header>
    <!-- Your header content goes here -->
</header>

<main>
    <div class="container">
        <h1>สถานะพัสดุ</h1>
        <table class="table table-bordered">
        <thead>
        <tr>
            <th style="color: #fff;">พัสดุ</th>
            <th style="color: #fff;">วันและเวลาสั่งซื้อ</th>
            <th style="color: #fff;">ที่อยู่ในการจัดส่ง</th>
            <th style="color: #fff;">สถานะการจัดส่ง</th>
            <th style="color: #fff;">สถานะการชำระ</th>
            <th style="color: #fff;">ช่องทางการชำระ</th>
            <th style="color: #fff;">ราคารวม</th>
        </tr>
    </thead>
    <tbody>
    <?php
$previousOrderId = null; // Initialize a variable to track the previous order ID
while ($row = mysqli_fetch_assoc($result)) {
    $order_id = $row['OrderID'];
    $date = $row['OrderDate'];
    $paymentStatus = $row['PaymentStatus'];
    $paymentChannel = $row['PaymentChannelID'];
    $transportID = $row['TransportID'];
    $sumPrice = $row['SumPrice'];
    $products = $row['Products'];
    $location = $row['Location'];
    $odTime = $row['Odtime'];

    $status = 'กำลังเตรียมสินค้า'; // Default status

// Query the parcel_code table to check if the order exists
$orderCheckQuery = "SELECT order_id FROM parcel_code WHERE order_id = $order_id";
$orderCheckResult = mysqli_query($conn, $orderCheckQuery);

if ($orderCheckResult && mysqli_num_rows($orderCheckResult) > 0) {
    $status = 'กำลังจัดส่ง'; // Order exists in parcel_code
}

// Check the status_id from the order_table
$statusQuery = "SELECT status_id FROM order_table WHERE id = $order_id";
$statusResult = mysqli_query($conn, $statusQuery);

if ($statusResult) {
    $statusData = mysqli_fetch_assoc($statusResult);
    $status_id = $statusData['status_id'];

    // Check the status_id and update $status accordingly
    if ($status_id == 3) {
        $status = 'ยกเลิก';
    }
}
    // Display the "Products" column only once for each order
    if ($order_id !== $previousOrderId) {
        ?>
        <tr>
            <td style="color: #fff; white-space: nowrap;">
                <?php
                // Explode the products into an array
                $productLines = explode('<br>', $products);

                // Loop through the product lines and extract product name and price
                foreach ($productLines as $productLine) {
                    $productInfo = explode(' หมายเลข ', $productLine);
                    $productName = $productInfo[0];
                    $productPrice = $productInfo[1];
                    // Extract the image name
                    $imageName = preg_replace('/^.*\s/', '', $productName);

                    // Display the image and product details
                    echo '<img style="width: 80px;" src="provider/' . $imageName . '" alt="' . $productName . '">' . ' ' . $productPrice . '<br>';
                }
                ?>
            </td>
            <td style="color: #fff;"><?php echo $date; ?><br><?php echo $odTime; ?></td>
            <td style="color: #fff;"><?php echo $location; ?></td>
            <td style="color: #fff;"><?php echo $status; ?></td>
            <td style="color: #fff;"><?php echo $paymentStatus; ?></td>
            <td style="color: #fff;">
                <?php
                // Fetch the Payment Method name from the "payments" table
                $paymentMethodID = $paymentChannel; // Assuming paymentChannel is the ID
                $paymentMethodQuery = "SELECT Paymethod FROM payment WHERE id = $paymentMethodID";
                $paymentMethodResult = mysqli_query($conn, $paymentMethodQuery);

                if ($paymentMethodResult) {
                    $paymentMethodData = mysqli_fetch_assoc($paymentMethodResult);
                    $paymentMethodName = $paymentMethodData['Paymethod'];

                    echo $paymentMethodName; // Display the Payment Method name
                } else {
                    // Handle the case where the query fails or no matching method is found
                    echo "Unknown Payment Method";
                }
                ?>
            </td>
            <td style="color: #fff;"><?php echo $sumPrice; ?></td>
        </tr>
        <?php
        $previousOrderId = $order_id; // Update the previous order ID
    }
}
?>

    </tbody>

        </table>
    </div>
</main>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="cartcount.js"></script>
<footer>
    <!-- Your footer content goes here -->
</footer>
</body>
</html>
