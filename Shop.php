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
$providerOptionsQuery = "SELECT * FROM providers";
$providerOptionsResult = mysqli_query($conn, $providerOptionsQuery);
$providerOptions = mysqli_fetch_all($providerOptionsResult, MYSQLI_ASSOC);

$typeOptionsQuery = "SELECT * FROM TYPES";
$typeOptionsResult = mysqli_query($conn, $typeOptionsQuery);
$typeOptions = mysqli_fetch_all($typeOptionsResult, MYSQLI_ASSOC);
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

<body style="padding-top: 0px;">


    <div id="navbar" >
    <img style="width: 300px;height: 100px ;margin-top: 50px;" src="ICON\LOGO.png" href="Shop.php">
       
          
    <div class="user-info">
    <ul class="navbar-nav ml-auto">
              
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow" style="align-content: right;">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['fname']; ?></span>
                                <img style="width: 50px;height: 50px alig;"class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                          
                                
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>
       </div>
    </div>
    </ul>
</div>
<div class="barbox"></div>
    <div class="content">

        <?php
        include('connection.php');
        $query = "SELECT * FROM uploadfile" or die("Error: " . mysqli_error());
        $resultbanner = mysqli_query($conn, $query);
        ?>
        <div class="banner">
            <div id="carouselExample" class="carousel slide" data-ride="carousel"style="margin-top">
                <div class="carousel-inner">
                    <?php
                    $resultbanner = mysqli_query($conn, $query);
                    $active = true; // เพื่อให้สไลด์แรกเป็น active
                    while ($row = mysqli_fetch_array($resultbanner)) {
                        echo "<div class='carousel-item " . ($active ? " active" : "") . "'>";
                        echo "<div class='carousel-img-container'><img style='min-height: 500px;max-height: 500px;' src='fileupload/" . $row['fileupload'] . "' class='d-block w-100'></div>";
                        echo "</div>";
                        $active = false; // ปิดการตั้งค่าสไลด์แรกเป็น active
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
    // Auto slide the carousel every 2 seconds
                                                                               $('#carouselExample').carousel({
                                                                                   interval: 2000
    });
        </script>
        <div class="container">

            <div class="process-number mt-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="number" placeholder="กรอกเบอร์">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">วิเคราะห์เบอร์</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">

                        <input type="text" class="form-control" name="phonenumber" placeholder="ค้นหาเบอร์">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>ประเภท</label>
                        <select class="custom-select" id="TYPES" name="TYPES" required>
                            <?php foreach ($typeOptions as $typeOption): ?>
                                <option value="<?php echo $typeOption['option_value']; ?>"><?php echo $typeOption['option_value']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>จำนวน</label>
                        <select class="custom-select" id="sumnumber">
                            <option value="50">50</option>
                            <option value="60">60</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>เครือข่าย</label>
                        <select class="custom-select" id="Provider" name="Provider" required>
                            <?php foreach ($providerOptions as $providerOption): ?>
                                <option value="<?php echo $providerOption['option_value']; ?>"><?php echo $providerOption['option_value']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>เรียงลำดับ</label>
                        <select class="custom-select" id="sortnumber" name="sortnumber">
                            <option value="maxtomin">ราคาเรียงจากน้อยไปมาก</option>
                            <option value="mintomax">ราคาเรียงจากมากไปน้อย</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>ช่วงราคา</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="price1" placeholder="ต่ำสุด">
                            <input type="text" class="form-control" name="price2" placeholder="สูงสุด">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>ชุดเลขที่ชอบ</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="set1" placeholder="เลขต่ำสุด">
                            <input type="text" class="form-control" name="set2" placeholder="เลขสูงสุด">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3 button-num">
                        <label>ตัวเลขที่ชอบ</label>
                        <?php for ($i = 0; $i < 10; $i++): ?>
                            <button class="btn btn-light" data-favnum="<?php echo $i; ?>"><?php echo $i; ?></button>
                        <?php endfor; ?>
                    </div>
                    <div class="col-md-6 mb-3 button-num">
                        <label>ตัวเลขที่ไม่ชอบ</label>
                        <?php for ($i = 0; $i < 10; $i++): ?>
                            <button class="btn btn-light" data-favnum="<?php echo $i; ?>"><?php echo $i; ?></button>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>




        <main>
            <section class="product-listings">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product" style="max-width: 200px;max-height: fit-content;">
                        <img src="<?php echo $row['Provider']; ?>.png" alt="Product">
                        <h2>
                            <?php echo $row['phonenumber']; ?>
                        </h2>
                        <p>
                            <?php echo $row['TYPES']; ?>
                        </p>
                        <span class="price">฿
                            <?php echo $row['Price']; ?>
                        </span>
                        <button>เพิ่มลงตะกร้า</button>
                    </div>
                <?php endwhile; ?>
            </section>
        </main>

        <section class="cart">
            <h2>ตะกร้าสินค้า</h2>
            <ul id="cart-items">
                <!-- Cart items will go here -->
            </ul>
            <p>ราคารวม: <span id="cart-total">฿0.00</span></p>
            <button id="clear-cart-btn">เคลียร์ตะกร้า</button>
        </section>

        <footer>
            <p>&copy; 2023 Lucky Phone Number Shop. All rights reserved.</p>
        </footer>

    </div>
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
    <script src="Shop.js"></script>
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
    <script>
                                                                               window.onscroll = function() {myFunction()};

                                                                               var navbar = document.getElementById("navbar");
                                                                               var sticky = navbar.offsetTop;

                                                                               function myFunction() {
  if (window.pageYOffset >= sticky) {
                                                                                   navbar.classList.add("sticky")
                                                                               } else {
                                                                                   navbar.classList.remove("sticky");
  }
}
    </script>

</body>

</html>