<?php
include_once('../../db.php');
include_once('cont.php');

// Name Page.
$name_page = 'profile';
// Get Name User Profile Used URL.
$url_user = filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT);

// Check If User Sign-up?, Don't Open This Page.
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];
} else {
  header("Location: sign-in.php");
  exit();
}

// Get Info About User.
$sql = "SELECT img_profile, name, email, permission, time_create, info FROM `users` WHERE username = '$url_user';";
$info_user = mysqli_fetch_row(mysqli_query($db, $sql));
$icon = $info_user[0];

// If You Cant Get Information Transform To 404 Page.
if (!isset($info_user)) {
  header('Location: ../../404.php?num_site=0');
}

// IF User Change Information Send It.
if (isset($_POST['send_change'])) {
  $new_name = $_POST['username'];
  $info = $_POST['info'];
  $new_password = $_POST['new_password'];
  if ($_FILES['img_profile']['error'] === 0) {
    foreach (scandir('../../images/user_profile/') as $file) {
      if (!empty(stristr($file, "$icon"))) {
        unlink("../../images/user_profile/" . stristr($file, "$icon"));
        break;
      }
    }
    $icon = $_FILES['img_profile'];
    $icon = "$username" . "." . explode('/', $_FILES['img_profile']['type'])[1];
    move_uploaded_file($_FILES['img_profile']['tmp_name'], "../../images/user_profile/" . $icon);
  }
  if (empty($new_password)) {
    mysqli_query($db, " UPDATE `users` SET name = '$new_name', info = '$info', img_profile = '$icon' WHERE users.username = '$username';");
    header('Refresh: 0;');
  } else {
    mysqli_query($db, " UPDATE `users` SET name = '$new_name', info = '$info', img_profile = '$icon', password = '$new_password' WHERE users.username = '$username';");
    header('Refresh: 0;');
  }
}

