<?php
include_once('../../db.php');

// Check If User Sign-up?, Don't Open This Page.
session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])) {
  header("Location: dashboard.php");
  exit();
}
date_default_timezone_set('Africa/Cairo');

if (isset($_POST['sign'])) {
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);;
  $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);;

  // IF Email Is Exisit.
  $sql = "SELECT email FROM `users` WHERE email = '$email'";
  $answer = mysqli_fetch_row(mysqli_query($db, $sql));

  if (isset($answer)) {
    $error = '- Email Is Exisit.';
  } else {

    // IF User Accept My Terms and Conditions.
    if (isset($_POST['accept'])) {
      $random_user = rand(100, 10000);
      $img_profile = $random_user . '.png';
      copy('../../images/user_profile/someone.png', '../../images/user_profile/' . $random_user . '.png');
      $sql = "INSERT INTO `users` (`id`, `username`, `img_profile`, `name`, `email`, `password`, `permission`, `time_create`) VALUES (NULL, '$random_user', '$img_profile', '$name', '$email', '$password', 'user', current_timestamp());";
      $que = mysqli_query($db, $sql);

      // Add This In News
      $time = date('Y-m-d h-i');
      ## Get Name Owner And User.
      $messg = "There Is New User ($name).";
      $news_change = "/new=? info=?$messg info=?$time";
      mysqli_query($db, "UPDATE `blog_design` SET `news` = CONCAT(news, '$news_change') WHERE `blog_design`.`id` = 1;");

      header("Location: sign-in.php");
    } else {
      $error = '- First Need Accept My Terms and Conditions.';
    }
  }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Sign Up | Dashboard | BlogTomey
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
</head>

<body class="">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent mt-4">
    <div class="container">
      <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="../pages/dashboard.html">
        BlogTomey Dashboard
      </a>
      <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse" id="navigation">
        <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7 me-0">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="../pages/dashboard.php">
              <i class="fa fa-chart-pie opacity-6  me-1"></i>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="../pages/profile.html">
              <i class="fa fa-user opacity-6  me-1"></i>
              Profile
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="../pages/sign-up.php">
              <i class="fas fa-user-circle opacity-6  me-1"></i>
              Sign Up
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link me-2" href="../pages/sign-in.php">
              <i class="fas fa-key opacity-6  me-1"></i>
              Sign In
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
  <main class="main-content  mt-0">
    <section class="min-vh-100 mb-6">
      <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 text-center mx-auto">
              <h1 class="text-white mb-2 mt-5">Welcome!</h1>
              <p class="text-lead text-white">Create New Account In BlogTomey</p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card z-index-0">
              <div class="card-header text-center pb-0 pt-4">
                <h5>Register with</h5>
              </div>
              <div class="card-body">
                <form role="form text-left" action="" method="POST">
                  <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Name" aria-label="Name" aria-describedby="email-addon" required>
                  </div>
                  <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email" aria-label="Email" aria-describedby="email-addon" required>
                  </div>
                  <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon" required>
                  </div>
                  <div class="form-check form-check-info text-left">
                    <input class="form-check-input" type="checkbox" name="accept" id="flexCheckDefault" checked>
                    <label class="form-check-label" for="flexCheckDefault">
                      I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                    </label>
                  </div>
                  <div class="text-danger" style="font-size: 0.8rem">
                    <?php
                    if (isset($error)) {
                      echo $error;
                    }

                    ?>
                  </div>
                  <div class="text-center">
                    <input type="submit" value="Sign up" name="sign" class="btn bg-gradient-dark w-100 my-4 mb-2">
                  </div>
                  <p class="text-sm mt-3 mb-0">Already have an account? <a href="sign-in.php" class="text-dark font-weight-bolder">Sign in</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 mb-4 mx-auto text-center">
            <a href="https://github.com/Thomas-Emad" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              MyGithub
            </a>
            <a href="https://eg.linkedin.com/in/thomas-emad-71bb681ba/" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              LinkedIn
            </a>
            <a href="../../index.php" target="_blank" class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
              Blog
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              Copyright © <script>
                document.write(new Date().getFullYear())
              </script> Thomas
            </p>
          </div>
        </div>
      </div>
    </footer>
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>