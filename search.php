<?php
// Include Cont File => [Header, Footer, Others..]
include_once('cont.php');

// Get Value Search Form URL
$search_link = urldecode(str_replace("+", '%', explode('=', $_SERVER['REQUEST_URI'])[1]));
$search = explode('&', $search_link)[0];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="icon" type="image/x-icon" href="images/design_site/<?php echo $icon_site; ?>">
  <title><?php echo $name_site . " | Search"; ?></title>
  <style>
    .answer {
      color: var(--bs-dark);
      padding: 10px 0;
      margin: 10px 0;
      text-align: center;
      font-size: 1.1rem;
    }
  </style>
</head>

<body>
  <!-- Start Header -->
  <header>
    <?php header_section(); ?>
  </header>
  <!-- Start Menu -->
  <section class="menu">
    <?php menu(); ?>

    <?php //echo $menu; 
    ?>
  </section>
  <!-- Start Content -->
  <section class="content">
    <div class="container">
      <div class="news">
        <div class="title_section">
          <h2 class="title">نتيجة البحث..</h2>
        </div>
        <div class="posts">
          <?php
          $nums = 0;
          $que = mysqli_query($db, "SELECT name_random, posts.name, posts.des, type, visits, comment, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users USING(username) WHERE posts.name LIKE '%$search%' ORDER BY `posts`.`time_add`;");
          $posts = mysqli_fetch_all($que);
          if (empty($posts)) {
            $que = mysqli_query($db, "SELECT name_random, posts.name, posts.des, type, visits, comment, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users USING(username) WHERE posts.type LIKE '$search' ORDER BY `posts`.`time_add`;");
            $posts = mysqli_fetch_all($que);
          }
          foreach ($posts as $post) {
            $nums++;
            echo "
            <div class='box'>
              <a href='post.php?name=$post[0]'><img src='images/posts/img_post/$post[7]' class='img_size' alt='post'><span>$post[3]</span></a>
              <div class='post_info'>
                <a href='post.php?name=$post[0]' class='title'>$post[1]</a>
                <div class='info'>
                  <span><a href='#'>$post[8]</a></span>
                  <span>$post[6]</span>
                </div>
                <div class='des'>$post[2]</div>
                <div class='buttons'>
                  <a href='post.php?name=$post[0]' class='button-color'>قراءة المزيد</a>
                  <a href='#' class='button-color'><i class='fa-sharp fa-solid fa-share-nodes'></i></a>
                </div>
              </div>
            </div>";
          }
          if ($nums > 0) {
            echo '<p class="answer">لا يوجد المزيد..</p>';
          } else {
            echo '<p class="answer">لا يوجد ما تبحث عنه</p>';
          }
          ?>
        </div>
      </div>
      <div class="row_other">
        <div class="top_post">
          <div class="title_section">
            <h2 class="title">المشاركات الشائعة</h2>
          </div>
          <div class="posts">
            <?php
            $sql = "SELECT name_random, bg_img_post, time_add, name, posts.des, visits FROM `posts` ORDER BY `posts`.`visits` DESC LIMIT 3;";
            $que = mysqli_query($db, $sql);
            $rows = mysqli_fetch_all($que);

            foreach ($rows as $post) {
              echo "
              <div class='box'>
                <div class='head'>
                  <a href='post.php?name=$post[0]'><img src='images/posts/img_post/$post[1]' alt='img post'></a>
                  <div class='info'>
                    <span class='date'><i class='fa-solid fa-calendar-days'></i> $post[2]</span>
                    <a href='post.php?name=$post[0]' class='title'>$post[3]</a>
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