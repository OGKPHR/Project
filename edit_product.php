<?php
session_start();
require_once "connection.php";

// Include calculate.php
require_once "calculate.php";

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = "SELECT * FROM product WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phonenumber = $_POST['phonenumber'];
        $provider_id = $_POST['provider'];
        $price = $_POST['price'];
    
        // Update the product information in the database
        $updateQuery = "UPDATE product SET phonenumber = '$phonenumber', provider_id = $provider_id, Price = $price WHERE id = $product_id";
        $updateResult = mysqli_query($conn, $updateQuery);
    
        if ($updateResult) {
            echo $_SESSION['success'] = "Product updated successfully";
            header("Location: Addproduct.php");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong";
        }
    }
    
}

$queryProviders = "SELECT * FROM providers";
$resultProviders = mysqli_query($conn, $queryProviders);

$providerOptions = [];
while ($row = mysqli_fetch_assoc($resultProviders)) {
    $providerOptions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit Product</title>
    <!-- Include necessary CSS files from startbootstrap-sb-admin-2-gh-pages -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Edit Product</h1>
    <form method="post">
        <div class="form-group">
            <label for="phonenumber">Phone Number</label>
            <input type="text" class="form-control" name="phonenumber" value="<?php echo $product['phonenumber']; ?>" required>
        </div>
        <div class="form-group">
            <label for="provider">Provider</label>
            <select class="form-control" name="provider" required>
                <?php foreach ($providerOptions as $providerOption) : ?>
                    <option value="<?php echo $providerOption['id']; ?>" <?php if ($product['provider_id'] === $providerOption['id']) echo "selected"; ?>>
                        <?php echo $providerOption['option_value']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
       <!-- ... previous form elements ... -->
<div class="form-group">
    <label for="price">Price</label>
    <input type="text" class="form-control" name="price" value="<?php echo $product['Price']; ?>" required>
</div>



        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



    <!-- Include necessary JS files from startbootstrap-sb-admin-2-gh-pages -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
