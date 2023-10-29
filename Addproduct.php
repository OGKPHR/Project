<?php
session_start();
require_once "connection.php";
require_once "calculate.php"; // Include the calculate script

// Fetch provider options from the 'providers' table
$providerOptionsQuery = "SELECT * FROM providers";
$providerOptionsResult = mysqli_query($conn, $providerOptionsQuery);
$providerOptions = mysqli_fetch_all($providerOptionsResult, MYSQLI_ASSOC);


if (isset($_POST['submit'])) {
    $phonenumber = $_POST['phonenumber'];
    $provider = $_POST['Provider'];
    $price_option = $_POST['price_option'];
    $price = 0;
    $cost = $_POST['cost'];

    // Check if the selected provider exists in the providers table
    $providerQuery = "SELECT id FROM providers WHERE option_value = '$provider'";
    $providerResult = mysqli_query($conn, $providerQuery);

    if ($providerResult && mysqli_num_rows($providerResult) > 0) {
        $providerRow = mysqli_fetch_assoc($providerResult);
        $provider_id = $providerRow['id'];

        if ($price_option === 'auto') {
            // Price is automatically calculated
            $calculationResult = calculateAverageScoreAndPrice($phonenumber, $conn);
            $price = $calculationResult['price'];
        } else {
            // Manually entered price by the user
            $price = $_POST['price'];
        }
        $calculationResult = calculateAverageScoreAndPrice($phonenumber, $conn);
        $averageScore = $calculationResult['averageScore'];

        // Check if the phone number already exists
        $checkPhoneNumberQuery = "SELECT phonenumber FROM product WHERE phonenumber = '$phonenumber'";
        $checkPhoneNumberResult = mysqli_query($conn, $checkPhoneNumberQuery);

        if (mysqli_num_rows($checkPhoneNumberResult) > 0) {
            // Phone number already exists, set an error message in PHP
            $error_message = "เบอร์ที่ต้องการเพิ่มมีอยู่ในฐานข้อมูลอยู่แล้ว!!!";
        } else {
            // Phone number does not exist, proceed with insertion
            $insertQuery = "INSERT INTO product (phonenumber, provider_id, Price, avg_score, cost)
            VALUES ('$phonenumber', '$provider_id', '$price', '$averageScore', '$cost')";

            if (mysqli_query($conn, $insertQuery)) {
                // Product added successfully, you can also display a success message
            } else {
                // An error occurred during insertion, set an error message in PHP
                $error_message = "An error occurred while adding the product. Please try again later.";
            }
        }
    } else {
        // Selected provider does not exist, set an error message in PHP
        $error_message = "Selected provider does not exist.";
    }
}


// Fetch products with their associated provider names
$query = "SELECT p.id, p.phonenumber, pr.option_value AS Provider, p.Price, p.avg_score, p.cost
          FROM product p
          JOIN providers pr ON p.provider_id = pr.id
          LEFT JOIN order_item oi ON p.id = oi.product_id
          WHERE oi.product_id IS NULL";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database error: " . mysqli_error($conn));
} else {
    $productResults = mysqli_fetch_all($result, MYSQLI_ASSOC);
}


$queryProducts = "SELECT * FROM product";
$resultProducts = mysqli_query($conn, $queryProducts);

$products = [];
while ($row = mysqli_fetch_assoc($resultProducts)) {
    $products[] = $row;
}
?>
<?php
$orderedProductsQuery = "SELECT p.id, p.phonenumber, pr.option_value AS Provider, p.Price, p.avg_score, p.cost
                      FROM product p
                      JOIN providers pr ON p.provider_id = pr.id
                      JOIN order_item oi ON p.id = oi.product_id";

$orderedProductResults = mysqli_query($conn, $orderedProductsQuery);

if (!$orderedProductResults) {
    die("Database error: " . mysqli_error($conn));
}
?>
<?php 
$orderedProductsCountQuery = "SELECT COUNT(*) as ordered_count
FROM product p
JOIN order_item oi ON p.id = oi.product_id";
$orderedProductsCountResult = mysqli_query($conn, $orderedProductsCountQuery);

