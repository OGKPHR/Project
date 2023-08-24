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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lucky Phone Number Shop</title>
    <link rel="icon" href="unnamed.png">
    <link rel="stylesheet" href="shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .banner {
            max-height: 500px;
            overflow: hidden;
        }

        .slide img {
            width: 100%;
            height: auto;
            display: block;

        }

        .carousel-img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="topnav">
                <div class="logo-nav">
                    <figure>
                        <a href="Shop.php">
                            <img src="logo.png">

                        </a>
                    </figure>
                </div>

                <ul class="nav-menu">
                    <li class="cate-mobile">
                        <a href="#">เมนูหมวดหมู่เบอร์ <i class="fa fa-caret-right"></i></a>
                        <ul class="cateMobile">
                            <li><a href="https://berhoro.com/สินค้า"> <span>เบอร์ทั้งหมด</span> <span>50781</span></a>
                            </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลมาใหม่"> <span>เบอร์มงคลมาใหม่</span>
                                    <span>963</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลแนะนำ"> <span>เบอร์มงคลแนะนำ</span>
                                    <span>356</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์หงส์-เสริมโชคลาภ-เงินทอง"> <span>เบอร์หงส์
                                        เสริมโชคลาภ เงินทอง</span> <span>2421</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มังกร-เสริมโชคลาภ-อำนาจวาสนา-เงินทอง">
                                    <span>เบอร์มังกร เสริมโชคลาภ อำนาจวาสนา เงินทอง</span> <span>2601</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์กวนอู-เทพแห่งชัยชนะ-ความซื่อสัตย์">
                                    <span>เบอร์กวนอู เทพแห่งชัยชนะ ความซื่อสัตย์</span> <span>3636</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์คู่รัก-เบอร์คู่เหมือน"> <span>เบอร์คู่รัก
                                        เบอร์คู่เหมือน</span> <span>13615</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคล-เจ้าของกิจการ"> <span>เบอร์มงคล
                                        เจ้าของกิจการ</span> <span>9866</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเศรษฐี-4-5-6-9-เลขการเงิน">
                                    <span>เบอร์มงคลเศรษฐี 4 5 6 9 เลขการเงิน</span> <span>251</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมความรัก-การเงิน-การงาน">
                                    <span>เบอร์มงคลเสริมความรัก การเงิน การงาน</span> <span>11958</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคล-สุขภาพดี-มั่งมี-ศรีสุข"> <span>เบอร์มงคล
                                        สุขภาพดี มั่งมี ศรีสุข</span> <span>7029</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมบารมี"> <span>เบอร์มงคลเสริมบารมี
                                    </span> <span>3716</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมธุรกิจงานประมูล-การแข่งขัน-ล็อบบี้">
                                    <span>เบอร์มงคลเสริมธุรกิจงานประมูล การแข่งขัน ล็อบบี้</span> <span>8676</span></a>
                            </li>
                            <li><a href="https://berhoro.com/สินค้า/539-935-คู่เลขแห่งการเลื่อนขั้น-เลื่อนตำแหน่ง">
                                    <span>539 935 คู่เลขแห่งการเลื่อนขั้น เลื่อนตำแหน่ง</span> <span>1983</span></a>
                            </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมการค้าขาย">
                                    <span>เบอร์มงคลเสริมการค้าขาย</span> <span>11502</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมเสน่ห์">
                                    <span>เบอร์มงคลเสริมเสน่ห์</span> <span>8795</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมโชคลาภ">
                                    <span>เบอร์มงคลเสริมโชคลาภ</span> <span>12265</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลขายของออนไลน์">
                                    <span>เบอร์มงคลขายของออนไลน์</span> <span>12766</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมสติปัญญา-การเรียน-ครู-อาจารย์">
                                    <span>เบอร์มงคลเสริมสติปัญญา การเรียน ครู อาจารย์</span> <span>6070</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลนารีอุปถัมภ์">
                                    <span>เบอร์มงคลนารีอุปถัมภ์</span> <span>13662</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคล-สวยและรวยมาก"> <span>เบอร์มงคล
                                        สวยและรวยมาก</span> <span>7290</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมอาชีพวิศวกร-สถาปนิก">
                                    <span>เบอร์มงคลเสริมอาชีพวิศวกร สถาปนิก</span> <span>8141</span></a> </li>
                            <li><a
                                    href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมอาชีพ-ธุรกิจสีเทา-ใช้ไหวพริบเล่ห์เหลี่ยม">
                                    <span>เบอร์มงคลเสริมอาชีพ ธุรกิจสีเทา ใช้ไหวพริบเล่ห์เหลี่ยม</span>
                                    <span>13074</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริมอาชีพค้าขายที่ดิน-อสังหาริมทรัพย์">
                                    <span>เบอร์มงคลเสริมอาชีพค้าขายที่ดิน อสังหาริมทรัพย์</span> <span>8663</span></a>
                            </li>
                            <li><a
                                    href="https://berhoro.com/สินค้า/เบอร์มงคลอาชีพ-ขายอุปกรณ์ไอที-ขายสินค้ามือ2-ขายวัตถุมงคล">
                                    <span>เบอร์มงคลอาชีพ ขายอุปกรณ์ไอที ขายสินค้ามือ2 ขายวัตถุมงคล</span>
                                    <span>8380</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคลเสริม-ธุรกิจนำเข้า-ส่งออก">
                                    <span>เบอร์มงคลเสริม ธุรกิจนำเข้า ส่งออก</span> <span>14188</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคล-ผู้บริหาร-หัวหน้างาน"> <span>เบอร์มงคล
                                        ผู้บริหาร หัวหน้างาน</span> <span>6832</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์มงคล-เจ้าสัว-168"> <span>เบอร์มงคล เจ้าสัว
                                        168</span> <span>447</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์โฟร์"> <span>เบอร์โฟร์</span>
                                    <span>33</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์ตอง"> <span>เบอร์ตอง</span>
                                    <span>1270</span></a> </li>
                            <li><a href="https://berhoro.com/สินค้า/เบอร์-xyxy-xxyy-เบอร์ห่าม"> <span>เบอร์ xyxy xxyy
                                        เบอร์ห่าม</span> <span>637</span></a> </li>
                            <div class="hide-button"><i class="fas fa-angle-double-left"></i></div>
                        </ul>
                    </li>
                    <li><a href="Shop.php">หน้าหลัก</a></li>
                    <li><a target="_blank" href="https://berhoro.com/ทำนายเบอร์">ทำนายเบอร์</a></li>
                    <li><a href="https://berhoro.com/ค้นหาเบอร์จากความหมาย">ค้นหาเบอร์จากความหมาย</a></li>
                    <li><a target="_blank" href="https://berhoro.com/บทความ">บทความ</a></li>
                    <li><a href="https://berhoro.com/วิธีการสั่งซื้อ">วิธีการสั่งซื้อ</a></li>
                    <li><a href="https://berhoro.com/เกี่ยวกับเรา">เกี่ยวกับเรา</a></li>
                    <li><a href="https://berhoro.com/การจัดส่ง">เช็คการจัดส่งสินค้า</a></li>
                    <li><a href="https://berhoro.com/ติดต่อเรา">ติดต่อเรา</a></li>
                    <li class="menu-cart">
                        <a href="#" class="cart">
                            <i class="fas fa-shopping-basket"></i>
                            <div class="num-shop-cart">
                                <span class="cartAmount">0</span>
                            </div>
                        </a>
                        <div class="cart-hover">
                            <ul>
                                <li class="head-hover">
                                    <p>
                                        จำนวนสินค้า
                                        <span class="cartAmount">0</span>
                                        เบอร์ /
                                        <span class="cartPrice">0 </span>
                                        บาท
                                    </p>
                                </li>
                                <div class="list-item">
                                </div>
                                <li class="total-cart"><a href="/รายการของฉัน"><i
                                            class="fas fa-cart-arrow-down"></i><span>สรุปรายการเพื่อสั่งซื้อสินค้า</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="buttonX"><i class="fas fa-times"></i></li>
                </ul>

                <div class="group-mobile-menu">
                    <div class="cart-mobile">
                        <li><a href="#" class="cart"><i class="fas fa-shopping-basket"></i></a></li>
                        <div class="num-shop-cart"><span class="cartAmount">0</span></div>
                    </div>
                    <div class="cart-hover">
                        <ul>
                            <li class="head-hover">
                                <p>
                                    จำนวนสินค้า
                                    <span class="cartAmount">0</span>
                                    เบอร์ /
                                    <span class="cartPrice">0 </span>
                                    บาท
                                </p>
                            </li>
                            <div class="list-item">
                            </div>
                            <li class="total-cart"><a href="/รายการของฉัน"><i
                                        class="fas fa-cart-arrow-down"></i><span>สรุปรายการเพื่อสั่งซื้อสินค้า</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="hamberger">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <?php
    include('connection.php');
    $query = "SELECT * FROM uploadfile" or die("Error: " . mysqli_error());
    $resultbanner = mysqli_query($conn, $query);
    ?>
    <div class="banner">
        <div id="carouselExample" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" style="max-height:inherit; padding-top:5%;">
                <?php
                $resultbanner = mysqli_query($conn, $query);
                $active = true; // เพื่อให้สไลด์แรกเป็น active
                while ($row = mysqli_fetch_array($resultbanner)) {
                    echo "<div class='carousel-item " . ($active ? " active" : "") . "'>";
                    echo "<div class='carousel-img-container'><img style='max-height:500px;' src='fileupload/" . $row['fileupload'] . "' class='d-block w-100'></div>";
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Auto slide the carousel every 2 seconds
        $('#carouselExample').carousel({
            interval: 2000
        });
    </script>

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




    <div class="grid-content">
        <section class="product-listings">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product">
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

    <script src="shop.js"></script>
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