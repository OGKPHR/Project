<?php
session_start();
require_once "connection.php";

if (isset($_POST['addNumberMeaning'])) {
    $newPair = $_POST['newPair'];
    $newMeaning = $_POST['newMeaning'];

    $query = "INSERT INTO numbermeanings (Pair, Meaning) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $newPair, $newMeaning);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Number Meaning added successfully";
    } else {
        $_SESSION['error_message'] = "Error adding Number Meaning: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    // Modify this query to search for 'Pair' and 'Meaning' columns in your numbermeanings table.
    $searchQuery = "SELECT * FROM numbermeanings WHERE Pair LIKE '%$query%' OR Meaning LIKE '%$query%'";
    $searchResult = mysqli_query($conn, $searchQuery);

    $results = array();
    while ($row = mysqli_fetch_assoc($searchResult)) {
        $results[] = $row;
    }

    echo json_encode($results);
}
if (isset($_POST['addNumberMeaning'])) {
    $newPair = $_POST['newPair'];
    $newMeaning = $_POST['newMeaning'];

    // Check if the pair already exists in the table
    $checkQuery = "SELECT * FROM numbermeanings WHERE Pair = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, 's', $newPair);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        $_SESSION['error_message'] = "Pair already exists in the table";
    } else {
        // Pair doesn't exist, insert the new meaning
        $insertQuery = "INSERT INTO numbermeanings (Pair, Meaning, finance, work, fortune, love, health, utterance, mind, charm, personality, learning) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, 'ssiiiiiiiiii', $newPair, $newMeaning, $_POST['finance'], $_POST['work'], $_POST['fortune'], $_POST['love'], $_POST['health'], $_POST['utterance'], $_POST['mind'], $_POST['charm'], $_POST['personality'], $_POST['learning']);

        if (mysqli_stmt_execute($insertStmt)) {
            $_SESSION['success_message'] = "Number Meaning added successfully";
        } else {
            $_SESSION['error_message'] = "Error adding Number Meaning: " . mysqli_error($conn);
        }

        mysqli_stmt_close($insertStmt);
    }
    mysqli_stmt_close($checkStmt);
}

if (isset($_GET['delete'])) {
    $deleteID = $_GET['delete'];

    $deleteQuery = "DELETE FROM numbermeanings WHERE ID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, 'i', $deleteID);

    if (mysqli_stmt_execute($deleteStmt)) {
        $_SESSION['success_message'] = "Number Meaning deleted successfully";
    } else {
        $_SESSION['error_message'] = "Error deleting Number Meaning: " . mysqli_error($conn);
    }

    mysqli_stmt_close($deleteStmt);
}

$query = "SELECT * FROM numbermeanings";
$result = mysqli_query($conn, $query);
$numberMeanings = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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

<!-- Nav Item - Dashboard -->
<?php include('menuli.php') ?>
          

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4" style="min-width: fit-content;">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <h2 style="font-weight: bold;">ระบบจัดการผู้ให้บริการ</h2>
                                    </div>
                                    <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Number Meanings Management</h1>

<?php


if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<div id="noResults" class="alert alert-warning" style="display: none;">
    Pairs range is 00-99.
</div>

<div class="form-group">
    <input type="text" id="searchInput" class="form-control" placeholder="Search by Pair or Meaning">
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#searchInput').on('input', function () {
        var searchText = $(this).val().toLowerCase();

        $('#numberMeaningsTableBody tr').each(function () {
            var pairData = $(this).find('td:nth-child(2)').text().toLowerCase(); // Index 2 for Pair column
            var meaningData = $(this).find('td:nth-child(3)').text().toLowerCase(); // Index 3 for Meaning column

            if (pairData.indexOf(searchText) !== -1 || meaningData.indexOf(searchText) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Show/hide the "Not Found" message based on the visibility of rows
        var visibleRows = $('#numberMeaningsTableBody tr:visible').length;
        var noResults = $('#noResults');
        noResults.css('display', visibleRows === 0 ? 'block' : 'none');
    });
});
</script>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add Number Meaning</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="newPair">Pair</label>
                    <input type="text" class="form-control" id="newPair" name="newPair" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="newMeaning">Meaning</label>
                    <input type="text" class="form-control" id="newMeaning" name="newMeaning" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="finance">Finance</label>
                    <input type="number" class="form-control" id="finance" name="finance" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="work">Work</label>
                    <input type="number" class="form-control" id="work" name="work" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="fortune">Fortune</label>
                    <input type="number" class="form-control" id="fortune" name="fortune" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="love">Love</label>
                    <input type="number" class="form-control" id="love" name="love" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="health">Health</label>
                    <input type="number" class="form-control" id="health" name="health" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="utterance">Utterance</label>
                    <input type="number" class="form-control" id="utterance" name="utterance" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="mind">Mind</label>
                    <input type="number" class="form-control" id="mind" name="mind" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="charm">Charm</label>
                    <input type="number" class="form-control" id="charm" name="charm" required>
                </div>
                <div class="form-group col-md-1">
                    <label for="personality">Personality</label>
                    <input type="number" class="form-control" id="personality" name="personality" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="learning">Learning</label>
                    <input type="number" class="form-control" id="learning" name="learning" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" name="addNumberMeaning">Add</button>
        </form>
    </div>
