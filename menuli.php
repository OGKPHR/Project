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

// Check if $_SESSION['level'] is set and display the appropriate role
$userRole = isset($userData ['userlevel']) ? ($userData ['userlevel'] === 'a' ? 'แอดมิน' : ($userData ['userlevel'] === 'm' ? 'สมาชิก' : 'บุคคลภายนอก')) : 'บุคคลภายนอก';
$notificationCountQuery = "SELECT COUNT(*) as notification_count 
                           FROM order_table 
                           WHERE  id NOT IN (SELECT order_id FROM parcel_code)";
$notificationCountResult = mysqli_query($conn, $notificationCountQuery);

$notificationCount = 0; // Default count

if ($notificationCountResult) {
    $row = mysqli_fetch_assoc($notificationCountResult);
    $notificationCount = $row['notification_count'];
}
?>

<!-- Google Fonts -->

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
<style>/* Add this CSS to your stylesheet */
.navbar-nav {
    position: fixed;
    top: 0;
    width: 250px; /* Adjust the width as needed */
    height: 100%; /* Adjust the height as needed */
    background-color: #f8f9fc; /* Set the desired background color */
    overflow-y: auto; /* Add scroll if the menu items exceed the height */
}

/* Adjust the main content margin to make space for the fixed sidebar */
#content {
    margin-left: 250px; /* Same as the width of the sidebar */
}
/* Style for the user icon */
.user-icon {
    width: 50px; /* Adjust the size as needed */
    height: 50px; /* Adjust the size as needed */
}
#acc{
background-color: bisque;
border-radius: 5px;
border:2px solid black;
}
.notification-count,
.email-count {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    position: absolute;
    top: -8px;
    right: -10px;
}
</style>
<li id="acc" class="nav-item dropdown pe-3 ">
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="<?php echo $userData['profile_icon']; ?>" class="rounded-circle user-icon">
        <h5 style="color:black; padding-left:20px;  "><?php echo $userData['firstname']; ?></h5>
    </a>
<!-- Container for notification bell and mail icons -->
<div class="nav-item" style="display: flex; align-items: center;">
    <!-- Notification bell icon as a link to parcelManage.php -->
    <a class="nav-link" href="parcelManage.php">
    <i class="fas fa-bell" style="font-size: 20px; color: blue; position: relative;">
        <span class="notification-count"><?php echo $notificationCount; ?></span>
    </i>
</a>

<a class="nav-link" href="your_email_page.php">
    <i class="fas fa-envelope" style="font-size: 20px; color: blue; margin-left: 20px; position: relative;">
        <span class="email-count">3</span> <!-- Update with the actual email count -->
    </i>
</a>

</div>
<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
  <li class="dropdown-header" style="color:black;">
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
   
    <a class="dropdown-item d-flex align-items-center" href="#" data-toggle="modal" data-target="#logoutModal">
    <i class="bi bi-box-arrow-right"></i>
     ออกจากระบบ
          </a>
  </li>
  <hr class="sidebar-divider">
</ul><!-- End Profile Dropdown Items -->
</li><!-- End Profile Nav -->
<!-- Nav Item - Dashboard -->
<hr class="sidebar-divider">
<li class="nav-item active">
    <a class="nav-link" href="Addproduct.php">
        <i class="fas fa-fw fa-cogs"></i>
        <span>ProductManage</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item active">
    <a class="nav-link" href="banner.php">
        <i class="fas fa-fw fa-clipboard-list"></i>
        <span>BannerManage</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item active">
    <a class="nav-link" href="UserManage.php">
        <i class="fas fa-fw fa-user"></i>
        <span>UserManage</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item active">
    <a class="nav-link" href="ProviderManage.php">
        <i class="fas fa-fw fa-signal"></i>
        <span>ProviderManage</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item active">
<a class="nav-link" href="NumberMeaningsManage.php">
        <i class="fas fa-fw fa-list"></i>
        <span>MeaningManage</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item active">
<a class="nav-link" href="process_payment.php">
        <i class="fas fa-fw fa-donate"></i>
        <span>PaymentManage</span></a>
</li>
<hr class="sidebar-divider">    
<li class="nav-item active">
<a class="nav-link" href="parcelManage.php">
        <i class="fas fa-fw fa-box"></i>
        <span>ParcelManage</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item active">
<a class="nav-link" href="transportManage.php">
        <i class="fas fa-fw fa-donate"></i>
        <span>TransportManage</span></a>
</li>
<hr class="sidebar-divider">         
<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Bootstrap JavaScript and jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