// Delete Account
if (isset($_POST['del'])) {
  mysqli_query($db, "DELETE FROM `users` WHERE `users`.`username` = '$username';");
  if (isset($_SESSION['username'])) {
    session_unset();
  } elseif (isset($_COOKIE['username'])) {
    setcookie('username', '', 0, '/');
  }
  header("Location: sign-in.php");
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
    <?php echo $info_user[1] . " | Profile"; ?>
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
  <style>
    * {
      box-sizing: border-box;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <!-- Aside -->
  <?php aside($name_page); ?>
  <!-- End Aside -->

  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php navbar($name_page); ?>
    <!-- End Navbar -->

    <div class="container-fluid">
      <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
      </div>
      <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="../../images/user_profile/<?php echo $info_user[0]; ?>" onerror="this.src='../../images/user_profile/someone.png';this.onerror='';" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $info_user[1]; ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
                <?php echo ucfirst($info_user[3]); ?>
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                <li class="nav-item">
                  <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                    <svg class="text-dark" width="16px" height="16px" viewBox="0 0 42 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-2319.000000, -291.000000)" fill="#FFFFFF" fill-rule="nonzero">
                          <g transform="translate(1716.000000, 291.000000)">
                            <g transform="translate(603.000000, 0.000000)">
                              <path class="color-background" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z">
                              </path>
                              <path class="color-background" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                              <path class="color-background" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                            </g>
                          </g>
                        </g>
                      </g>
                    </svg>
                    <span class="ms-1">Profile</span>
                  </a>
                </li>
                <?php
                if ($username == $url_user) {
                  echo "
                    <li class='nav-item'>
                      <form action='' method='POST' class='nav-link mb-0 px-0 py-1'>
                        <svg class='text-dark' width='16px' height='16px' viewBox='0 0 40 40' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                          <title>Logout</title>
                          <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                            <g transform='translate(-2020.000000, -442.000000)' fill='#FFFFFF' fill-rule='nonzero'>
                              <g transform='translate(1716.000000, 291.000000)'>
                                <g transform='translate(304.000000, 151.000000)'>
                                  <polygon class='color-background' opacity='0.596981957' points='18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667'>
                                  </polygon>
                                  <path class='color-background' d='M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z' opacity='0.596981957'></path>
                                  <path class='color-background' d='M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z'>
                                  </path>
                                </g>
                              </g>
                            </g>
                          </g>
                        </svg>
                        <input type='submit' name='logout' value='Logout' class='ms-1' style='outline:none;border:none;background-color:transparent;'>
                      </form>
                    </li>
                  ";
                }
                ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <div class="row">
                <div class="col-md-8 d-flex align-items-center">
                  <h6 class="mb-0">Profile Information</h6>
                </div>
                <?php
                if ($username == $url_user) {
                  echo "
                    <div class='col-md-4 text-end'>
                      <a href='javascript:;' data-bs-toggle='modal' data-bs-target='#change_info'>
                        <i class='fas fa-user-edit text-secondary text-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Edit Profile'></i>
                      </a>
                    </div>
                  ";
                }
                ?>

              </div>
            </div>
            <div class="card-body p-3">
              <p class="text-sm"><?php
                                  if (empty($info_user[5])) {
                                    echo 'No Thing!!';
                                  } else {
                                    echo $info_user[5];
                                  }
                                  ?></p>
              <hr class="horizontal gray-light my-4">
              <ul class="list-group">
                <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong>
                  &nbsp;<?php echo $info_user[1]; ?></li>
                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp;
                  <?php echo $info_user[2]; ?></li>
                <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Time Create:</strong> &nbsp;
                  <?php echo $info_user[4]; ?></li>

              </ul>
            </div>
          </div>
        </div>
        <?php
        // If You Don't Have Permission To Open Dashboard.
        $sql = "SELECT permission FROM `users` WHERE username = '$username'";
        $permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
        if ($permission_user != 'user') {
          echo "
          <div class='col-12 mt-4'>
            <div class='card mb-4'>
              <div class='card-header pb-0 p-3' style='display: flex; align-items: flex-start; justify-content: space-between;'>
                <div>
                  <h6 class='mb-1'>Posts</h6>
                  <p class='text-sm'>Your Posts.</p>
                </div>
                <a href='";
          echo 'home_post.php?site=main_page';
          echo "' target='_blank' class='btn btn-outline-info btn-sm mb-0'>Edit</a>
              </div>
              <div class='card-body p-3'>
                <div class='row'>
          ";
          $sql = "SELECT name_random, name, des, visits, time_add, type, bg_img_post FROM `posts` WHERE username = '$url_user';";
          $posts_user = mysqli_fetch_all(mysqli_query($db, $sql));
          foreach ($posts_user as $post) {
            echo "
            <div class='col-xl-3 col-md-4 mb-xl-0 mb-4'>
              <div class='card card-blog card-plain'>
                <div class='position-relative'>
                  <a class='d-block shadow-xl border-radius-xl'>
                    <img src='../../images/posts/img_post/$post[6]' alt='img-blur-shadow'
                      class='shadow border-radius-xl' style='max-width: 100%;height: 200px;width: 100%;'>
                  </a>
                </div>
                <div class='card-body px-1 pb-0'>
                  <p class='text-gradient text-dark text-sm'>$post[5]</p>
                  <a href='javascript:;'>
                    <h5>
                      $post[1]
                    </h5>
                  </a>
                  <p class='mb-4 text-sm' style='font-size:0.9rem;height:50px;overflow:hidden;'>
                    $post[2]
                  </p>
                  <div class='d-flex align-items-center justify-content-between'>
                    <a href='http://localhost/PHP/Projects/Blog/post.php?name=$post[0]' target='_blank' class='btn btn-outline-primary btn-sm mb-0'>View Post</a>
                    <div class='avatar-group' style='font-size: 0.9rem;'>
                      $post[3] Watch
                    </div>
                  </div>
                </div>
              </div>
              </div>";
          }
          echo "
                  <div class='col-xl-3 col-md-4 mb-xl-0 mb-4'>
                    <div class='card h-100 card-plain border'>
                      <div class='card-body d-flex flex-column justify-content-center text-center'>
                        <a href='";
          echo 'home_post.php?site=add_post';
          echo "'>
                          <i class='fa fa-plus text-secondary mb-3'></i>
                          <h5 class=' text-secondary'> New project </h5>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          ";
        }
        ?>
      </div>
      <!-- Footer -->
      <?php footer(); ?>
      <!-- End Footer -->
    </div>
  </div>
  <?php opitons_dashboard(); ?>
  <?php

  if ($username == $url_user) {
    echo "<!-- Modal ==> Change Info Channle -->
      <div class='modal fade text-dark' id='change_info' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='staticBackdropLabel' aria-hidden='true'>
        <div class='modal-dialog modal-fullscreen'>
          <div class='modal-content '>
            <div class='modal-header'>
              <h1 class='modal-title fs-5' id='staticBackdropLabel'>Change Your Information</h1>
              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
              <form action='' method='POST' enctype='multipart/form-data'>
                <div class='input-group mb-2'>
                  <input type='file' class='form-control' id='img_profile' name='img_profile'>
                  <label class='input-group-text' for='img_profile'>Icon Channle</label>
                </div>
                <div class='form-floating mb-2'>
                  <input type='text' class='form-control' id='username' name='username' placeholder='Your Name' value='$info_user[1]'>
                  <label for='username'>Your Name</label>
                </div>
                <div class='form-floating mb-2'>
                  <input type='email' class='form-control' id='email_input' placeholder='Email address' value='$info_user[2]' disabled>
                  <label for='email_input'>Email address</label>
                </div>
                <div class='form-floating mb-2'>
                  <textarea class='form-control' id='info' name='info' placeholder='Info' style='height: 100px;'>$info_user[5]</textarea>
                  <label for='info'>Info</label>
                </div>
                <div class='form-floating mb-2'>
                  <input type='password' class='form-control' id='password_input' name='new_password' placeholder='Password'>
                  <label for='password_input'>Password</label>
                </div>
                <div class='form-floating mb-2'>
                  <input type='text' class='form-control' id='time_create_acc' placeholder='Time Create' value='$info_user[4]' disabled>
                  <label for='time_create_acc'>Time Create</label>
                </div>
                <div class='btn btn-outline-danger' data-bs-toggle='modal' data-bs-target='#delet_account'>Delete Account</div>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Don't Save</button>
              <input type='submit' name='send_change' value='Save Change' class='btn btn-success'>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Modal ==> Delete Account -->
      <div class='modal fade text-dark' id='delet_account' tabindex='-1' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h1 class='modal-title fs-5'>Delete Your Account?</h1>
              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
              Are You Want To Delete Your Account?!!
            </div>
            <div class='modal-footer'>
              <form action='' method='POST'>
                <button type='button' class='btn btn-success' data-bs-dismiss='modal'>Close</button>
                <input type='submit' name='del' class='btn btn-outline-danger' value='Delete'>
              </form>
            </div>
          </div>
        </div>
      </div>
    ";
  }
  ?>

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