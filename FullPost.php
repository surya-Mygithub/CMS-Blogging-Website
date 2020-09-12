<?php require_once("Includes/DB.php"); ?>
<?php require_once("Includes/Functions.php"); ?>
<?php require_once("Includes/Sessions.php"); ?>
<?php $SearchQueryParameter = $_GET["id"]; ?>
<?php
if(isset($_POST["Submit"])){
  $Name    = $_POST["CommenterName"];
  $Email   = $_POST["CommenterEmail"];
  $Comment = $_POST["CommenterThoughts"];
  date_default_timezone_set("Asia/Karachi");
  $CurrentTime=time();
  $DateTime=strftime("%B-%d-%Y %H:%M:%S",$CurrentTime);

  if(empty($Name)||empty($Email)||empty($Comment)){
    $_SESSION["ErrorMessage"]= "All fields must be filled out";
    Redirect_to("FullPost.php?id={$SearchQueryParameter}");
  }elseif (strlen($Comment)>500) {
    $_SESSION["ErrorMessage"]= "Comment length should be less than 500 characters";
    Redirect_to("FullPost.php?id={$SearchQueryParameter}");
  }else{
    // Query to insert comment in DB When everything is fine
    global $ConnectingDB;
    $sql  = "INSERT INTO comments(datetime,name,email,comment,approvedby,status,post_id)";
    $sql .= "VALUES(:dateTime,:name,:email,:comment,'Pending','OFF',:postIdFromURL)";
    $stmt = $ConnectingDB->prepare($sql);
    $stmt -> bindValue(':dateTime',$DateTime);
    $stmt -> bindValue(':name',$Name);
    $stmt -> bindValue(':email',$Email);
    $stmt -> bindValue(':comment',$Comment);
    $stmt -> bindValue(':postIdFromURL',$SearchQueryParameter);
    $Execute = $stmt -> execute();
    //var_dump($Execute);
    if($Execute){
      $_SESSION["SuccessMessage"]="Comment Submitted Successfully";
      Redirect_to("FullPost.php?id={$SearchQueryParameter}");
    }else {
      $_SESSION["ErrorMessage"]="Something went wrong. Try Again !";
      Redirect_to("FullPost.php?id={$SearchQueryParameter}");
    }
  }
} //Ending of Submit Button If-Condition
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
  <style media="screen">
  .heading{
      font-family: Bitter,Georgia,"Times New Roman",Times,serif;
      font-weight: bold;
       color: #005E90;
  }
  .heading:hover{
    color: #0090DB;
  }
  </style>
  <title>Full Post Page</title>

