<?php
session_start();
ob_start();

// Include Cont File => [Header, Footer, Others..]
include_once('cont.php');
include_once('db.php');

if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
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
$replace_text_format = ['<br>', '<br>', '<b>', '</b>',  '<a href="', '">', '</a>'];


// If Don't Have This Page Transform To 404 Page
if (!isset($row) || !isset($url_post)) {
  header('Location: 404.php?num_site=1');
}

// Transform String To Url Image
$imgs_one = explode(',', $row[6]);
$imgs_two = explode(',', $row[8]);

// Add Comment To Blog.
if ($allow_comments == '/admin@dont_allow_comm') {
  if (isset($_POST['send_mess'])) {
    $mess = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
    $date = date_format(date_create(), "Y-m-d");
    $sql = "UPDATE posts SET comment = '$row[9]/nucm=? info=?$username info=?$mess info=?$date info=?$url_post' WHERE posts.name_random = $url_post;";
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
  <title><?php echo $name_site . " | " . $row[0]; ?></title>
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
          <div class="title"><?php echo $row[0] ?></div>
          <div class="info">
            <div class="date"><i class="fa-solid fa-calendar-days"></i> <?php echo $row[3] ?></div>
            <div class="admin"><i class="fa-solid fa-user"></i> <?php echo $row[1] ?></div>
          </div>
        </div>
        <div class="content_post">
          <img src="images/posts/img_post/<?php echo $row[4] ?>" alt="" class="bg-post">
          <p class="text"><?php echo str_replace($search_text_format, $replace_text_format, $row[5]);; ?></p>
          <?php
          if (!empty($imgs_one[0])) {
            foreach ($imgs_one as $img) {
              echo "<img src='images/posts/img_post/$img' alt='img_post' class='bg-post'>";
            }
          }
          ?>
          <p class="text"><?php echo str_replace($search_text_format, $replace_text_format, $row[7]); ?></p>
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
          <div class="parent <?php if ($allow_comments == $row[9]) {
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
                <input type="submit" <?php if ($allow_comments != $row[9]) {
                                        echo "name='send_mess'";
                                      } ?> value="Send">
              </div>
            </form>
            <div class="last_comm">
              <h2><i class="fa-solid fa-comments"></i> اخر التعليقات</h2>
              <?php
              $big = (explode("/nucm=?", $row[9]));

              $num = 0;

              for ($i = 1; $i < (sizeof($big)); $i++) {
                // Tansformtion commits To Array, For All Commits.
                $commit = explode('info=?', $big[$i]);
                $sql = "SELECT name, img_profile FROM `users` WHERE username = '$commit[1]';";
                $row = mysqli_fetch_row(mysqli_query($db, $sql));
                if (!isset($row)) {
                  $username_comment = 'Unknown';
                  $img_comment_user = 'someone.png';
                } else {
                  $username_comment = $row[0];
                  $img_comment_user = $row[1];
                }

                $permission = '';
                if ($username != '') {
                  $sql = "SELECT permission FROM `users` WHERE username = '$username';";
                  $permission = mysqli_fetch_row(mysqli_query($db, $sql))[0];
                }
                echo "
                  <div class='box'>
                    <img src='images/user_profile/$img_comment_user' alt=''>
                    <div class='info'>
                      <div class='name'><i class='fa-solid fa-user'></i> $username_comment</div>
                      <div class='date'><i class='fa-solid fa-calendar-days'></i> $commit[3]</div>
                      <div class='dec'>
                        $commit[2]";
                if ($permission == 'admin' || $permission == 'owner') {
                  echo "
                        <form action='' method='POST'>
                          <input type='submit' name='del_comm' value='$i' id='$i' style='display:none;'>
                          <label for='$i' class='del_comm'>Delete</label>
                        </form>
                        ";
                }
                echo  "</div>
                    </div>
                  </div>
                ";
                $num++;
              }

              // If Comment Is Empty.
              if (sizeof($big) == 1) {
                echo "
                  <div class='box'>
                    لا يوجد تعليقات هنا.
                  </div>
                  ";
              }

              // Delete Comm
              if (isset($_POST['del_comm'])) {
                $commit_delete = $_POST['del_comm'];
                unset($big[$commit_delete]);
                $new_commit_as_string = implode('/nucm=?', $big);

                $sql = "UPDATE posts SET comment = '$new_commit_as_string' WHERE posts.name_random = $url_post;";
                $que = mysqli_query($db, $sql);
                header("Refresh: 0");
              }

              ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row_other">
        <div class="top_post">
          <div class="title_section">
            <h2 class="title">المشاركات الشائعة</h2>
          </div>
          <div class="posts">
            <?php
            $sql = "SELECT name_random, bg_img_post, time_add, name, posts.des, visits FROM `posts` ORDER BY `posts`.`visits` DESC LIMIT 4;";
            $que = mysqli_query($db, $sql);
            $rows = mysqli_fetch_all($que);
            foreach ($rows as $post) {
              echo "
              <div class='box'>
                <div class='head'>
                  <a href='post.php?name=$post[0]'><img src='images/posts/img_post/$post[1]' alt='img post'></a>
                  <div class='info'>
                    <span class='date'><i class='fa-solid fa-calendar-days'></i> $post[2]</span>
                    <a href='#' class='title'>$post[3]</a>
                  </div>
                </div>
                <div class='dec'>$post[4]</div>
              </div>
              ";
            }

            ?>
          </div>
        </div>
        <div class="sections">
          <div class="title_section">
            <h2 class="title">التسميات</h2>
          </div>
          <div class="parent">
            <?php
            $sql = "SELECT type, COUNT(type) FROM `posts` GROUP By type ORDER BY `COUNT(type)` DESC";
            $types = mysqli_fetch_all(mysqli_query($db, $sql));
            foreach ($types as $ty) {
              echo "
                <a class='box' href='search.php?search=as'>
                  <div class='title'>
                    <i class='fa-solid fa-folder'></i>
                    <span>$ty[0]</span>
                  </div>
                  <div class='num'>$ty[1]</div>
                </a>
              ";
            }
            ?>
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