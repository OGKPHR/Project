<?php 
    session_start();
    require_once "connection.php";

    // Handle product addition
    if (isset($_POST['submit'])) {
        $phonenumber = $_POST['phonenumber'];
        $provider = $_POST['Provider'];
        $price = $_POST['price'];
        
        $query = "INSERT INTO product (phonenumber, Provider, Price)
                VALUES ('$phonenumber', '$provider', '$price')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Data inserted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Handle product deletion
    if (isset($_POST['delete'])) {
        if (!empty($_POST['selected_products'])) {
            $selectedProducts = $_POST['selected_products'];
            $ids = implode(',', $selectedProducts);
            $deleteQuery = "DELETE FROM product WHERE id IN ($ids)";
            $deleteResult = mysqli_query($conn, $deleteQuery);
            
            if ($deleteResult) {
                echo "Selected products deleted successfully!";
            } else {
                echo "Error deleting products: " . mysqli_error($conn);
            }
        }
    }

    // Fetch products from the database
    $query = "SELECT * FROM product";
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Product Page</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="APD.css">
</head>
<body>
    <div class="user">
        <h3>Admin: <?php echo $_SESSION['fname']; ?></h3>
        <a href="logout.php">ออกจากระบบ</a>
    </div>
   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <h2>เพิ่มสินค้า</h2>
    <label for="phonenumber">หมายเลขเบอร์โทร</label>
    <input type="text" name="phonenumber" placeholder="กรอกเบอร์โทร" required>
    <br>
    <label for="Provider">ผู้ให้บริการ</label>
    <select name="Provider" required>
        <option value="TRUE">TRUE</option>
        <option value="DTAC">DTAC</option>
        <option value="AIS">AIS</option>
    </select>
    <br>
    <label for="price">ราคา</label>
    <input type="text" name="price" placeholder="กรอกราคา" required>
    <br>
    <input type="submit" name="submit" value="ยืนยัน">
</form>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2>ลบสินค้า</h2>
        <table>
            <thead>
                <tr>
                    <th>เลือก</th>
                    <th>เบอร์</th>
                    <th>ผู้ให้บริการ</th>
                    <th>ราคา</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><input type="checkbox" name="selected_products[]" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo $row['phonenumber']; ?></td>
                        <td><?php echo $row['Provider']; ?></td>
                        <td><?php echo $row['Price']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" name="delete">ลบสินค้าที่เลือก</button>
    </form>
</body>
</html>
