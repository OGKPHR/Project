<?php
if (!isset($_SESSION['userid'])) {
    header("Location: Shop.php");
    exit();
}

require_once "connection.php";

$user_id = $_SESSION['userid'];


// Function to display user information and phone numbers
$userQuery = "SELECT * FROM user WHERE id = $user_id";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Database error: " . mysqli_error($conn));
}

$userData = mysqli_fetch_assoc($userResult);

if (!$userData) {
    die("User not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Charts / ApexCharts - NiceAdmin Bootstrap Template</title>


  <!-- Favicons -->



  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
<style>
    /* Basic CSS styles for the navigation menu */
    ul.nav-menu {
        list-style-type: none;
        margin: 0;
        padding: 0;
        background-color: #1111;
        overflow: hidden;
        height: auto; /* Change the fixed height to 'auto' for responsiveness */
    }

    ul.nav-menu li {
        display: inline-block;
        margin: 0 10px; /* Add margin to space out menu items */
        position: relative; /* Add a position property */
        z-index: 999; /* Set a higher z-index value */
    }

    ul.nav-menu li a {
        display: block;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-weight: bolder;
        color: burlywood !important; /* Text color with !important */
        transition: border-radius 0.3s, background-color 0.3s, color 0.3s; /* Smooth transition */
    }

    ul.nav-menu li a:hover {
        background-color: #333; /* Background color on hover */
        border-radius: 5px; /* Add border radius on hover */
        color: white !important; /* Text color on hover with !important */
    }

    /* CSS to position the cart icon and user info to the right */
    .cart-icon {
        float: right;
    }

    .user-info {
        float: right;
        margin-top: 0px; /* Adjust the vertical alignment */
    }

     /* Media query for screens with a maximum width of 768px (e.g., tablets and phones) */
     @media (max-width: 768px) {
        ul.nav-menu {
            text-align: center; /* Center-align the menu items */
        }

        ul.nav-menu li {
            display: block;
            margin: 10px 0; /* Add margin to space out menu items vertically */
        }

        ul.nav-menu li a {
            padding: 10px 0; /* Reduce the padding for smaller screens */
        }
    }

    .modal {
        z-index: 9999;
    }

    .dropdown-menu-custom {
        z-index: 9999; /* Set a high z-index value */
    }

    ul.nav-menu li {
        display: inline-block;
        margin: 0 10px;
    }

    #navbar {
        overflow: hidden;
        background-color: rgb(255, 255, 255);
        display: flex;
        transition: height 0.3s; /* Add transition for smooth animation */
    }

    #navbar.minimized {
        height: 50px; /* Set the minimized height */
    }

    #navbar a {
        float: left;
        display: block;
        color: rgb(0, 0, 0);
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    #navbar a:hover {
        background-color: #ffffff;
        color: black;
    }

    #navbar a.active {
        background-color: #04AA6D;
        color: red;
    }
    /* CSS for the cart count */
.cart-item-count {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 3px 6px;
    font-size: 12px;
    position: relative;
    top: -10px;
    right: 5px;
}
/* Media query for screens with a maximum width of 768px (e.g., tablets and phones) */
@media (max-width: 768px) {
    ul.nav-menu {
        text-align: center; /* Center-align the menu items */
    }

    ul.nav-menu li {
        display: block;
        margin: 10px 0; /* Add margin to space out menu items vertically */
    }

    ul.nav-menu li a {
        padding: 10px 0; /* Reduce the padding for smaller screens */
    }
}
.logo img {
  max-height: 80px;
  margin-right: 6px;
}





</style>
<?php
// Check if $_SESSION['level'] is set and display the appropriate role
$userRole = isset($_SESSION['userlevel']) ? ($_SESSION['userlevel'] === 'a' ? 'แอดมิน' : ($_SESSION['userlevel'] === 'm' ? 'สมาชิก' : 'บุคคลภายนอก')) : 'บุคคลภายนอก';
?>
  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
<!-- ======= Header ======= -->
  <header style="box-shadow: 0px 5px 7px rgba(0, 0, 0, 0.297); background-color:  #111;height: 140px;min-width:700px ;" id="header" class="header fixed-top d-flex align-items-center" >

    <div class="d-flex align-items-center justify-content-between">
      <a href="Shop.php" class="logo d-flex align-items-center">
        <img src="ICON/LOGO.png" alt="">
      </a>
  
    </div><!-- End Logo -->
  
    <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
    <li class="nav-item dropdown pe-3"style="margin-right: 10px;color:white;">
            <h5><a   style="color:white;" href="Shop.php">หน้าหลัก</a></h5>
        </li>
        <li class="nav-item dropdown pe-3"style="margin-right: 10px;">
            <h5 ><a   style="color:white;" href="predict.php">ทำนายเบอร์</a></h5>
        </li>

        <!-- Add some spacing between navigation items -->
        <li class="nav-item dropdown pe-3" style="margin-right: 10px;">
        <h5><a   style="color:white;" href="#contact">ติดต่อเรา</a></h5>
        </li>

        <!-- Add some spacing between navigation items -->
        <li class="nav-item dropdown pe-3" style="margin-right: 10px;">
        <h5> <a class="nav-link" href="cart_items.php">
                <i class="fas fa-shopping-cart"></i> ตะกร้า
                <span id="cart-count" class="cart-item-count">0</span>
            </a></h5>
        </li>
    </ul>
</nav>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

    
      
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
           
         <img src="<?php echo $userData['profile_icon']; ?>"  class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2" style="color:white;"><?php echo $userData['firstname']; ?> </span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
            <h6><?php echo $userRole; ?></h6>
              <span>สวัสดี   <?php echo $userData['firstname']." "." ".$userData['lastname']; ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="my_profile.php">
                <i class="bi bi-person"></i>
                <span>โปรไฟล์ของฉัน</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="add_location.php">
                <i class="bi bi-compass"></i>
                <span>จัดการที่อยู่ในการส่ง</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
             
            <a class="dropdown-item d-flex align-items-center" href="parcellstatus.php">
              <i class="bi bi-box-seam"></i>
               สถานะการจัดส่ง
                    </a>
            </li>
            <li>
             
              <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal" data-target="#logoutModal">
              <i class="bi bi-box-arrow-right"></i>
               ออกจากระบบ
                    </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
  
 
</body>

</html><script>
    // Add an event listener to the cart icon button
    document.getElementById('cart-icon-button').addEventListener('click', function() {
        $('#cartModal').modal('show'); // Trigger the modal when the button is clicked
    });

    function updateCartCount() {
    $.ajax({
        url: 'get_cart_count.php', // Replace with the actual URL to fetch cart count
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                // Update the cart count in the HTML
                $('#cart-count').text(response.cartCount);
            }
        },
        error: function () {
            // Handle errors here
        }
    });
}

// Call the function to update cart count on page load
updateCartCount();

</script>
 <!-- Logout Modal-->
 <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="color: black; ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ออกจากระบบ</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" >คุณต้องการออกจากระบบไช่หรือไม่</div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                        <a class="btn btn-primary" href="logout.php">ไช่</a>
                    </div>
                </div>
            </div>
        </div>
        <script src="aaw.js"></script>
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Bootstrap JavaScript and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>


