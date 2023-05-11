<?php
include_once('db.php');

// Get Every Thing About Design Blog.
$sql = "SELECT name_site, icon_site, urls_nav, types_post, colors, best_post, media_links FROM `blog_design`;";
$answer = mysqli_fetch_row(mysqli_query($db, $sql));
// Values 
$name_site = $answer[0];
$icon_site = $answer[1];
$urls_nav = $answer[2];
$types_post = $answer[3];
$colors = $answer[4];
$best_post = $answer[5];
$media_links = explode(',', $answer[6]);


// Types Posts
$urls_nav = explode(',', $urls_nav);
$types_post = explode(',', $types_post);

function header_section()
{
  global $media_links;
  echo "
  <div class='container'>
    <ul>
      <li><a href='$media_links[0]'><i class='fa-brands fa-facebook-f icon_facebook'></i></a></li>
      <li><a href='$media_links[1]'><i class='fa-brands fa-youtube icon_youtube'></i></a></li>
      <li><a href='Dashboard/pages/sign-in.php' class='button-color'>Log</a></li>
    </ul>
    <ul class='form_box'>
      <div class='collapse collapse-horizontal' id='form_search'>
        <form action='search.php' method='GET'>
          <input type='text' name='search' placeholder='بحث...'>
          <input type='submit' name='send' value='Search'>
        </form>
      </div>
      <li><a class='button-color' data-bs-toggle='collapse' data-bs-target='#form_search' aria-expanded='false'
          aria-controls='form_search'><i class='fa-solid fa-magnifying-glass'></i></a></li>
      <img src='images/design_site/small_icon.x-icon' class='icon'>
    </ul>
  </div>
";
}

function menu()
{
  global $urls_nav, $img_site, $db;
  echo "  <div class='container'>
    <div class='row_other w-100 ' style='margin:10px 0;'>
      <div id='carouselExampleCaptions' class='carousel slide' style='border-radius: 10px; overflow: hidden; '>
        <div class='carousel-indicators'>
          <button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='0' class='active'
            aria-current='true' aria-label='Slide 1'></button>
          <button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='1'
            aria-label='Slide 2'></button>
          <button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='2'
            aria-label='Slide 3'></button>
        </div>
        <div class='carousel-inner'>";
  $sql = 'SELECT name_random, bg_img_post, name FROM `posts` ORDER BY `posts`.`visits` DESC LIMIT 3;';
  $que = mysqli_query($db, $sql);
  $rows = mysqli_fetch_all($que);
  $num_card = 0;
  foreach ($rows as $post) {
    if ($num_card == 1) {
      echo "
                <a href='post.php?name=$post[0]' class='carousel-item active'>
                  <img src='images/posts/img_post/$post[1]' style='height:350px;' class='d-block w-100' alt='...'>
                  <div class='carousel-caption d-none d-md-block'>
                    <h5 style='padding: 5px; background-color: #777777b5; border-radius: 5px;'>$post[2]</h5>
                  </div>
                </a>
                ";
    } else {
      echo "
              <a href='post.php?name=$post[0]' class='carousel-item'>
                <img src='images/posts/img_post/$post[1]' style='height:350px;' class='d-block w-100' alt='...'>
                <div class='carousel-caption d-none d-md-block'>
                  <h5 style='padding: 5px; background-color: #777777b5; border-radius: 5px;'>$post[2]</h5>
                </div>
              </a>
              ";
    }
    $num_card++;
  }
  echo "        </div>
        <button class='carousel-control-prev' type='button' data-bs-target='#carouselExampleCaptions'
          data-bs-slide='prev'>
          <span class='carousel-control-prev-icon' aria-hidden='true'></span>
          <span class='visually-hidden'>Previous</span>
        </button>
        <button class='carousel-control-next' type='button' data-bs-target='#carouselExampleCaptions'
          data-bs-slide='next'>
          <span class='carousel-control-next-icon' aria-hidden='true'></span>
          <span class='visually-hidden'>Next</span>
        </button>
      </div>
    </div>
  </div>";
  echo "
  <div class='container'>
    <nav class='w-100'>
      <ul>
        <li><a href='index.php'><i class='fa-solid fa-house-chimney home'></i></a></li>";
  foreach ($urls_nav as $tab) {
    echo "<li><a href='search.php?filter=$tab'>$tab</a></li>";
  }
  echo "</ul>
    </nav>
  </div>";
}