if ($orderedProductsCountResult) {
$orderedProductsCountData = mysqli_fetch_assoc($orderedProductsCountResult);
$orderedProductCount = $orderedProductsCountData['ordered_count'];
} else {
die("Database error: " . mysqli_error($conn));
}





$unorderedProductsCountQuery = "SELECT COUNT(*) as unordered_count
                              FROM product p
                              LEFT JOIN order_item oi ON p.id = oi.product_id
                              WHERE oi.product_id IS NULL";
$unorderedProductsCountResult = mysqli_query($conn, $unorderedProductsCountQuery);

if ($unorderedProductsCountResult) {
    $unorderedProductsCountData = mysqli_fetch_assoc($unorderedProductsCountResult);
    $unorderedProductCount = $unorderedProductsCountData['unordered_count'];
} else {
    die("Database error: " . mysqli_error($conn));
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
    <link rel="icon" href="unnamed.png">
    <title>Product&Users Management</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ADMINISTRATION</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
              <!-- Divider -->
              <hr class="sidebar-divider my-0">

<?php include('menuli.php') ?>
          

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">User and Product</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                 
                    <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                    
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    <h2 style="font-weight: bold;">เพิ่มสินค้า</h2>
                
                </div>
             
               

                <div class="h5 mb-0 font-weight-bold text-gray-800">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="form-group">
        <label for="phonenumber">หมายเลขเบอร์โทร</label>
        <input type="text" class="form-control bg-light border-0 small"
            name="phonenumber" placeholder="กรอกเบอร์โทร" required>
    </div>
    <div class="form-group">
        <label for="Provider">ผู้ให้บริการ</label>
        <select class="custom-select custom-select-sm form-control form-control-sm"
            id="Provider" name="Provider" required>
            <?php foreach ($providerOptions as $providerOption): ?>
                <option value="<?php echo $providerOption['option_value']; ?>">
                    <?php echo $providerOption['option_value']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
    <label for="cost">ต้นทุน</label>
    <input type="text" class="form-control bg-light border-0 small" name="cost" placeholder="กรอกต้นทุน">
</div>
    <div class="form-group">
        <label>ราคา</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="price_option" id="auto_price" value="auto" checked>
            <label class="form-check-label" for="auto_price">สร้างราคาโดยอัตโนมัติ</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="price_option" id="manual_price" value="manual">
            <label class="form-check-label" for="manual_price">กรอกราคาเอง</label>
        </div>
    </div>
    
    <div class="form-group" id="price_auto">
        <!-- Hidden input field to store the calculated price -->
        <input type="hidden" name="auto_calculated_price" value="<?php echo $price; ?>">
    </div>
    
    <div class="form-group" id="price_manual" style="display: none;">
        <label for="price">ราคา</label>
        <input type="text" class="form-control bg-light border-0 small"
            name="price" placeholder="ราคา">
    </div>
    
    <button class="btn btn-success mt-2" type="submit" name="submit">ยืนยัน</button>
    <div >
                <?php 
// Display the error message if it's set
if (isset($error_message)) {
    echo '<div style="padding: 10px; border-radius: 10px; background-color:bisque; width: fit-content; color: red;">' . $error_message . '</div>';
}?>
                </div>
</form>

<script>
    // Add an event listener to the radio buttons
    const autoPriceRadio = document.getElementById("auto_price");
    const manualPriceRadio = document.getElementById("manual_price");
    const priceAutoDiv = document.getElementById("price_auto");
    const priceManualDiv = document.getElementById("price_manual");

    autoPriceRadio.addEventListener("change", function () {
        priceAutoDiv.style.display = "block";
        priceManualDiv.style.display = "none";
    });

    manualPriceRadio.addEventListener("change", function () {
        priceAutoDiv.style.display = "none";
        priceManualDiv.style.display = "block";
    });
</script>
      </div>
            </div>
            <div class="col-auto">
                <i class="fas fa-donate fa-2x text-gray-300"></i>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4" style="min-width: fit-content;">

    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
        <?php if (isset($_SESSION['success'])) : ?>
                                        <div class="success">
                                            <?php echo $_SESSION['success']; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($_SESSION['error'])) : ?>
                                        <div class="error">
                                            <?php echo $_SESSION['error']; ?>
                                        </div>
                                    <?php endif; ?>
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">  
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        <h2 style="font-weight: bold;">ค้นหาสินค้า</h2><h5>
    <span style="color: blue;">สินค้าทั้งหมด: <?php echo $unorderedProductCount + $orderedProductCount; ?></span>&nbsp;&nbsp;&nbsp;
    <span style="color: green;">สินค้าที่ยังไม่ขาย: <?php echo $unorderedProductCount; ?></span>&nbsp;&nbsp;&nbsp;
    <span style="color: red;">สินค้าที่ขายแล้ว: <?php echo $orderedProductCount; ?></span>
