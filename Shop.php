<?php 
    session_start();

    if (!$_SESSION['userid']) {
        header("Location: index.php");
        exit(); // Make sure to exit after redirect
    } else {
        require_once "connection.php";
        
        $query = "SELECT * FROM product";
        $result = mysqli_query($conn, $query);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lucky Phone Number Shop</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="sticky-header">
        <h1><i class="fas fa-shopping-cart"></i>Lucky Phone Number Shop</h1>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        <div class="user"><h3>คุณ: <?php echo $_SESSION['fname']; ?></h3><a href="logout.php">ออกจากระบบ</a></div>
    </header>

    <main>
        <section class="product-listings">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <div class="product">
                    <img src="<?php echo $row['Provider']; ?>.png" alt="Product">
                    <h2><?php echo $row['phonenumber']; ?></h2>
                    <p>Description of Product.</p>
                    <span class="price">฿<?php echo $row['Price']; ?> </span>
                    <button>เพิ่มลงตะกร้า</button>
                </div>
            <?php endwhile; ?>
        </section>
    </main>

    <section class="cart">
        <h2>ตะกร้าสินค้า</h2>
        <ul id="cart-items">
           
        </ul>
        <p>ราคารวม: <span id="cart-total">฿0.00</span></p>
        <button id="clear-cart-btn">เคลียร์ตะกร้า</button>
    </section>

    <footer>
        <p>&copy; 2023 Lucky Phone Number Shop. All rights reserved.</p>
    </footer>

    <script src="Shop.js"></script>
</body>
</html>
