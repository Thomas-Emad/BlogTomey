<?php
include_once('../../db.php');
include_once('cont.php');

// Name Page.
$name_page = 'Edit Design';

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

// Get Every Thing About Design Blog.
$sql = "SELECT name_site, icon_site, urls_nav, types_post, colors, best_post, media_links FROM `blog_design`;";
$answer = mysqli_fetch_row(mysqli_query($db, $sql));
// Values 
$name_site = $answer[0];
$icon_site = $answer[1];
$urls_nav = $answer[2];
$types_post = $answer[3];
$colors = $answer[4];
$media_links = explode(',', $answer[6]);

// Save Your Changle
if (isset($_POST['save_change'])) {
  $name_site = filter_var($_POST['name_site'], FILTER_SANITIZE_EMAIL);
  $urls_nav = filter_var($_POST['urls_nav'], FILTER_SANITIZE_STRING);
  $types_post = filter_var($_POST['types_post'], FILTER_SANITIZE_STRING);
  $colors = $_POST['color'];
  $media_links = filter_var($_POST['link_one'] . ',' . $_POST['link_two'], FILTER_SANITIZE_URL);
  $best_post = $_POST['best_post'];
  $allow_types_icon = ['icon', 'x-icon', 'ico', 'png', 'web'];
  $errors = [];

  // If Owner Upload Small Icon, Or Background Img Site.
  if ($_FILES['icon_site']['error'] == 0) {
    $type_img = explode('/', $_FILES['icon_site']['type'])[1];
    if (in_array($type_img, $allow_types_icon)) {
      unlink("../../images/design_site/" . $icon_site);
      move_uploaded_file($_FILES['icon_site']['tmp_name'], "../../images/design_site/small_icon." . $type_img);
      $icon_site = "small_icon." . $type_img;
    } else {
      $errors[] = 'type_icon';
    }
  }


  // If You Don't Have Any Error Upload, Else Print Errors.
  if (empty($errors)) {
    mysqli_query($db, "UPDATE `blog_design` SET
      `name_site` = '$name_site',
      `icon_site` = '$icon_site',
      `urls_nav` = '$urls_nav',
      `types_post` = '$types_post',
      `colors` = '$colors',
      `best_post` = '$best_post',
      `media_links` = '$media_links' WHERE `blog_design`.`id` = 1;");

    // Add This In News
    $time = date('Y-m-d h-i');
    ## Get Name Owner And User.
    $que = mysqli_query($db, "SELECT `name` FROM `users` WHERE username = '$username'");
    $names = mysqli_fetch_all($que);
    $owner_name = $names[0][0];
    $messg = "$owner_name Change In Design Site";
    $news_change = "/new=? info=?$messg info=?$time";
    mysqli_query($db, "UPDATE `blog_design` SET `news` = CONCAT(news, '$news_change') WHERE `blog_design`.`id` = 1;");

    header("Refresh: 0;");
  } else {
    echo "<div style='font-size: 0.9rem; position: absolute; z-index: 100000; right: 10px; bottom: 15px'>";
    foreach ($errors as $err) {
      if ($err == 'type_icon') {
        echo "
        <div class='alert bg-danger text-light d-flex align-items-center' role='alert'>
          <svg class='bi flex-shrink-0 me-2' role='img' aria-label='Danger:' style='height: 30px; width: 30px;'>
            <use xlink:href='#exclamation-triangle-fill' />
          </svg>
          <div style='display: flex; justify-content: space-between; width: 100%;'>
            <span><strong>Can't Upload Change</strong> Only Upload [icon, x-icon, ico, png, web].</span>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>
        </div>
        ";
      }
    }
    echo "</div>";
  }
}



?>
<!DOCTYPE html>
<html lang="en">

<?php header_page($name_page, 'edit_design.css'); ?>


<body class="g-sidenav-show  bg-gray-100">
  <!-- If We Have Errors-->
  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
  </svg>

  <!-- Aside -->
  <?php aside($name_page); ?>
  <!-- End Aside -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php navbar($name_page); ?>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="parent">
          <div class="box">
            <div class="title">
              <i class="ni ni-book-bookmark text-lg opacity-10" aria-hidden="true"></i>
              Title
            </div>
            <a href="#title_modal" class="btn btn-secondary m-0" data-bs-toggle="modal">Edit</a>
            <!-- Modal -->
            <div class="modal fade" id="title_modal" tabindex="-1" aria-labelledby="title_modal" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="title_modal">Title Site</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="input-group mb-3">
                      <span class="input-group-text bg-light">Title</span>
                      <input type="text" name="name_site" class="form-control ps-2" value="<?php echo $name_site; ?>">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="title">
              <i class="ni ni-books text-lg opacity-10" aria-hidden="true"></i>
              Types Posts
            </div>
            <a href="#types_posts" class="btn btn-secondary m-0" data-bs-toggle="modal">Edit</a>
            <!-- Modal -->
            <div class="modal fade" id="types_posts" tabindex="-1" aria-labelledby="types_posts" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="types_posts">Types Posts</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <label class="form-label">- Separate Each Sentence With ,</label>
                    <div class="form-floating mb-3">
                      <input type="text" name="types_post" class="form-control" id="types" value="<?php echo $types_post; ?>" placeholder="text">
                      <label for="types">Types Posts</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="title">
              <i class="ni ni-palette text-lg opacity-10" aria-hidden="true"></i>
              Mode Colors
            </div>
            <a href="#colors" class="btn btn-secondary m-0" data-bs-toggle="modal">Choose</a>
            <!-- Modal -->
            <div class="modal fade" id="colors" tabindex="-1" aria-labelledby="colors" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="colors">Mode Colors</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="form-floating mb-3">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="color" value="dark-color" id="dark-color" <?php if ($colors == 'dark-color') echo 'checked'; ?>>
                        <label class="form-check-label" for="dark-color" style="display: flex; align-items: center; justify-content: space-between;">
                          Dark Mode
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="color" value="light-color" id="light-color" <?php if ($colors == 'light-color') echo 'checked'; ?>>
                        <label class="form-check-label" for="light-color" style="display: flex; align-items: center; justify-content: space-between;">
                          Light Mode
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="title">
              <i class="ni ni-align-left-2 text-lg opacity-10" aria-hidden="true"></i>
              Urls In Nav
            </div>
            <a href="#urls_nav" class="btn btn-secondary m-0" data-bs-toggle="modal">Edit</a>
            <!-- Modal -->
            <div class="modal fade" id="urls_nav" tabindex="-1" aria-labelledby="urls_nav" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="urls_nav">Urls Nav</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <label class="form-label">- Separate Each Sentence With ,</label>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="urls_nav" id="types" value="<?php echo $urls_nav; ?>" placeholder="text">
                      <label for="types">Urls Nav</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="title">
              <i class="ni ni-ungroup text-lg opacity-10" aria-hidden="true"></i>
              Small Icon Site
            </div>
            <a href="#small_icon" class="btn btn-secondary m-0" data-bs-toggle="modal">Choose</a>
            <!-- Modal -->
            <div class="modal fade" id="small_icon" tabindex="-1" aria-labelledby="small_icon" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="small_icon">Icon Site</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="formFile" class="form-label">- Choose Small Icon For Icon Site</label>
                      <input class="form-control" type="file" name="icon_site" id="formFile">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="title">
              <i class="ni ni-laptop text-lg opacity-10" aria-hidden="true"></i>
              Media Links
            </div>
            <a href="#media_links" class="btn btn-secondary m-0" data-bs-toggle="modal">Edit</a>
            <!-- Modal -->
            <div class="modal fade" id="media_links" tabindex="-1" aria-labelledby="media_links" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="media_links">Media Links</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <label class="form-label">- Separate Each Sentence With ,</label>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="link_one" required id="types" value="<?php echo $media_links[0]; ?>" placeholder="text">
                      <label for="types">Facebook</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" name="link_two" required id="types" value="<?php echo $media_links[1]; ?>" placeholder="text">
                      <label for="types">YouTube</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box">
            <div class="title">
              <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
              Featured Sharing
            </div>
            <a href="#best_post" class="btn btn-secondary m-0" data-bs-toggle="modal">Choose</a>
            <!-- Modal -->
            <div class="modal fade" id="best_post" tabindex="-1" aria-labelledby="best_post" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="best_post">Choose Featured Sharing</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <select class="form-select" name="best_post" aria-label="Default select example">
                        <?php
                        $sql = "SELECT name_random, name FROM `posts`;";
                        $posts_choose = mysqli_fetch_all(mysqli_query($db, $sql));
                        foreach ($posts_choose as $post) {
                          echo "<option value='$post[0]'";
                          if ($post[0] === $answer[6]) {
                            echo "selected";
                          }
                          echo ">$post[1]</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box submit">
          <a href="#" class="btn btn-secondary m-0">Close</a>
          <input type="submit" name="save_change" value="Change" class="btn btn-success m-0">
        </div>

      </form>
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