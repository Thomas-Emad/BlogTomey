<?php
include_once('../../db.php');
include_once('cont.php');

// Check If User Sign-up?, Don't Open This Page.
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];
} else {
  header("Location: sign-in.php");
  exit();
}
// If You Don't Have Permission To Open Home Post.
$sql = "SELECT permission FROM `users` WHERE username = '$username'";
$permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
if ($permission_user == 'user') {
  echo "
  <div style='width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-family: cursive; gap: 10px; flex-direction: column;'>
    Can\'t Open Dashboard, Because You Don\'t Have Enough Permission.
    <form action='' method='POST' style='margin: 0;'>
      <input type='submit'  value='Logout' name='logout' style='outline: none; border: none;padding: 5px 15px; font-size: 1.2rem; background-color: #ddd; border-radius: 5px;cursor: pointer; '>
    </form>
    <a href='profile.php?user=$username' style='outline: none; border: none;padding: 5px 15px; font-size: 1.2rem; background-color: #ddd; border-radius: 5px;cursor: pointer; text-decoration: none;color: inherit;'>My Profile</a>
  </div>
  ";
  exit();
}

// Name Page.
$name_page = "posts";

// Get Name Site Used URL.
$url_page = $_GET['site'];

// Print All Site Used Url => [Mange Post, Add Post, Edit Post]
if ($url_page == 'main_page') {
  $title_page = 'Posts';
  // IF Admin Del Post.
  if (isset($_POST['del_post'])) {
    $del_post = $_POST['del_post'];
    $sql = "SELECT `img_box_one`, `img_box_two`, `bg_img_post` FROM `posts` WHERE `posts`.`name_random` = '$del_post'";
    $imgs_post_del = mysqli_fetch_row(mysqli_query($db, $sql));

    // Delete Files 
    foreach ($imgs_post_del as $img_str) {
      $img_array = explode(',', $img_str);
      foreach ($img_array as $img) {
        foreach (scandir('../../images/posts/img_post/') as $file) {
          if ($img == $file) {
            unlink("../../images/posts/img_post/" . $file);
            break;
          }
        }
      }
    }
    mysqli_query($db, "DELETE FROM `posts` WHERE `posts`.`name_random` = '$del_post'");
    header('Refresh:0;');
  }
} elseif ($url_page == 'add_post') {
  $title_page = 'Add New Post';

  // Get Types Post.
  $sql = "SELECT types_post FROM `blog_design`";
  $types_post_string = mysqli_fetch_row(mysqli_query($db, $sql))[0];
  $types_post = explode(',', $types_post_string);

  // Upload Post 
  if (isset($_POST['up_post'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $name_random = rand(100, 1000000);
    $text_one = str_replace(['<', '>'], '', $_POST['text_one']);
    $text_two = str_replace(['<', '>'], '', $_POST['text_two']);
    $des = filter_var($_POST['des'], FILTER_SANITIZE_STRING);
    $type = $_POST['type'];
    $img_bg = $_FILES['bg_img_file'];
    $imgs_one = $_FILES['bg_img_file_one'];
    $imgs_two = $_FILES['bg_img_file_two'];
    $errors = [];

    // If User Don't Add Content. (Required)
    if (strlen($text_one) === 0) {
      $errors[] = 'text-empty';
    }

    // Make Max Size In Title => Char 50. (Required)
    if (strlen($title) === 0) {
      $errors[] = 'title-empty';
    } elseif (strlen($title) > 150) {
      $errors[] = 'title-match';
    }

    // Make Max Size In Description => Char 200. (Required)
    if (strlen($des) === 0) {
      $errors[] = 'des-not';
    } elseif (strlen($des) > 200) {
      $errors[] = 'des-match';
    }

    // If You Upload Background, Move It And Change Has Name.
    if ($img_bg['error'] != 4) {
      $type_img = explode('/', $img_bg['type'])[1];
      $new_name_bg_img = rand(1, 10000) . '.' . $type_img;
      move_uploaded_file($img_bg['tmp_name'], '../../images/posts/img_post/' . $new_name_bg_img);
    } else {
      $errors[] = 'bg-img';
    }

    // Upload Images Box One => Change Has Name Then Move It, And Save Has Name in Array.
    if ($imgs_one['error'][0] != 4) {
      $name_imgs_one_move_array = [];
      for ($i = 0; $i < sizeof($imgs_one['name']); $i++) {
        $type_img = explode('/', $imgs_one['type'][$i])[1];
        $new_name_img = rand(1, 10000) . '.' . $type_img;
        move_uploaded_file($imgs_one['tmp_name'][$i], '../../images/posts/img_post/' . $new_name_img);
        array_push($name_imgs_one_move_array, $new_name_img);
      }
      $name_imgs_one_move_string = implode(',', $name_imgs_one_move_array);
    } else {
      $name_imgs_one_move_string = '';
    }

    // Upload Images Box Two => Change Has Name Then Move It, And Save Has Name in Array.
    if ($imgs_two['error'][0] != 4) {
      $name_imgs_two_move_array = [];
      for ($i = 0; $i < sizeof($imgs_two['name']); $i++) {
        $type_img = explode('/', $imgs_two['type'][$i])[1];
        $new_name_img = rand(1, 10000) . '.' . $type_img;
        move_uploaded_file($imgs_two['tmp_name'][$i], '../../images/posts/img_post/' . $new_name_img);
        array_push($name_imgs_two_move_array, $new_name_img);
      }
      $name_imgs_two_move_string = implode(',', $name_imgs_two_move_array);
    } else {
      $name_imgs_two_move_string = '';
    }

    // Allow To Add Comments.
    if (isset($_POST['allow_comm'])) {
      $allow_comm = '';
    } else {
      $allow_comm = '/admin@dont_allow_comm';
    }

    // IF You Don't Have Any Errors Upload Data.
    if (empty($errors)) {
      $sql = "INSERT INTO `posts` (`id`, `name_random`, `name`, `content_one`, `content_two`, `img_box_one`, `img_box_two`, `des`, `visits`, `comment`, `time_add`, `username`, `type`, `bg_img_post`) 
    VALUES (NULL, '$name_random', '$title', '$text_one', '$text_two', '$name_imgs_one_move_string', '$name_imgs_two_move_string', '$des', '0', '$allow_comm', current_timestamp(), '$username', '$type', '$new_name_bg_img');";

      // Add This In News
      $time = date('Y-m-d h-i');
      ## Get Name Owner And User.
      $que = mysqli_query($db, "SELECT `name` FROM `users` WHERE username = '$username'");
      $names = mysqli_fetch_all($que);
      $owner_name = $names[0][0];
      $messg = "$owner_name Added New Post";
      $news_change = "/new=? info=?$messg info=?$time";
      mysqli_query($db, "UPDATE `blog_design` SET `news` = CONCAT(news, '$news_change') WHERE `blog_design`.`id` = 1;");

      mysqli_query($db, $sql);
      header('Location: home_post.php?site=main_page');
    }
  }
} elseif ($url_page == 'edit_post') {
  $title_page = 'Edit Post';

  // Get name Post Edit
  $name_post_edit = $_GET['post_edit'];

  // Check From Your Permissions.
  $sql = "SELECT permission FROM `users` WHERE username = '$username'";
  $permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
  $allow_edit = false;

  // If User Editor Check IF Its From His Posts ?
  if ($permission_user == 'editor') {
    $sql = "SELECT name_random FROM `tomblog`.`posts` WHERE `username` = '$username' AND name_random = '$name_post_edit';";
    $isset = mysqli_fetch_row(mysqli_query($db, $sql));
    if (isset($isset)) {
      $sql = "SELECT * FROM `posts` WHERE posts.name_random = '$name_post_edit';";
      $que = mysqli_query($db, $sql);
      $post_edit = mysqli_fetch_row($que);
      $allow_edit = true;
    }
  } else {

    // Get All Information About Post, For Edit.
    $sql = "SELECT * FROM `posts` WHERE posts.name_random = '$name_post_edit';";
    $que = mysqli_query($db, $sql);
    $post_edit = mysqli_fetch_row($que);
    $allow_edit = true;
    if (!isset($post_edit)) {
      header('Location: ../../404.php?num_site=0');
    }
  }

  // Upload Edit Post 
  if (isset($_POST['up_post'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $text_one = str_replace(['<', '>'], '', $_POST['text_one']);
    $text_two = str_replace(['<', '>'], '', $_POST['text_two']);
    $des = filter_var($_POST['des'], FILTER_SANITIZE_STRING);
    $type = $_POST['type'];
    $img_bg = $post_edit[13];
    $imgs_one = $post_edit[5];
    $imgs_two = $post_edit[6];
    $errors = [];

    // If User Don't Add Content. (Required)
    if (strlen($text_one) === 0) {
      $errors[] = 'text-empty';
    }

    // Make Max Size In Title => Char 50. (Required)
    if (strlen($title) === 0) {
      $errors[] = 'title-empty';
    } elseif (strlen($title) > 150) {
      $errors[] = 'title-match';
    }

    // Make Max Size In Description => Char 200. (Required)
    if (strlen($des) === 0) {
      $errors[] = 'des-not';
    } elseif (strlen($des) > 200) {
      $errors[] = 'des-match';
    }

    // If User Add New Background, Or Not.
    if ($_FILES['bg_img_file']['error'] != 4) {
      $type_img = explode('/', $_FILES['bg_img_file']['type'])[1];
      $img_bg_tmp = $_FILES['bg_img_file']['tmp_name'];
      unlink('../../images/posts/img_post/' . $img_bg);
      $img_bg = rand(1, 10000) . '.' . $type_img;
      move_uploaded_file($img_bg_tmp, '../../images/posts/img_post/' . $img_bg);
    }

    // Upload Images Box One => Change Has Name Then Move It, And Save Has Name in Array.
    if ($_FILES['bg_img_file_one']['error'][0] != 4) {
      $name_imgs_one_move_array = [];
      for ($i = 0; $i < sizeof($imgs_one['name']); $i++) {
        $type_img = explode('/', $imgs_one['type'][$i])[1];
        $new_name_img = rand(1, 10000) . '.' . $type_img;
        move_uploaded_file($imgs_one['tmp_name'][$i], '../../images/posts/img_post/' . $new_name_img);
        array_push($name_imgs_one_move_array, $new_name_img);
      }
      $imgs_one = implode(',', $name_imgs_one_move_array);
    }
    // Upload Images Box Two => Change Has Name Then Move It, And Save Has Name in Array.
    if ($_FILES['bg_img_file_two']['error'][0] != 4) {
      $name_imgs_two_move_array = [];
      for ($i = 0; $i < sizeof($imgs_two['name']); $i++) {
        $type_img = explode('/', $imgs_two['type'][$i])[1];
        $new_name_img = rand(1, 10000) . '.' . $type_img;
        move_uploaded_file($imgs_two['tmp_name'][$i], '../../images/posts/img_post/' . $new_name_img);
        array_push($name_imgs_two_move_array, $new_name_img);
      }
      $imgs_two = implode(',', $name_imgs_two_move_array);
    }

    // Allow To Add Comments.
    if (isset($_POST['allow_comm'])) {
      $allow_comm = '';
    } else {
      $allow_comm = '/admin@dont_allow_comm';
    }


    // IF You Don't Have Any Errors Upload Data.
    if (empty($errors)) {
      $sql = "UPDATE `posts` SET
      `name` = '$title', `content_one` = '$text_one',
      `content_two` = '$text_two', `img_box_one` = '$imgs_one',
      `img_box_two` = '$imgs_two', `des` = '$des',
      `type` = '$type', `bg_img_post` = '$img_bg',
      `comment` = '$allow_comm'
        WHERE `posts`.`name_random` = '$name_post_edit'; ";
      mysqli_query($db, $sql);
      header('Location: home_post.php?site=main_page');
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php header_page($title_page, 'add_post.css'); ?>

<body class="g-sidenav-show  bg-gray-100">
  <!-- Aside -->
  <?php aside($name_page); ?>
  <!-- End Aside -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" style="padding: 0 30px;">
    <!-- Navbar -->
    <?php navbar($name_page); ?>
    <!-- End Navbar -->
    <div class="position-relative">
      <?php
      if ($url_page == 'main_page') {
        // Check From Your Permission.
        $sql = "SELECT permission FROM `users` WHERE username = '$username'";
        $permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
        if ($permission_user == 'editor') {
          $que = mysqli_query($db, "SELECT name_random, posts.name, type, visits, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users USING(username) WHERE users.username = '$username' ORDER BY `posts`.`time_add` DESC;");
          $posts = mysqli_fetch_all($que);
        } else {
          $que = mysqli_query($db, 'SELECT name_random, posts.name, type, visits, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users USING(username) ORDER BY `posts`.`time_add` DESC;');
          $posts = mysqli_fetch_all($que);
        }
        echo "
          <div class='row'>
            <div class='col-12'>
              <div class='card mb-4'>
                <div class='card-header pb-0'>
                  <h6>Add New Post..</h6>
                </div>
                <div class='card-body px-0 pt-0 pb-2'>
                  <div class='table-responsive p-0'>
                    <table class='table align-items-center justify-content-center mb-0'>
                      <tbody>
                        <tr class='d-flex align-items-center justify-content-between bg-light rounded-3'>
                          <td>
                            <div class='d-flex px-2'>
                              <div>
                                <img src='../../images/4878033.jpg' class='avatar avatar-sm rounded-circle me-2' alt='xd'>
                              </div>
                              <div class='my-auto'>
                                <h6 class='mb-0 text-sm'>Add New Post In Blog....</h6>
                              </div>
                            </div>
                          </td>
                          <td class='align-middle'>
                            <a href='?site=add_post' class='badge badge-sm bg-gradient-success' style='transition:0.3s'>Add New Post</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        ";
        echo "    
          <div class='row'>
            <div class='col-12'>
              <div class='card mb-4'>
                <div class='card-header pb-0'>
                  <h6>Posts</h6>
                </div>
                <div class='card-body px-0 pt-0 pb-2'>
                  <div class='table-responsive p-0'>
                    <table class='table align-items-center mb-0'>
                      <thead>
                        <tr>
                          <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Name</th>
                          <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Watched</th>
                          <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Type</th>
                          <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Data</th>
                          <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'></th>
                        </tr>
                      </thead>
        ";
        foreach ($posts as $post) {
          echo "
          <tr>
            <td>
              <div class='d-flex px-2 py-1'>
                <div>
                  <img src='../../images/posts/img_post/$post[5]' class='avatar avatar-sm me-3' alt='post'>
                </div>
                <div class='d-flex flex-column justify-content-center'>
                  <h6 class='mb-0 text-sm'>$post[1]</h6>
                  <p class='text-xs text-secondary mb-0'>Anther: $post[6]</p>
                </div>
              </div>
            </td>
            <td>
              <p class='text-xs font-weight-bold mb-0'>$post[3]</p>
            </td>
            <td class='align-middle text-center text-sm'>
              <span class='badge badge-sm bg-gradient-success'>$post[2]</span>
            </td>
            <td class='align-middle text-center'>
              <span class='text-secondary text-xs font-weight-bold'>$post[4]</span>
            </td>
            <td class='align-middle'>
            <a href='?site=edit_post&post_edit=$post[0]' class='text-secondary font-weight-bold text-xs' data-toggle='tooltip' data-original-title='Edit user'>
              Edit
            </a>
              <br>
              <form action='' method='POST'>
                <label for='$post[0]' class='text-secondary font-weight-bold text-xs' style='margin:0;cursor:pointer'>Delete</label>
                <input type='submit' id='$post[0]' value='$post[0]' name='del_post' style='display:none;'>
              </form>
            </td>
          </tr>
        ";
        }
        echo "
                    </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        ";
      } elseif ($url_page == 'add_post') {
        echo "<link rel='stylesheet' href='../assets/css/add_post.css'>";
        echo "
        <div class='alert alert-warning alert-light fade show info-post' role='alert'>
          <div class='info' dir='rtl'>
            <h3 class='text-center'>تعليمات الكتابة</h3>
            <div class='box'>
              <p>- اذا كنت تريد جعل النص عريض:</p>
              <p> اكتب في بداية النص هكذا (o_b) وفي النهاية (c_b)</p>
            </div>
            <div class='box'>
              <p>- اذا كنت تريد نزول الي سطر جديد:</p>
              <p>اكتب هكذا /n</p>
            </div>
            <div class='box'>
              <p>- اذا كنت تريد عمل رابط علي كلمة:</p>
              <p>اكتب هكذا l_k[كلمة تريديها](الرابط)l_k</p>
            </div>
          </div>
          <button type='button' class='btn btn-success button-close ' data-bs-dismiss='alert' aria-label='Close'>Close</button>
        </div>
        ";
        echo "
          <!-- Start Add Post -->
          <h2 class='text-center mt-3 mb-3'>اضافة مقالة جديد</h2>
          <div class='post' dir='rtl'>
            <div class='container'>
              <form action='' method='POST' enctype='multipart/form-data'>
                <div class='parent'>
        ";
        echo "<input type='text' placeholder='اكتب عنوان المقالة...' name='title' value='";
        if (isset($title)) {
          echo $title;
        }
        echo "'>";
        if (isset($errors)) {
          if (in_array('title-empty', $errors)) {
            echo '<p class="error">* يجب وضع عنوان للمقالة.</p>';
          } elseif (in_array('title-match', $errors)) {
            echo '<p class="error">* يجب لا يتخطي العنوان 50 حرف.</p>';
          }
        }
        echo "<textarea name='text_one' placeholder='اكتب موضوع المقاله...' required>";
        if (isset($text_one)) {
          echo $text_one;
        }
        echo "</textarea>";
        if (isset($errors)) {
          if (in_array('text-empty', $errors)) {
            echo '<p class="error">* يجب وضع محتوي للمقاله!!</p>';
          }
        }
        echo "
        <div class='file_input'>
          <label for='bg_img_file_one'>اضافة صور هنا</label>
          <input type='file' name='bg_img_file_one[]' id='bg_img_file_one' multiple='multiple'>
        </div>
        <textarea name='text_two' placeholder='اكتب موضوع المقاله...'>";
        if (isset($text_two)) {
          echo $text_two;
        }
        echo "</textarea>
            <div class='file_input'>
            <label for='bg_img_file_two'>اضافة صور هنا</label>
            <input type='file' name='bg_img_file_two[]' id='bg_img_file_two' multiple='multiple'>
          </div>
        </div>
        <div class='parent'>
          <input type='submit' value='اضافة' name='up_post'>
          <div class='file_input'>
            <label for='bg_img_file'>* اضف خلفيه للمقاله</label>
            <input type='file' name='bg_img_file' id='bg_img_file'>
        ";
        if (isset($errors)) {
          if (in_array('bg-img', $errors)) {
            echo '<p class="error">* تحتاح الي صورة رائسية للمقاله.</p>';
          }
        }
        echo "
          </div>
          <div class=' opstions'>
            <select name='type'>";
        foreach ($types_post as $typs) {
          echo "<option value='$typs'>$typs</option>";
        }
        echo  "
          </select>
            <div class='allow_comm'>
              <label for='allow_comm'>السماح بالتعليقات</label>
              <input type='checkbox' name='allow_comm' id='allow_comm' checked>
            </div>
          </div>
          <textarea name='des' placeholder='اكتب وصف المقاله...' required>";
        if (isset($des)) {
          echo $des;
        }
        echo "</textarea>";
        if (isset($errors)) {
          if (in_array('des-not', $errors)) {
            echo '<p class="error">* يجب وضع ملخص بسيط للمقالة.</p>';
          } elseif (in_array('des-match', $errors)) {
            echo '<p class="error">* يجب لا يتخطي 200 حرف.</p>';
          }
        }
        echo "
                  </div>
              </form>
            </div>
          </div>
        ";
      } elseif ($url_page == 'edit_post') {
        if ($allow_edit == true) {
          echo '<link id="pagestyle" href="../assets/css/add_post.css" rel="stylesheet" />';
          echo "
            <div class='alert alert-warning alert-light fade show info-post' role='alert'>
              <div class='info' dir='rtl'>
                <h3 class='text-center'>تعليمات الكتابة</h3>
                <div class='box'>
                  <p>- اذا كنت تريد جعل النص عريض:</p>
                  <p> اكتب في بداية النص هكذا (o_b) وفي النهاية (c_b)</p>
                </div>
                <div class='box'>
                  <p>- اذا كنت تريد نزول الي سطر جديد:</p>
                  <p>اكتب هكذا /n</p>
                </div>
                <div class='box'>
                  <p>- اذا كنت تريد عمل رابط علي كلمة:</p>
                  <p>اكتب هكذا l_k[الرابط](كلمة الرابط)l_k</p>
                </div>
              </div>
              <button type='button' class='btn btn-success button-close' data-bs-dismiss='alert' aria-label='Close'>Close</button>
            </div>
          ";
          echo "
          <!-- Start Add Post -->
          <h2 class='text-center mt-3 mb-3'>قم بتعديل علي مقالة سابقه</h2>
          <div class='post' dir='rtl'>
            <div class='container'>
              <form action='' method='POST' enctype='multipart/form-data'>
                <div class='parent'>
                  <input type='text' placeholder='اكتب عنوان المقالة...' name='title' value='";
          if (isset($title)) {
            echo $title;
          } else {
            echo $post_edit[2];
          }
          echo "'>";
          if (isset($errors)) {
            if (in_array('title-empty', $errors)) {
              echo "<p class='error'>* يجب وضع عنوان للمقالة.</p>";
            } elseif (in_array('title-match', $errors)) {
              echo "<p class='error'>* يجب لا يتخطي العنوان 50 حرف.</p>";
            }
          }
          echo "<textarea name='text_one' placeholder='اكتب موضوع المقاله...' required>";
          if (isset($text_one)) {
            echo $text_one;
          } else {
            echo $post_edit[3];
          }
          echo "</textarea>";
          if (isset($errors)) {
            if (in_array('text-empty', $errors)) {
              echo "<p class='error'>* يجب وضع محتوي للمقاله!!</p>";
            }
          }
          echo "
            <div class='file_input'>
            <label for='bg_img_file_one'>تغير صور هنا</label>
            <input type='file' name='bg_img_file_one[]' id='bg_img_file_one' multiple='multiple'>
          </div>
          <textarea name='text_two' placeholder='اكتب موضوع المقاله...'>";
          if (isset($text_two)) {
            echo $text_two;
          } else {
            echo $post_edit[4];
          }
          echo "
          </textarea>
              <div class='file_input'>
                <label for='bg_img_file_two'>تغير صور هنا</label>
                <input type='file' name='bg_img_file_two[]' id='bg_img_file_two' multiple='multiple'>
              </div>
            </div>
            <div class=' parent'>
              <input type='submit' value='تحديث' name='up_post'>
              <div class='file_input'>
                <label for='bg_img_file'>* تغير خلفيه للمقاله</label>
                <input type='file' name='bg_img_file' id='bg_img_file' value='";
          echo $post_edit[13];
          echo "'>";
          if (isset($errors)) {
            if (in_array('bg-img', $errors)) {
              echo "<p class='error'>* تحتاح الي صورة رائسية للمقاله.</p>";
            }
          }
          echo "
          </div>
          <div class=' opstions'>
            <select name='type'>";
          $sql = "SELECT types_post FROM `blog_design`;";
          $ty_p_ans = mysqli_fetch_row(mysqli_query($db, $sql));
          $ty_p = explode(',', $ty_p_ans[0]);
          print_r($ty_p);
          foreach ($ty_p as $p) {
            echo "<option value='$p'";
            if ($p == $post_edit[12]) {
              echo "selected";
            }
            echo ">$p</option>";
          }
          echo "    </select>
            <div class='allow_comm'>
                <label for='allow_comm'>السماح بالتعليقات</label>
                <input type='checkbox' name='allow_comm' id='allow_comm' checked>
              </div>
            </div>
            <textarea name='des' placeholder='اكتب وصف المقاله...' required>";
          if (isset($des)) {
            echo $des;
          } else {
            echo $post_edit[7];
          }
          echo "</textarea>";
          if (isset($errors)) {
            if (in_array('des-not', $errors)) {
              echo "<p class='error'>* يجب وضع ملخص بسيط للمقالة.</p>";
            } elseif (in_array('des-match', $errors)) {
              echo "<p class='error'>* يجب لا يتخطي 200 حرف.</p>";
            }
          }
          echo "
              </div>
              </form>
            </div>
          </div>";
        } else {
          header('Location: ../../404.php?num_site=0');
        }
      } else {
        header('Location: ../../404.php?num_site=0');
      }
      ?>
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