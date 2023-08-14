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

    // Count total users in the database
    $countQuery = "SELECT COUNT(id) AS total_users FROM user";
    $countResult = mysqli_query($conn, $countQuery);
    $totalUsers = mysqli_fetch_assoc($countResult)['total_users'];

    // Search for users by name or ID
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search_term'];
        $searchQuery = "SELECT * FROM user WHERE id = '$searchTerm' OR username = '$searchTerm' OR CONCAT(firstname, ' ', lastname) LIKE '%$searchTerm%'";
        $searchResult = mysqli_query($conn, $searchQuery);
    }
    // Handle user deletion
if (isset($_POST['delete_users'])) {
    if (!empty($_POST['selected_users'])) {
        $selectedUsers = $_POST['selected_users'];
        $ids = implode(',', $selectedUsers);
        $deleteQuery = "DELETE FROM user WHERE id IN ($ids)";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "Selected users deleted successfully!";
        } else {
            echo "Error deleting users: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Management</title>
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

    
    <!-- Search users by name or ID -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <p><h2>ค้นหาผู้ใช้</h2><h2><div class="usamount">จำนวนผู้ใช้ในระบบ: <?php echo $totalUsers; ?></div></h2></p>

        <label for="search_term">ค้นหาด้วยชื่อหรือรหัสผู้ใช้</label>
        <input type="text" name="search_term" placeholder="กรอกชื่อหรือรหัสผู้ใช้" required>
        <button type="submit" name="search">ค้นหา</button>
    </form>

    <!-- Display searched users -->
    <?php if (isset($_POST['search'])) : ?>
       
        <?php if (mysqli_num_rows($searchResult) > 0) : ?>
         <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
         <h2>ผลการค้นหาผู้ใช้</h2>
            <table>
                <thead>
                    <tr>
                        <th>เลือก</th>
                        <th>ID</th>
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>สถานะ</th>
                        <!-- Add more columns if needed... -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($searchResult)) : ?>
                        <tr>
                            <td><input type="checkbox" name="selected_users[]" value="<?php echo $row['id']; ?>"></td>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo ($row['userlevel'] == 'a') ? 'ADMIN' : (($row['userlevel'] == 'm') ? 'USER' : ''); ?></td>

                            <!-- Add more columns if needed... -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button type="submit" name="delete_users">ลบผู้ใช้ที่เลือก</button>
        </form>
        <?php else : ?>
           <div class="red">ไม่พบผู้ใช้ที่ค้นหา</div> 
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
