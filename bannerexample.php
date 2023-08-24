<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Auto Slideshow Banner with Bootstrap</title>
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
  </style>
</head>
<body>
  <?php 
    include('connection.php'); 
    $query = "SELECT * FROM uploadfile" or die("Error: " . mysqli_error());  
    $result = mysqli_query($conn, $query); 
  ?>
  <div class="banner">
    <div id="carouselExample" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <?php
          $result = mysqli_query($conn, $query);
          $active = true; // เพื่อให้สไลด์แรกเป็น active
          while ($row = mysqli_fetch_array($result)) {
            echo "<div class='carousel-item" . ($active ? " active" : "") . "'>";
            echo "<img src='fileupload/" . $row['fileupload'] . "' class='d-block w-100'>";
            echo "</div>";
            $active = false; // ปิดการตั้งค่าสไลด์แรกเป็น active
          }
        ?>
      </div>
      <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev" >
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
</body>
</html>
