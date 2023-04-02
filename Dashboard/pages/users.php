<?php
include_once('../../db.php');
include_once('cont.php');

// Name Page.
$name_page = "Users";

// If You Don't Have Permission To Open Dashboard.
$sql = "SELECT permission FROM `users` WHERE username = '$username'";
$permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
if ($permission_user != 'owner') {
  echo "
  <div style='width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-family: cursive; gap: 10px; flex-direction: column;'>
    Can\'t Open This Page, Because You Don't Have Enough Permission.
    <form action='' method='POST' style='margin: 0;'>
      <input type='submit'  value='Logout' name='logout' style='outline: none; border: none;padding: 5px 15px; font-size: 1.2rem; background-color: #ddd; border-radius: 5px;cursor: pointer; '>
    </form>
    <a href='profile.php?user=$username' style='outline: none; border: none;padding: 5px 15px; font-size: 1.2rem; background-color: #ddd; border-radius: 5px;cursor: pointer; text-decoration: none;color: inherit;'>My Profile</a>
  </div>
  ";
  exit();
}

// Change Permission Any User.
if (isset($_POST['change_status'])) {
  $username_change = $_POST['change_status'];
  $new_status = $_POST['ch_status'];

  // Add This In News
  $time = date('Y-m-d h-i');
  ## Get Name Owner And User.
  $que = mysqli_query($db, "SELECT `name` FROM `users` WHERE username = '$username' OR username = '$username_change';");
  $names = mysqli_fetch_all($que);
  $owner_name = $names[0][0];
  $user_name_new = $names[1][0];
  $messg = "$owner_name Changed The Powers of $user_name_new To $new_status";
  $news_change = "/new=? info=?$messg info=?$time";
  mysqli_query($db, "UPDATE `blog_design` SET `news` = CONCAT(news, '$news_change') WHERE `blog_design`.`id` = 1;");

  mysqli_query($db, "UPDATE `users` SET `permission` = '$new_status' WHERE `users`.`username` = '$username_change';");
  header('Refresh: 0;');
}

// Delete User.
if (isset($_POST['del_user'])) {
  $user_del = $_POST['del_user'];

  // Add This In News
  $time = date('Y-m-d h-i');
  ## Get Name Owner And User.
  $que = mysqli_query($db, "SELECT `name` FROM `users` WHERE username = '$username' OR username = '$user_del';");
  $names = mysqli_fetch_all($que);
  $owner_name = $names[0][0];
  $user_name_new = $names[1][0];
  $messg = "$owner_name Deleted $user_name_new Account";
  $news_change = "/new=? info=?$messg info=?$time";
  mysqli_query($db, "UPDATE `blog_design` SET `news` = CONCAT(news, '$news_change') WHERE `blog_design`.`id` = 1;");

  mysqli_query($db, "DELETE FROM users WHERE `users`.`username` = '$user_del'");
  header('Refresh: 0;');
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
    BlogTomey | Users
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
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Users</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">permission</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Posts</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Get All Users.
                    $sql = "SELECT users.username, users.name, users.email, users.permission, users.time_create, COUNT(posts.name), users.img_profile FROM `users` LEFT JOIN `posts` USING(username) GROUP BY users.username ORDER BY `COUNT(posts.name)` DESC;";
                    $users = mysqli_fetch_all(mysqli_query($db, $sql));
                    foreach ($users as $user) {
                      echo "
                      <tr>
                        <form action='' method='POST'>
                          <td>
                            <div class='d-flex px-2 py-1'>
                              <div>
                                <img src='../../images/user_profile/$user[6]' class='avatar avatar-sm me-3' alt='user1'>
                              </div>
                              <div class='d-flex flex-column justify-content-center'>
                                <h6 class='mb-0 text-sm'>$user[1]</h6>
                                <p class='text-xs text-secondary mb-0'>$user[2]</p>
                              </div>
                            </div>
                          </td>
                          <td>
                            <select name='ch_status'>
                              <option value='owner'>owner</option>
                              <option value='$user[3]' selected>$user[3]</option>
                              <option value='admin'>admin</option>
                              <option value='editor'>editor</option>
                              <option value='user'>user</option>
                            </select>
                          </td>
                          <td>
                            <p class='text-xs font-weight-bold mb-0'>$user[5]</p>
                          </td>
                          <td class='align-middle text-center'>
                            <span class='text-secondary text-xs font-weight-bold'>$user[4]</span>
                          </td>
                          <td class='align-middle'>
                            <input type='submit' name='change_status' id='ch_status$user[0]' value='$user[0]' style='display: none;'>
                            <label for='ch_status$user[0]' class='text-secondary font-weight-bold text-xs' style='cursor: pointer;' data-toggle='tooltip' data-original-title='Change Status user'>Change</label>

                            <input type='submit' name='del_user' id='del_account$user[0]' value='$user[0]' style='display: none;'>
                            <label for='del_account$user[0]' class='text-secondary font-weight-bold text-xs' style='cursor: pointer;' data-toggle='tooltip' data-original-title='Edit user'>Delete</label>
                          </td>
                        </form>
                      </tr>
                      ";
                    }

                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer pt-3  ">
        <?php footer($name_page); ?>
      </footer>
    </div>
  </main>
  <?php opitons_dashboard(); ?>

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