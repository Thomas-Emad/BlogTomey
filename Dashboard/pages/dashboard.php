<?php
include_once('../../db.php');
include_once('cont.php');

// Name Page.
$name_page = 'Home';

// Check If User Sign-up?, Don't Open This Page.
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];
} else {
  header("Location: sign-in.php");
  exit();
}

// If You Don't Have Permission To Open Dashboard.
$sql = "SELECT permission FROM `users` WHERE username = '$username'";
$permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
if ($permission_user == 'user') {
  echo "
  <div style='width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-family: cursive; gap: 10px; flex-direction: column;'>
    Can't Open Dashboard, Because You Don't Have Enough Permission.
    <form action='' method='POST' style='margin: 0;'>
      <input type='submit'  value='Logout' name='logout' style='outline: none; border: none;padding: 5px 15px; font-size: 1.2rem; background-color: #ddd; border-radius: 5px;cursor: pointer; '>
    </form>
    <a href='profile.php?user=$username' style='outline: none; border: none;padding: 5px 15px; font-size: 1.2rem; background-color: #ddd; border-radius: 5px;cursor: pointer; text-decoration: none;color: inherit;'>My Profile</a>
  </div>
  ";
  exit();
}

// Get Info For COUNT Posts, Visits.
$sql = "SELECT past_visits, COUNT(p.name) FROM `posts` p, blog_design;";
$que = mysqli_query($db, $sql);
$posts_info = mysqli_fetch_row($que);

// Get Info For COUNT Admins.
$sql = "SELECT permission FROM `users`;";
$users_status = mysqli_fetch_all(mysqli_query($db, $sql));
$count_status_admins = 0;
$all_users = 0;
foreach ($users_status as $user) {
  if ($user[0] == 'owner' || $user[0] == 'admin') {
    $count_status_admins++;
  } else {
    $all_users++;
  }
}
$all_users += $count_status_admins;

// Show All Comments
$sql = "SELECT comment FROM `posts`";
$comments = mysqli_fetch_all(mysqli_query($db, $sql));

// Get All News.
$sql = "SELECT news FROM `blog_design`;";
$answer = mysqli_fetch_row(mysqli_query($db, $sql))[0];
$str_to_array = array_reverse(explode('/new=?', $answer));


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Home Page
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
    ::-webkit-scrollbar {
      width: 10px;
    }

    ::-webkit-scrollbar-track {
      background-color: #eee;
      opacity: 0.9;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #999;
      border-radius: 10px;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <!-- Aside -->
  <?php aside($name_page); ?>
  <!-- End Aside -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php navbar($name_page); ?>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">All Vists</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $posts_info[0]; ?>
                    </h5>
                  </div>
                </div>

                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Posts</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $posts_info[1]; ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-book-bookmark text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Admins</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $count_status_admins; ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">All Memmbers</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo $all_users; ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row my-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Comments</h6>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive" style="overflow-y: auto; height: 400px;">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">article</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comment</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Go Post</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Tansform All Posts To Array.
                    foreach ($comments as $post_all_comment) {
                      $big = explode("/nucm=?", $post_all_comment[0]);
                      // One Comment
                      foreach ($big as $one) {
                        $one = explode("/nucm=?", $one);
                        if ($one[0] != '') {
                          // info About Comment.
                          foreach ($one as $com) {
                            $com = explode("info=?", $com);

                            if ($com[0] != '/admin@dont_allow_comm') {
                              // Get Info About Post.
                              $sql = "SELECT name, `bg_img_post` FROM `posts` WHERE name_random = $com[4]";
                              $post_com = mysqli_fetch_row(mysqli_query($db, $sql));

                              // Get Info About User Was Add Comment.
                              $sql = "SELECT name, img_profile FROM `users` WHERE username = '$com[1]'";
                              $user_comm = mysqli_fetch_row(mysqli_query($db, $sql));

                              // If User Was Delete Account Or Don't Have Account.
                              if (isset($user_comm)) {
                                $user_comm_name = $user_comm[0];
                                $user_comm_img = $user_comm[1];
                              } else {
                                $user_comm_name = 'Unknown';
                                $user_comm_img = 'someone.png';
                              }
                              echo "
                              <tr>
                                <td>
                                  <div class='d-flex px-2 py-1'>
                                    <div>
                                      <img src='../../images/posts/img_post/$post_com[1]' class='avatar avatar-sm me-3' alt='xd'>
                                    </div>
                                    <div class='d-flex flex-column justify-content-center'>
                                      <h6 class='mb-0 text-sm'>$post_com[0]</h6>
                                    </div>
                                  </div>
                                </td>
                                <td class='align-middle text-center text-sm'>
                                  <span class='text-xs font-weight-bold' style='white-space: pre-wrap; min-height: 20px;max-height: 100px;overflow-y: auto;display: inline-block;'>$com[2]</span>
                                </td>
                                <td>
                                  <div class='avatar-group mt-2'>
                                    <a href='javascript:;' class='avatar avatar-xs rounded-circle' data-bs-toggle='tooltip' data-bs-placement='bottom' title='$user_comm_name'>
                                      <img src='../../images/user_profile/$user_comm_img' class='w-100 h-100' alt='$user_comm_name'>
                                    </a>
                                  </div>
                                </td>
                                <td class='align-middle text-center text-sm'>
                                  <span class='text-xs font-weight-bold'>$com[3]</span>
                                  </td>
                                  <td class='align-middle'>
                                    <a href='../../post.php?name=$com[4]' class='text-xs font-weight-bold'>Go To Post</a>
                                  </td>
                                </td>
                              </tr>
                            ";
                            }
                          }
                        }
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6>Now News</h6>
              <p class="text-sm">
                <i class="fa fa-arrow-up text-success" aria-hidden="true"></i> Last News
              </p>
            </div>
            <div class="card-body p-3" style="overflow-y: auto; height: 400px;">
              <div class="timeline timeline-one-side">
                <?php
                foreach ($str_to_array as $new) {
                  $new_onec = explode('info=?', $new);
                  if (sizeof($new_onec) !== 1) {
                    echo "
                    <div class='timeline-block mb-3'>
                      <span class='timeline-step'>
                        <i class='ni ni-bell-55 text-success text-gradient'></i>
                      </span>
                      <div class='timeline-content'>
                        <h6 class='text-dark text-sm font-weight-bold mb-0'>$new_onec[1]</h6>
                        <p class='text-secondary font-weight-bold text-xs mt-1 mb-0'>$new_onec[2]</p>
                      </div>
                    </div>
                ";
                  }
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer -->
    <?php footer(); ?>
    <!-- End Footer -->
    </div>
  </main>
  <?php opitons_dashboard(); ?>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>