</head>
<body>
  <!-- NAVBAR -->
  <div style="height:10px; background:#27aae1;"></div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"> Blogger.com</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a href="Blog.php?page=1" class="nav-link">Home</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">About Us</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">Contact Us</a>
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
    <div class="container">
      <div class="row mt-4">
        <!-- Main Area Start-->
        <div class="col-sm-8 ">
          <h1>The Complete Responsive CMS Blog</h1>
          <h1 class="lead">The Complete blog by using PHP by M.SURYA</h1>
          <?php
           echo ErrorMessage();
           echo SuccessMessage();
           ?>
          <?php
          global $ConnectingDB;
          // SQL query when Searh button is active
          if(isset($_GET["SearchButton"])){
            $Search = $_GET["Search"];
            $sql = "SELECT * FROM posts
            WHERE datetime LIKE :search
            OR title LIKE :search
            OR category LIKE :search
            OR post LIKE :search";
            $stmt = $ConnectingDB->prepare($sql);
            $stmt->bindValue(':search','%'.$Search.'%');
            $stmt->execute();
          }
          // The default SQL query
          else{
            $PostIdFromURL = $_GET["id"];
            if (!isset($PostIdFromURL)) {
              $_SESSION["ErrorMessage"]="Bad Request !";
              Redirect_to("Blog.php?page=1");
            }
            $sql  = "SELECT * FROM posts  WHERE id= '$PostIdFromURL'";
            $stmt =$ConnectingDB->query($sql);
            $Result=$stmt->rowcount();
            if ($Result!=1) {
              $_SESSION["ErrorMessage"]="Bad Request !";
              Redirect_to("Blog.php?page=1");
            }

          }
          while ($DataRows = $stmt->fetch()) {
            $PostId          = $DataRows["id"];
            $DateTime        = $DataRows["datetime"];
            $PostTitle       = $DataRows["title"];
            $Category        = $DataRows["category"];
            $Admin           = $DataRows["author"];
            $Image           = $DataRows["image"];
            $PostDescription = $DataRows["post"];
          ?>
          <div class="card">
            <img src="Uploads/<?php echo htmlentities($Image); ?>" style="max-height:450px;" class="img-fluid card-img-top" />
            <div class="card-body">
              <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
              <small class="text-muted">Category: <span class="text-dark"> <a href="Blog.php?category=<?php echo htmlentities($Category); ?>"> <?php echo htmlentities($Category); ?> </a></span> & Written by <span class="text-dark"> <a href="Profile.php?username=<?php echo htmlentities($Admin); ?>"> <?php echo htmlentities($Admin); ?></a></span> On <span class="text-dark"><?php echo htmlentities($DateTime); ?></span></small>
            <hr>
              <p class="card-text">
                <?php echo nl2br($PostDescription); ?></p>
            </div>
          </div>
          <br>
          <?php   } ?>
          <!-- Comment Part Start -->
          <!-- Fetching existing comment START  -->
          <span class="FieldInfo">Comments</span>
          <br><br>
        <?php
        global $ConnectingDB;
        $sql  = "SELECT * FROM comments
         WHERE post_id='$SearchQueryParameter' AND status='ON'";
        $stmt =$ConnectingDB->query($sql);
        while ($DataRows = $stmt->fetch()) {
          $CommentDate   = $DataRows['datetime'];
          $CommenterName = $DataRows['name'];
          $CommentContent= $DataRows['comment'];
        ?>
  <div>
    <div class="media CommentBlock">
      <img class="d-block img-fluid align-self-start" src="images/comment.png" alt="">
      <div class="media-body ml-2">
        <h6 class="lead"><?php echo $CommenterName; ?></h6>
        <p class="small"><?php echo $CommentDate; ?></p>
        <p><?php echo $CommentContent; ?></p>
      </div>
    </div>
  </div>
  <hr>
  <?php } ?>

        <!--  Fetching existing comment END -->

          <div>
            <form class="" action="FullPost.php?id=<?php echo $SearchQueryParameter ?>" method="post">
              <div class="card mb-3">
                <div class="card-header">
                  <h5 class="FieldInfo">Share your thoughts about this post</h5>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                      </div>
                    <input class="form-control" type="text" name="CommenterName" placeholder="Name" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                      </div>
                    <input class="form-control" type="text" name="CommenterEmail" placeholder="Email" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <textarea name="CommenterThoughts" class="form-control" rows="6" cols="80"></textarea>
                  </div>
                  <div class="">
                    <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
            <!-- Comment Part End -->
        </div>
        <!-- Main Area End-->

        <!-- Side Area Start -->
        <div class="col-sm-4">
          <div class="card mt-4">
            <div class="card-body">
              <img src="images/startblog.png" class="d-block img-fluid mb-3" alt="">
              <div class="text-center">
							If you’re a writer, it makes perfect sense: You can use a blog to serve as your author platform, market your work or find new freelance writing clients. Blogging is also a great way to experiment with your writing style.If you’re searching for a good way to share your thoughts, feelings and expertise, now is a great time to start a blog.This is the age of content — people are always looking for more to absorb, and your unique voice has a place on the vast, limitless interwebs, too. 
              </div>
            </div>
          </div>
          <br>
          <div class="card">
            <div class="card-header bg-dark text-light">
              <h2 class="lead">LOGIN!</h2>
            </div>
            <div class="card-body">
              <button type="button" class="btn btn-danger btn-block text-center text-white mb-4" name="button"><a href="Login.php">Login</a></button>
            </div>
          </div>
          <br>
          <div class="card">
            <div class="card-header bg-primary text-light">
              <h2 class="lead">Categories</h2>
              </div>
              <div class="card-body">
                <?php
                global $ConnectingDB;
                $sql = "SELECT * FROM category ORDER BY id desc";
                $stmt = $ConnectingDB->query($sql);
                while ($DataRows = $stmt->fetch()) {
                  $CategoryId = $DataRows["id"];
                  $CategoryName=$DataRows["title"];
                 ?>
                <a href="Blog.php?category=<?php echo $CategoryName; ?>"> <span class="heading"> <?php echo $CategoryName; ?></span> </a><br>
               <?php } ?>
            </div>
          </div>
          <br>
          <div class="card">
            <div class="card-header bg-info text-white">
              <h2 class="lead"> Recent Posts</h2>
            </div>
            <div class="card-body">
              <?php
              global $ConnectingDB;
              $sql= "SELECT * FROM posts ORDER BY id desc LIMIT 0,5";
              $stmt= $ConnectingDB->query($sql);
              while ($DataRows=$stmt->fetch()) {
                $Id     = $DataRows['id'];
                $Title  = $DataRows['title'];
                $DateTime = $DataRows['datetime'];
                $Image = $DataRows['image'];
              ?>
              <div class="media">
                <img src="Uploads/<?php echo htmlentities($Image); ?>" class="d-block img-fluid align-self-start"  width="90" height="94" alt="">
                <div class="media-body ml-2">
                <a style="text-decoration:none;" href="FullPost.php?id=<?php echo htmlentities($Id) ; ?>" target="_blank">  <h6 class="lead"><?php echo htmlentities($Title); ?></h6> </a>
                  <p class="small"><?php echo htmlentities($DateTime); ?></p>
                </div>
              </div>
              <hr>
              <?php } ?>
            </div>
          </div>

        </div>
        <!-- Side Area End -->
      </div>

    </div>

    <!-- HEADER END -->
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