</h5>

                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <div class="container">
                            <h1>Product Management</h1>
                            <div class="mb-3">
                                <input type="text" id="searchBox" class="form-control" placeholder="ค้นหาด้วยเบอร์, ผู้ให้บริการ, หรือ ราคา">
                            </div>
                            <div id="noResults" class="alert alert-warning" style="display: none;">
                                ไม่พบสินค.
                            </div>
                                <table class="table table-bordered dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>เบอร์โทรศัพย์</th>
                                        <th>ผู้ให้บริการ</th>
                                        <th>ต้นทุน</th>
                                        <th>ราคา</th>
                                        <th>ค่าความมงคล</th>
                                        <th>แก้ใข</th>
                                        <th>ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productResults as $row) : ?>
                                        <tr class="product-row" style="text-align: center;">
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['phonenumber']; ?></td>
                                            <td class="provider-name"><?php echo $row['Provider']; ?></td>
                                            <td><?php echo $row['cost']; ?></td> 
                                            <td><?php echo $row['Price']; ?></td>
                                            
                                            <td><?php echo $row['avg_score']; ?></td>
                                            <td><a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                                            <td><a href="delete_product.php?id=<?php echo $row['id']; ?>">Delete</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                    
                        <div class="row no-gutters align-items-center">
    <div class="col mr-2">
        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
            <h2 style="font-weight: bold; color: red;">สินค้าที่ขายแล้ว</h2>
        </div>
        <div class="h5 mb-0 font-weight-bold text-gray-800">
            <div class="container">
                <h1>Ordered Products</h1>
              
              
                <table class="table table-bordered dataTable">
                    <thead>
                        <tr>
                        <th>ID</th>
                                        <th>เบอร์โทรศัพย์</th>
                                        <th>ผู้ให้บริการ</th>
                                        <th>ต้นทุน</th>
                                        <th>ราคา</th>
                                        <th>ค่าความมงคล</th>
                                       
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderedProductResults as $row) : ?>
                            <tr class="product-row" style="text-align: center;">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['phonenumber']; ?></td>
                                <td class="provider-name"><?php echo $row['Provider']; ?></td>
                                <td><?php echo $row['cost']; ?></td>
                                <td><?php echo $row['Price']; ?></td>
                                <td><?php echo $row['avg_score']; ?></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                        <script>
                            $(document).ready(function() {
                                $("#searchBox").keyup(function() {
                                    var searchText = $(this).val().toLowerCase();
                                    $(".product-row").each(function() {
                                        var rowData = $(this).text().toLowerCase();
                                        if (rowData.includes(searchText)) {
                                            $(this).show();
                                        } else {
                                            $(this).hide();
                                        }
                                    });

                                    if ($(".product-row:visible").length === 0) {
                                        $("#noResults").show();
                                    } else {
                                        $("#noResults").hide();
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>
                <div class="col-auto">
                    <div class="col-auto">
                        <i class="fas fa-search fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                   

                    </div>
                    <!-- /.container-fluid -->
                    </div>
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="aaw.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>