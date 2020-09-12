<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php
$_SESSION["TrackingURL"]=$_SERVER["PHP_SELF"];
//echo $_SESSION["TrackingURL"];
Confirm_Login(); ?>
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
  <title>Dashboard</title>
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
          <a href="MyProfile.php" class="nav-link"> <i class="fas fa-user text-success"></i> My Profile</a>
        </li>
        <li class="nav-item">
          <a href="Dashboard.php" class="nav-link">Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="Posts.php" class="nav-link">Posts</a>
        </li>
        <li class="nav-item">
          <a href="Categories.php" class="nav-link">Categories</a>
        </li>
        <li class="nav-item">
          <a href="Admins.php" class="nav-link">Manage Admins</a>
        </li>
        <li class="nav-item">
          <a href="Comments.php" class="nav-link">Comments</a>
        </li>
        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link" target="_blank">Live Blog</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a href="Logout.php" class="nav-link text-danger">
          <i class="fas fa-user-times"></i> Logout</a></li>
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
          <div class="col-md-12">
          <h1><i class="fas fa-cog" style="color:#27aae1;"></i> Dashboard</h1>
          </div>
          <div class="col-lg-3 mb-2">
            <a href="AddNewPost.php" class="btn btn-primary btn-block">
              <i class="fas fa-edit"></i> Add New Post
            </a>
          </div>
          <div class="col-lg-3 mb-2">
            <a href="Categories.php" class="btn btn-info btn-block">
              <i class="fas fa-folder-plus"></i> Add New Category
            </a>
          </div>
          <div class="col-lg-3 mb-2 ">
            <a href="Admins.php" class="btn btn-warning btn-block">
              <i class="fas fa-user-plus"></i> Add New Admin
            </a>
          </div>
          <div class="col-lg-3 mb-2">
            <a href="Comments.php" class="btn btn-success btn-block">
              <i class="fas fa-check"></i> Approve Comments
            </a>
          </div>

        </div>
      </div>
    </header>
    <!-- HEADER END -->

    <!-- Main Area -->
    <section class="container py-2 mb-4">
      <div class="row">
         <!-- Left Side Area Start -->
        <div class="col-lg-2 d-none d-md-block">
          <div class="card text-center bg-dark text-white mb-3">
            <div class="card-body">
              <h1 class="lead">Posts</h1>
              <h4 class="display-5">
                <i class="fab fa-readme"></i>
                <?php TotalPosts(); ?>
              </h4>
            </div>
          </div>

          <div class="card text-center bg-dark text-white mb-3">
            <div class="card-body">
              <h1 class="lead">Categories</h1>
              <h4 class="display-5">
                <i class="fas fa-folder"></i>
                <?php TotalCategories(); ?>
              </h4>
            </div>
          </div>

          <div class="card text-center bg-dark text-white mb-3">
            <div class="card-body">
              <h1 class="lead">Admins</h1>
              <h4 class="display-5">
                <i class="fas fa-users"></i>
                <?php TotalAdmins(); ?>
              </h4>
            </div>
          </div>
          <div class="card text-center bg-dark text-white mb-3">
            <div class="card-body">
              <h1 class="lead">Comments</h1>
              <h4 class="display-5">
                <i class="fas fa-comments"></i>
                <?php TotalComments(); ?>
              </h4>
            </div>
          </div>

        </div>
        <!-- Left Side Area End -->
        <!-- Right Side Area Start -->
        <div class="col-lg-10">
          <?php
           echo ErrorMessage();
           echo SuccessMessage();
           ?>
          <h1>Top Posts</h1>
          <table class="table table-striped table-hover">
            <thead class="thead-dark">
              <tr>
                <th>No.</th>
                <th>Title</th>
                <th>Date&Time</th>
                <th>Author</th>
                <th>Comments</th>
                <th>Details</th>
              </tr>
            </thead>
            <?php
            $SrNo = 0;
            global $ConnectingDB;
            $sql = "SELECT * FROM posts ORDER BY id desc LIMIT 0,6";
            $stmt=$ConnectingDB->query($sql);
            while ($DataRows=$stmt->fetch()) {
              $PostId = $DataRows["id"];
              $DateTime = $DataRows["datetime"];
              $Author  = $DataRows["author"];
              $Title = $DataRows["title"];
              $SrNo++;
             ?>
            <tbody>
              <tr>
                <td><?php echo $SrNo; ?></td>
                <td><?php echo $Title; ?></td>
                <td><?php echo $DateTime; ?></td>
                <td><?php echo $Author; ?></td>
                <td>
                    <?php $Total = ApproveCommentsAccordingtoPost($PostId);
                    if ($Total>0) {
                      ?>
                      <span class="badge badge-success">
                        <?php
                      echo $Total; ?>
                      </span>
                        <?php  }   ?>
                  <?php $Total = DisApproveCommentsAccordingtoPost($PostId);
                  if ($Total>0) {  ?>
                    <span class="badge badge-danger">
                      <?php
                      echo $Total; ?>
                    </span>
                         <?php  }  ?>
                </td>
                <td> <a target="_blank" href="FullPost.php?id=<?php echo $PostId; ?>">
                  <span class="btn btn-info">Preview</span>
                </a>
              </td>
              </tr>
            </tbody>
            <?php } ?>

          </table>

        </div>
        <!-- Right Side Area End -->


      </div>
    </section>
    <!-- Main Area End -->

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
