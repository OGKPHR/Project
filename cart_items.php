<?php
session_start();

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

.popup-message {
    display: none;
    position: fixed;
    background: white;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

</style>

<body style="padding-top: 100px;background-color: #111;color: white;">
    <?php include('newnav.php'); ?>
    
    <main >
    

        <h2>ตะกร้าสินค้า</h2>
        <div class="col-sm-12" style="color: white;">
        <form action="cart_items.php" method="post">
        <table class="table table-dark dataTable" id="dataTable">
            <thead>
                <tr>
                    <th>เลือก</th>
                    <th>ผู้ให้บริการ</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>ราคา</th>
                    <th>ลบ</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cartResult as $cartRow): ?>
                    <tr>
                    <td><input type="checkbox" name="selectedProducts[]" value="<?php echo $cartRow['id']; ?>"
                                data-price="<?php echo $cartRow['Price']; ?>"></td>
                        <td> 
                            <?php
                                
                                    $providerName = $cartRow['provider_id'];
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
                        <td>
                            <?php echo $cartRow['phonenumber']; ?>
                        </td>
                        <td>
                            <?php echo number_format($cartRow['Price']);  ?>
                        </td>
                      
                        <td>
                            <button type="submit" class="btn btn-danger" name="delete_cart"
                                value="<?php echo $cartRow['id']; ?>">Delete</button>

                        </td>
                    </tr>
                    <?php endforeach; ?>
               
            </tbody>
        </table>
        <h4 style="padding: 20px; border-radius: 10px; background-color: green; width: fit-content;">
            รวมทั้งสิ้น: <span id="selectedTotalPrice">0</span>฿
        </h4>
        <!-- Checkout button -->
        <div class="cart-buttons">
            <button type="submit" name="submitCheckout" class="btn btn-primary">checkout</button>
            <a href="Shop.php" class="btn btn-light">กลับสู่หน้าซื้อ</a>
        </div>
        <div class="info-icon" id="infoIcon">&#9432;</div>
<div class="popup-message" id="popupMessage">
    <p style="color:red;">หากสินค้าหายนั้นหมายความว่าถูกขายออกไปแล้ว..ขออภัยด้วยครับ</p>
</div>
    <!-- ปุ่มสั่งซื้อ -->
    <script>
        // เมื่อหน้าเว็บโหลดเสร็จแล้ว
        document.addEventListener("DOMContentLoaded", function () {
            // รับ checkbox ที่มีชื่อ "selectedProducts[]"
            const checkboxes = document.querySelectorAll('input[name="selectedProducts[]"]');

            // เพิ่มการตรวจสอบเมื่อ checkbox ถูกเลือกหรือยกเลิก
            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    updateTotalPrice(); // เรียกฟังก์ชันอัปเดตราคารวม
                });
            });

            // ฟังก์ชันสำหรับอัปเดตราคารวม
            function updateTotalPrice() {
                let selectedTotalPrice = 0;

                // วนลูปผ่าน checkbox เพื่อคำนวณราคารวม
                checkboxes.forEach(function (checkbox) {
                    if (checkbox.checked) {
                        // ดึงราคาจาก data-price ที่คุณเพิ่มให้กับ input checkbox ได้
                        const price = parseFloat(checkbox.getAttribute('data-price'));
                        selectedTotalPrice += price;
                    }
                });

                // แสดงราคารวมที่ถูกคำนวณใน span
                document.getElementById('selectedTotalPrice').textContent = selectedTotalPrice.toFixed(2) ;
            }

            // อัปเดตราคารวมเมื่อหน้าเว็บโหลดเสร็จและเรียกฟังก์ชันอัปเดตราคารวมเริ่มต้น
            updateTotalPrice();
        });
        // Get the info icon and popup message elements
const infoIcon = document.getElementById('infoIcon');
const popupMessage = document.getElementById('popupMessage');

// Add a click event listener to the info icon
infoIcon.addEventListener('click', () => {
    // Toggle the display of the popup message
    if (popupMessage.style.display === 'block') {
        popupMessage.style.display = 'none';
    } else {
        popupMessage.style.display = 'block';
    }
});

    </script>

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
