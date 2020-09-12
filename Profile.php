<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<!-- Fetching Existing Data -->
<?php
    $SearchQueryParameter = $_GET["username"];
    global   $ConnectingDB;
    $sql    =  "SELECT aname,aheadline,abio,aimage FROM admins WHERE username=:userName";
    $stmt   =  $ConnectingDB->prepare($sql);
    $stmt   -> bindValue(':userName', $SearchQueryParameter);
    $stmt   -> execute();
    $Result = $stmt->rowcount();
if( $Result==1 ){
  while ( $DataRows   = $stmt->fetch() ) {
    $ExistingName     = $DataRows["aname"];
    $ExistingBio      = $DataRows["abio"];
    $ExistingImage    = $DataRows["aimage"];
    $ExistingHeadline = $DataRows["aheadline"];
  }
}else {
  $_SESSION["ErrorMessage"]="Bad Request !!";
  Redirect_to("Blog.php?page=1");
}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css"> 
       <link rel="stylesheet" href="/css/styles.css">
       <link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
       <link rel="stylesheet" href="node_modules/bootstrap-social/bootstrap-social.css">
  <title>Profile</title>
</head>
<body>
  <!-- NAVBAR -->
  <div style="height:10px; background:#27aae1;"></div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">Blogger.com</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a href="Blog.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">About Us</a>
        </li>
        <li class="nav-item">
          <a href="Blog.php" class="nav-link">Blog</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Contact Us</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Features</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <form class="form-inline d-none d-sm-block" action="Blog.php">
          <div class="form-group">
          <input class="form-control mr-2" type="text" name="Search" placeholder="Search here"value="">
          <button  class="btn btn-primary" name="SearchButton">Go</button>
          </div>
        </form>
      </ul>
      </div>
    </div>
  </nav>
    <div style="height:10px; background:#27aae1;"></div>
    <!-- NAVBAR END -->
    <!-- HEADER -->
    <header class="bg-dark text-white py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
          <h1><i class="fas fa-user text-success mr-2" style="color:#27aae1;"></i><?php echo $ExistingName; ?></h1>
          <h3><?php echo $ExistingHeadline; ?></h3>
          </div>
        </div>
      </div>
    </header>
    <!-- HEADER END -->
    <section class="container py-2 mb-4">
      <div class="row">
        <div class="col-md-3">
          <img src="images/<?php echo $ExistingImage; ?>" class="d-block img-fluid mb-3 rounded-circle" alt="">
        </div>
        <div class="col-md-9" style="min-height:400px;">
          <div class="card">
            <div class="card-body">
              <p class="lead"> <?php echo $ExistingBio; ?> </p>
            </div>

          </div>

        </div>

      </div>

    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white">
      <div class="container">
        <div class="row">
          <div class="col">
          <p class="lead text-center">Theme By | M.SURYA | <span id="year"></span> &copy; ----All right Reserved.</p>
          <p class="text-center small"><a style="color: white; text-decoration: none; cursor: pointer;" href="https://www.linkedin.com/in/m-surya-8aaa10139/" target="_blank"> This site is designed and created by surya<br></a></p>
           </div>
         </div>
      </div>
    </footer>
        <div style="height:10px; background:#27aae1;"></div>
    <!-- FOOTER END-->

    <script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
  <script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
  $('#year').text(new Date().getFullYear());
</script>
</body>
</html>
