<?php
// Include Cont File => [Header, Footer, Others..]
include_once('cont.php');

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
  <title><?php echo $name_site . " | Home"; ?></title>
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
      <div class="news">
        <div class="title_section">
          <h2 class="title">آخر المشاركات</h2>
          <a href="search.php?search=&send=Search">المزيد...</a>
        </div>
        <div class="posts">
          <?php
          // Get Url Post
          $url_path_post_array = explode('/', $_SERVER['REQUEST_URI']);
          $url_path_post = 'https://' . $_SERVER['HTTP_HOST'] . '/' .  $url_path_post_array[1] . '/' . $url_path_post_array[2] . '/';

          $que = mysqli_query($db, "SELECT name_random, posts.name, posts.des, type, visits, comment, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users USING(username) ORDER BY `posts`.`time_add` DESC LIMIT 5;");
          $posts = mysqli_fetch_all($que);
          foreach ($posts as $post) {
            $link = $url_path_post . "post.php?name=$post[0]";
            echo "
            <!-- Modal -->
            <div dir='ltr' class='modal fade' id='share_$post[0]' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
              <div class='modal-dialog'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <h1 class='modal-title fs-5' id='exampleModalLabel'>Copy Link Post</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                  </div>
                  <div class='modal-body'>
                    <input class='form-control' type='text' value='$link' placeholder='Disabled input' disabled>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                  </div>
                </div>
              </div>
            </div>
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
                  <a href='#share_$post[0]' data-bs-toggle='modal' class='button-color'><i class='fa-sharp fa-solid fa-share-nodes'></i></a>
                </div>
              </div>
            </div>";
          }
          ?>
        </div>
        <a href="search.php?search=&send=Search" class="more">المزيد من المشاركات</a>
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