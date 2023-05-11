<?php
session_start();
ob_start();

// Include Cont File => [Header, Footer, Others..]
include_once('cont.php');
include_once('db.php');

if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];

  // Check From Permission For This User
  $sql = "SELECT users.permission FROM `users` WHERE users.username = '$username';";
  $que = mysqli_query($db, $sql);
  $permission = mysqli_fetch_row($que);

} elseif (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];
} else {
  $username = '';
}

// Get Name Post Used URL.
$url_post = filter_var(explode("=", $_SERVER['QUERY_STRING'])[1], FILTER_SANITIZE_NUMBER_INT);

// Get Content Post.
$sql = "SELECT posts.name, users.name, visits, time_add, bg_img_post, content_one, img_box_one, content_two, img_box_two, comment FROM `posts` INNER JOIN users USING(username) WHERE posts.name_random = $url_post;";
$que = mysqli_query($db, $sql);
$row = mysqli_fetch_row($que);

$allow_comments = '/admin@dont_allow_comm';

// Format Post Text
$search_text_format = ['/n', '\n', '(o_b)', '(c_b)', 'l_k[', '](', ')l_k'];
$replace_text_format = ['<br>', '<br>', '<b>', '</b>', '<a href="', '">', '</a>'];


// If Don't Have This Page Transform To 404 Page
if (!isset($row) || !isset($url_post)) {
  header('Location: 404.php?num_site=1');
}

// Transform String To Url Image
$imgs_one = explode(',', $row[6]);
$imgs_two = explode(',', $row[8]);

// Get All Comments
$sql = mysqli_query($db, "SELECT comments.id, users.name, comments.message, comments.time_add, users.img_profile, users.permission FROM `comments` INNER JOIN `users` ON (users.username = comments.username) AND (comments.random_post = '$url_post') ORDER BY `comments`.`time_add` DESC;");
$comments = mysqli_fetch_all($sql);

// Add Comment To Blog.
if ($allow_comments == '/admin@dont_allow_comm') {
  if (isset($_POST['send_mess'])) {
    $mess = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
    $date = date_format(date_create(), "Y-m-d");
    $sql = "INSERT INTO `comments` (`id`, `username`, `message`, `time_add`, `random_post`) VALUES (NULL, '$username', '$mess', '$date', '$url_post');";
    $que = mysqli_query($db, $sql);
    header("Refresh: 0");
  }
}


// First You Open Video, +1 Visit.
$visit = $row[2] + 1;
$que = mysqli_query($db, "UPDATE `posts` SET `visits` = '$visit' WHERE posts.name_random = $url_post;");
mysqli_query($db, "UPDATE `blog_design` SET `past_visits` = past_visits + 1;");

?>
<!DOCTYPE html>
<html lang="ar" dir='rtl'>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/post.css">
  <link rel="icon" type="image/x-icon" href="images/design_site/<?php echo $icon_site; ?>">
  <title>
    <?php echo $name_site . " | " . $row[0]; ?>
  </title>
</head>

<body>
  <!-- Start Header -->
  <header>
    <?php header_section(); ?>
  </header>
  <!-- Start Menu -->
  <section class="menu">
    <?php menu(); ?>
  </section>
  <!-- Start Content -->
  <section class="content">
    <div class="container">
      <div class="curent_post">
        <div class="head">
          <div class="title">
            <?php echo $row[0] ?>
          </div>
          <div class="info">
            <div class="date"><i class="fa-solid fa-calendar-days"></i>
              <?php echo $row[3] ?>
            </div>
            <div class="admin"><i class="fa-solid fa-user"></i>
              <?php echo $row[1] ?>
            </div>
          </div>
        </div>
        <div class="content_post">
          <img src="images/posts/img_post/<?php echo $row[4] ?>" alt="" class="bg-post">
          <p class="text">
            <?php echo str_replace($search_text_format, $replace_text_format, $row[5]);
            ; ?>
          </p>
          <?php
          if (!empty($imgs_one[0])) {
            foreach ($imgs_one as $img) {
              echo "<img src='images/posts/img_post/$img' alt='img_post' class='bg-post'>";
            }
          }
          ?>
          <p class="text">
            <?php echo str_replace($search_text_format, $replace_text_format, $row[7]); ?>
          </p>
          <?php
          if (!empty($imgs_two[0])) {
            foreach ($imgs_two as $img) {
              echo "<img src='images/posts/img_post/$img' alt='img_post' class='bg-post'>";
            }
          }
          ?>
        </div>
        <div class="commit">
          <div class="title_section">
            <h2 class="title">تعليقات</h2>
          </div>
          <div class="parent <?php if ($allow_comments == $row[9] || !isset($permission)) {
            echo "allow_comment";
          } ?> ">
            <?php
            if ($allow_comments == $row[9]) {
              echo "";
            }
            ?>

            <form action="" method="POST" class="send_mess" <?php if ($allow_comments != $row[9]) {
              echo "name='send_mess'";
            } ?>>
              <h2><i class="fa-solid fa-comment"></i> إرسال تعليق</h2>
              <div class="add_commit">
                <?php
                $img_main_user = 'images/user_profile/someone.png';
                if (strlen($username) != 0) {
                  $sql = "SELECT img_profile FROM `users` WHERE username = '$username';";
                  $img_main_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];
                }
                echo "<img src='images/user_profile/$img_main_user' onerror='this.src=`images/user_profile/someone.png`;this.onerror=``;' alt='icon someone'>";
                ?>
                <input type="text" placeholder="ادخل تعليقك..." name="msg">
                <input type="submit" <?php if ($allow_comments != $row[9] || !isset($permission)) {
                  echo "name='send_mess'";
                } ?> value="Send">
              </div>
            </form>
            <div class="last_comm">
              <h2><i class="fa-solid fa-comments"></i> اخر التعليقات</h2>
              <?php
              foreach ($comments as $comment) {
                echo "
                  <div class='box'>
                    <img src='images/user_profile/$comment[4]' alt=''>
                    <div class='info'>
                      <div class='name'><i class='fa-solid fa-user'></i> $comment[1]</div>
                      <div class='date'><i class='fa-solid fa-calendar-days'></i> $comment[3]</div>
                      <div class='dec'>
                        $comment[2]";
                if (isset($permission) && ($permission[0] == 'admin' || $permission[0] == 'owner')) {
                  echo "
                    <form action='' method='POST'>
                      <input type='submit' name='del_comm' value='$comment[0]' id='$comment[0]' style='display:none;'>
                      <label for='$comment[0]' class='del_comm'>Delete</label>
                    </form>
                  ";
                }
                echo "</div>
                    </div>
                  </div>
                ";
              }

              // If Comment Is Empty.
              if (empty($comments)) {
                echo "
                  <div class='box'>
                    لا يوجد تعليقات هنا.
                  </div>
                  ";
              }

              // Delete Comm
              if (isset($_POST['del_comm'])) {
                $commit_delete = $_POST['del_comm'];
                $sql = "DELETE FROM comments WHERE `comments`.`id` = $commit_delete";
                $que = mysqli_query($db, $sql);
                header("Refresh: 0");
              }

              ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- Start Footer -->
  <footer>
    <?php footer(); ?>
  </footer>

  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>