function footer()
{
  global $types_post, $db, $best_post;
  // Get Information About Best Post.
  $que = mysqli_query($db, "SELECT name_random, posts.name, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users ON (name_random = '$best_post') AND (users.username = posts.username);");
  $post = mysqli_fetch_row($que);

  // IF The Post Deleted, Choose The Top Visits.
  if (!isset($post)) {
    $que = mysqli_query($db, "SELECT name_random, posts.name, time_add, bg_img_post, users.name FROM `posts` INNER JOIN users On (users.username = posts.username) ORDER BY `posts`.`visits` ASC LIMIT 1;");
    $post = mysqli_fetch_row($que);
  }

  echo "
  <div class='container'>
    <div class='sections'>
      <div class='title_section'>
        <h2 class='title'>التسميات</h2>
      </div>
      <div class='parent'>";
  foreach ($types_post as $type) {
    echo "
      <a href='search.php?filter=$type' class='box'>
        <i class='fa-solid fa-tag'></i>
        <span>$type</span>
      </a>";
  }
  echo "</div>
    </div>
    <div class='more'>
      <div class='pages'>
        <div class='title_section'>
          <h2 class='title'>الصفحات</h2>
        </div>
        <div class='parent'>
          <a href='index.php' class='box'>
            <i class='fa-solid fa-file-code'></i> <span>الرئسية</span>
          </a>
        </div>
      </div>
      <div class='top_post'>
        <div class='title_section'>
          <h2 class='title'>مشاركة مميزة</h2>
        </div>
        <div class='box'>
          <a href='post.php?name=$post[0]'><img src='images/posts/img_post/$post[3]' class='img_size' alt='Best Post'></a>
          <a href='post.php?name=$post[0]'>$post[1]</a>
          <div class='info'>
            <div>
              <i class='fa-solid fa-user'></i> $post[4]
            </div>
            <div>
              <i class='fa-solid fa-calendar-xmark'></i> $post[2]
            </div>
          </div>
        </div>
      </div>
      <div class='connect'>
        <div class='title_section'>
          <h2 class='title'>نموذج الاتصال</h2>
        </div>
        <form action='' method='POST'>
          <div class='box'>
            <i class='fa-solid fa-user'></i>
            <input type='text' name='name' placeholder='الاسم'>
          </div>
          <div class='box'>
            <i class='fa-solid fa-envelope'></i>
            <input type='email' name='email' placeholder='بريد الاكتروني'>
          </div>
          <div class='box textarea'>
            <i class='fa-solid fa-quote-right'></i>
            <textarea type='text' name='mess' placeholder='رسالة'></textarea>
          </div>
          <input type='submit' name='send' value='إرسال'>
        </form>
      </div>
    </div>
  </div>
  <div class='copyright'>
    <div class='container'>
      <i class='fa-solid fa-t icon'></i> جميع الحقوق محفوظة <a href='https://eg.linkedin.com/in/thomas-emad-71bb681ba/'>Me</a>
    </div>
  </div>
";
}


// User Want Send Email
if (isset($_POST['send'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $mess = $_POST['mess'];

  // Send Message
  date_default_timezone_set("Africa/Cairo");
  $date = date_format(date_create(), 'Y/m/d h-i');
  include('phpmailer.php');
  $mail->Subject = "New Message";
  $mail->Body = "
    <div style='background-color: #222; font-size: 1.2rem; text-align: left; padding: 15px; color: #fff;'>
      <h2 style='text-align:center; font-size:2rem'>BlogTomey</h2>
      <h3 style='font-size:1.4rem'>New Message</h3>
      <div style='padding: 5px 0; font-size:1rem;'>
        <div style='color: #fff;'>Your Name: $name</div>
        <div style='color: #fff;'>Your Email: $email</div>
        <div style='color: #fff;'>Time Create: $date</div>
        <div style='color: #fff;'>Message:<br><span style='color: #ddd; font-size: 0.9rem'>$mess</span></div>
      </div>
    </div>";
  $mail->send();

  header("Refresh: 0;");
}



if ($colors == 'dark-color') {
  echo "<style>body{background-color: #111 !important;color: #fff !important;} :root{--black-color: var(--bs-gray-600) !important; --text: #fff !important; --main: #6e5aff !important;}</style>";
} elseif ($colors == 'light-color') {
  echo "<style>:root{--black-color: #111111 !important; --colors:linear-gradient(to left, #6e5aff, #bd3057) !important; --main: #6e5aff !important;}</style>";
}