</div>
<div class="table-responsive">
  
<!-- Display the fetched number meanings in a table -->
<table class="table table-bordered">
    <thead>
        <tr>
            
            <th>ID</th>
            <th>คู่</th>
            <th>ความหมาย</th>
            <th>การเงิน</th>
            <th>การงาน</th>
            <th>โชคลาภ</th>
            <th>ความรัก</th>
            <th>สุขภาพ</th>
            <th>คำพูด</th>
            <th>จิตใจ</th>
            <th>เสน่ห์</th>
            <th>บุคลิกภาพ</th>
            <th>การเรียนรู้</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody id="numberMeaningsTableBody">
        <?php foreach ($numberMeanings as $numberMeaning): ?>
            <tr>
            <td><?php echo $numberMeaning['ID']; ?></td>
                <td><?php echo $numberMeaning['Pair']; ?></td>
                <td>
    <?php
    $meaning = $numberMeaning['Meaning'];
    $shortenedMeaning = substr($meaning, 0, 30); // Display the first 30 characters as a preview
    echo $shortenedMeaning;
    ?>
    <a href="#" data-toggle="modal" data-target="#viewMeaningModal<?php echo $numberMeaning['ID']; ?>">ดูเต็ม</a>
</td>
                <!-- Add columns for each meaning here -->
                <td><?php echo $numberMeaning['finance']; ?></td>
                <td><?php echo $numberMeaning['work']; ?></td>
                <td><?php echo $numberMeaning['fortune']; ?></td>
                <td><?php echo $numberMeaning['love']; ?></td>
                <td><?php echo $numberMeaning['health']; ?></td>
                <td><?php echo $numberMeaning['utterance']; ?></td>
                <td><?php echo $numberMeaning['mind']; ?></td>
                <td><?php echo $numberMeaning['charm']; ?></td>
                <td><?php echo $numberMeaning['personality']; ?></td>
                <td><?php echo $numberMeaning['learning']; ?></td>

                <td>
<!-- Add an edit button for each row -->
<a href="EditNumberMeaning.php?id=<?php echo $numberMeaning['ID']; ?>">Edit</a>

</td>
<td>
    <a href="?delete=<?php echo $numberMeaning['ID']; ?>" class="btn btn-danger">Delete</a>
</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>


<!-- Edit Number Meaning Modal -->
<div class="modal fade" id="editNumberMeaningModal" tabindex="-1" role="dialog"
     aria-labelledby="editNumberMeaningModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNumberMeaningModalLabel">Edit Number Meaning</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editNumberMeaningForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" id="editNumberMeaningId" name="editNumberMeaningId">
                    <div class="form-group">
                        <input type="text" id="editedPair" name="editedPair" class="form-control"
                               placeholder="Edited Pair" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="editedMeaning" name="editedMeaning" class="form-control"
                               placeholder="Edited Meaning" required>
                    </div>
                    <!-- Add input fields for other data fields here -->
                    <div class="form-group">
                        <input type="text" id="editedFinance" name="editedFinance" class="form-control"
                               placeholder="Edited Finance" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="editedWork" name="editedWork" class="form-control"
                               placeholder="Edited Work" required>
                    </div>
                   
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modals for displaying full meanings -->
<?php foreach ($numberMeanings as $numberMeaning): ?>
    <div class="modal fade" id="viewMeaningModal<?php echo $numberMeaning['ID']; ?>" tabindex="-1" role="dialog"
         aria-labelledby="viewMeaningModalLabel<?php echo $numberMeaning['ID']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMeaningModalLabel<?php echo $numberMeaning['ID']; ?>">ความหมาย</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $numberMeaning['Meaning']; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
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
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
