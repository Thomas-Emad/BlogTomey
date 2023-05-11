<?php
session_start();
ob_start();

// Check If User Sign-up?, Don't Open This Page.
if (isset($_SESSION['username'])) {
  $username = $_SESSION['username'];
} elseif (isset($_COOKIE['username'])) {
  $username = $_COOKIE['username'];
} else {
  header("Location: sign-in.php");
  exit();
}

// Check From Your Permission For Print Pages.
$sql = "SELECT permission FROM `users` WHERE username = '$username'";
$permission_user = mysqli_fetch_row(mysqli_query($db, $sql))[0];

date_default_timezone_set('Africa/Cairo');

// Header Page 
$sql = "SELECT name_site, icon_site FROM `blog_design`;";
$blog_design = mysqli_fetch_row(mysqli_query($db, $sql));

function header_page($name_page, $style_page) {
  global $blog_design;
  echo "
      <head>
          <meta charset='utf-8' />
          <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
          <link rel='apple-touch-icon' sizes='76x76' href='../assets/img/apple-icon.png'>
          <link rel='icon' type='image/png' href='../../images/design_site/$blog_design[1]'>
          <title>
              $name_page | Dashboard | $blog_design[0]
          </title>
          <!--     Fonts and icons     -->
          <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' />
          <!-- Nucleo Icons -->
          <link href='../assets/css/nucleo-icons.css' rel='stylesheet' />
          <link href='../assets/css/nucleo-svg.css' rel='stylesheet' />
          <!-- Font Awesome Icons -->
          <script src='https://kit.fontawesome.com/42d5adcbca.js' crossorigin='anonymous'></script>
          <link href='../assets/css/nucleo-svg.css' rel='stylesheet' />
          <!-- CSS Files -->
          <link id='pagestyle' href='../assets/css/soft-ui-dashboard.css?v=1.0.7' rel='stylesheet' />";
          if (strlen($style_page) > 2) {
            echo "<link href='../assets/css/$style_page' rel='stylesheet' />";
          }
  echo "</head>";
}


function aside($name_page)
{

  global $username, $permission_user, $blog_design;
  echo "
<aside class='sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 ' id='sidenav-main' style='z-index: 1000;'>
  <div class='sidenav-header'>
    <i class='fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none' aria-hidden='true' id='iconSidenav'></i>
    <a class='navbar-brand m-0' href='../../index.php' target='_blank'>
      <img src='../../images/design_site/$blog_design[1]' class='navbar-brand-img h-100' alt='main_logo'>
      <span class='ms-1 font-weight-bold'>$blog_design[0] Dashboard</span>
    </a>
    </div>
    <hr class='horizontal dark mt-0'>
    <div class='collapse navbar-collapse  w-auto ' id='sidenav-collapse-main'>
    <ul class='navbar-nav'>
      <!-- Main -->
      <li class='nav-item'>
        <a class='nav-link";
  if ($name_page == "Home") {
    echo " active";
  }
  echo "' href='../pages/dashboard.php'>
          <div class='icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center'>
            <svg width='12px' height='12px' viewBox='0 0 45 40' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
              <title>shop </title>
              <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                <g transform='translate(-1716.000000, -439.000000)' fill='#FFFFFF' fill-rule='nonzero'>
                  <g transform='translate(1716.000000, 291.000000)'>
                    <g transform='translate(0.000000, 148.000000)'>
                      <path class='color-background opacity-6' d='M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z'>
                      </path>
                      <path class='color-background' d='M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z'>
                      </path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class='nav-link-text ms-1'>Dashboard</span>
        </a>
      </li>
      <li class='nav-item'>
        <a class='nav-link";
  if ($name_page == "posts") {
    echo " active";
  }
  echo "' href='../pages/home_post.php?site=main_page'>
          <div class='icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center'>
            <svg width='12px' height='12px' viewBox='0 0 43 36' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
              <title>Posts</title>
              <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                <g transform='translate(-2169.000000, -745.000000)' fill='#FFFFFF' fill-rule='nonzero'>
                  <g transform='translate(1716.000000, 291.000000)'>
                    <g transform='translate(453.000000, 454.000000)'>
                      <path class='color-background opacity-6' d='M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z'>
                      </path>
                      <path class='color-background' d='M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z'>
                      </path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class='nav-link-text ms-1'>Posts</span>
        </a>
      </li>";
  if ($permission_user == 'owner') {
    echo "<li class='nav-item'>
        <a class='nav-link";
    if ($name_page == "Users") {
      echo " active";
    }
    echo "' href='../pages/users.php'>
          <div class='icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center'>
            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 640 512'><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d='M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z'/>
            <title>Users</title>
            </svg>        
          </div>
          <span class='nav-link-text ms-1'>Users</span>
        </a>
      </li>";
  }
  if ($permission_user == 'owner') {
    echo "
        <li class='nav-item'>
              <a class='nav-link";
    if ($name_page == "Edit Design") {
      echo " active";
    }
    echo "' href='../pages/edit_design.php'>
                <div class='icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center'>
                <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z'/></svg>                  </svg>        
                </div>
                <span class='nav-link-text ms-1'>Edit Design</span>
              </a>
            </li>
        ";
  }
  echo " <li class='nav-item mt-3'>
        <h6 class='ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6'>Account pages</h6>
      </li>

      <!-- Others -->
      <li class='nav-item'>
        <a class='nav-link";
  if ($name_page == "profile") {
    echo " active";
  }
  echo "'href='profile.php?user=$username'>
          <div class='icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center'>
            <svg xmlns='http://www.w3.org/2000/svg'  width='12px' height='20px' viewBox='0 0 448 512'><!--! Font Awesome Pro 6.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d='M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z'/>
            <title>Profile</title>
            </svg>
          </div>
          <span class='nav-link-text ms-1'>Profile</span>
        </a>
      </li>
      <li class='nav-item'>
        <a class='nav-link";
  if ($name_page == "posts") {
    echo " active";
  }
  echo "' href='../pages/home_post.php?site=add_post'>
          <div class='icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center'>
            <svg width='12px' height='20px' viewBox='0 0 40 40' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
              <title>New Post</title>
              <g stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'>
                <g transform='translate(-1720.000000, -592.000000)' fill='#FFFFFF' fill-rule='nonzero'>
                  <g transform='translate(1716.000000, 291.000000)'>
                    <g transform='translate(4.000000, 301.000000)'>
                      <path class='color-background' d='M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z'>
                      </path>
                      <path class='color-background opacity-6' d='M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z'>
                      </path>
                      <path class='color-background opacity-6' d='M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z'>
                      </path>
                      <path class='color-background opacity-6' d='M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z'>
                      </path>
                    </g>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class='nav-link-text ms-1'>New Post</span>
        </a>
      </li>
      <li class='nav-item'>
        <form action='' method='POST' class='nav-link'>
            <input type='submit'  value='Logout' name='logout' style='utline: none; border: none; padding: 5px 10px; background-color: #fff; width: 100%; color: var(--bs-dark); border-radius:10px;'>
        </form>
      </li>
    </ul>
  </div>
</aside>
  ";
}

function navbar($name_page)
{
  global $username, $name_page, $db, $permission_user;
  echo "
  <nav class='navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl' id='navbarBlur' navbar-scroll='true'>
    <div class='container-fluid py-1 px-3'>
      <nav aria-label='breadcrumb'>
        <ol class='breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5'>
          <li class='breadcrumb-item text-sm'><a class='opacity-5 text-dark' href='javascript:;'>Pages</a></li>
          <li class='breadcrumb-item text-sm text-dark active' aria-current='page'>$name_page</li>
        </ol>
        <h6 class='font-weight-bolder mb-0'>$name_page</h6>
      </nav>
      <div class='collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4' id='navbar'>
        <div class='ms-md-auto pe-md-3 d-flex align-items-center'>
        </div>
        <ul class='navbar-nav justify-content-end'>
          <li class='nav-item d-flex align-items-center'>
            <a href='profile.php?user=$username' class='nav-link text-body font-weight-bold px-0'>
              <i class='fa fa-user me-sm-1'></i>
              <span class='d-sm-inline d-none'>Profile</span>
            </a>
          </li>
          <li class='nav-item d-xl-none ps-3 d-flex align-items-center'>
            <a href='javascript:;' class='nav-link text-body p-0' id='iconNavbarSidenav'>
              <div class='sidenav-toggler-inner'>
                <i class='sidenav-toggler-line'></i>
                <i class='sidenav-toggler-line'></i>
                <i class='sidenav-toggler-line'></i>
              </div>
            </a>
          </li>
          <li class='nav-item px-3 d-flex align-items-center'>
            <a href='javascript:;' class='nav-link text-body p-0'>
              <i class='fa fa-cog fixed-plugin-button-nav cursor-pointer'></i>
            </a>
          </li>";

  if ($permission_user != 'user') {
    echo "
    <li class='nav-item dropdown pe-2 d-flex align-items-center'>
            <a href='javascript:;' class='nav-link text-body p-0' id='dropdownMenuButton' data-bs-toggle='dropdown' aria-expanded='false'>
              <i class='fa fa-bell cursor-pointer'></i>
            </a>
            <ul class='dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4' aria-labelledby='dropdownMenuButton'>";
    $sql = "SELECT news FROM `blog_design`;";
    $answer = mysqli_fetch_row(mysqli_query($db, $sql))[0];
    $str_to_array = array_reverse(explode('/new=?', $answer));
    $count = 0;
    foreach ($str_to_array as $new) {
      $new_onec = explode('info=?', $new);
      if (sizeof($new_onec) !== 1) {
        $count++;
        echo "
          <li class='mb-2'>
            <a class='dropdown-item border-radius-md' href='javascript:;'>
              <div class='d-flex py-1'>
                <div class='my-auto'>
                  <img src='../assets/img/theme/tim.png' class='avatar avatar-sm  me-3 '>
                </div>
                <div class='d-flex flex-column justify-content-center'>
                  <h6 class='text-sm font-weight-normal mb-1'>
                    <span class='font-weight-bold'>$new_onec[1]
                  </h6>
                  <p class='text-xs text-secondary mb-0 '>
                    <i class='fa fa-clock me-1'></i>
                    $new_onec[2]
                  </p>
                </div>
              </div>
            </a>
          </li>
        ";
        if ($count == 5) {
          break;
        }
      }
    }
  }

  echo "</ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  ";
}

function footer()
{
  echo "
  <footer class='footer pt-3'>
        <div class='container-fluid'>
          <div class='row align-items-center justify-content-lg-between'>
            <div class='col-lg-6 mb-lg-0 mb-4'>
              <div class='copyright text-center text-sm text-muted text-lg-start'>
                ©
                <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class='fa fa-heart'></i> by
                <a href='https://eg.linkedin.com/in/thomas-emad-71bb681ba/' class='font-weight-bold' target='_blank'>Thomas Emad</a>
                for a better web.
              </div>  
            </div>
            <div class='col-lg-6'>
              <ul class='nav nav-footer justify-content-center justify-content-lg-end'>
                <li class='nav-item'>
                  <a href='https://github.com/Thomas-Emad' class='nav-link text-muted' target='_blank'>GitHub</a>
                </li>
                <li class='nav-item'>
                  <a href='https://eg.linkedin.com/in/thomas-emad-71bb681ba/' class='nav-link text-muted' target='_blank'>Linkedin</a>
                </li>
                <li class='nav-item'>
                  <a href='../../index.php' class='nav-link text-muted' target='_blank'>Blog</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>  ";
}

function opitons_dashboard()
{
  echo "
    <div class='fixed-plugin'>
      <a class='fixed-plugin-button text-dark position-fixed px-3 py-2'>
        <i class='fa fa-cog py-2'> </i>
      </a>
      <div class='card shadow-lg '>
        <div class='card-header pb-0 pt-3 '>
          <div class='float-start'>
            <h5 class='mt-3 mb-0'>BlogTomey Dashboard</h5>
            <p>See our dashboard options.</p>
          </div>
          <div class='float-end mt-4'>
            <button class='btn btn-link text-dark p-0 fixed-plugin-close-button'>
              <i class='fa fa-close'></i>
            </button>
          </div>
          <!-- End Toggle Button -->
        </div>
        <hr class='horizontal dark my-1'>
        <div class='card-body pt-sm-3 pt-0'>
          <!-- Sidebar Backgrounds -->
          <div>
            <h6 class='mb-0'>Sidebar Colors</h6>
          </div>
          <a href='javascript:void(0)' class='switch-trigger background-color'>
            <div class='badge-colors my-2 text-start'>
              <span class='badge filter bg-gradient-primary active' data-color='primary' onclick='sidebarColor(this)'></span>
              <span class='badge filter bg-gradient-dark' data-color='dark' onclick='sidebarColor(this)'></span>
              <span class='badge filter bg-gradient-info' data-color='info' onclick='sidebarColor(this)'></span>
              <span class='badge filter bg-gradient-success' data-color='success' onclick='sidebarColor(this)'></span>
              <span class='badge filter bg-gradient-warning' data-color='warning' onclick='sidebarColor(this)'></span>
              <span class='badge filter bg-gradient-danger' data-color='danger' onclick='sidebarColor(this)'></span>
            </div>
          </a>
          <!-- Sidenav Type -->
          <div class='mt-3'>
            <h6 class='mb-0'>Sidenav Type</h6>
            <p class='text-sm'>Choose between 2 different sidenav types.</p>
          </div>
          <div class='d-flex'>
            <button class='btn bg-gradient-primary w-100 px-3 mb-2 active' data-class='bg-transparent' onclick='sidebarType(this)'>Transparent</button>
            <button class='btn bg-gradient-primary w-100 px-3 mb-2 ms-2' data-class='bg-white' onclick='sidebarType(this)'>White</button>
          </div>
          <p class='text-sm d-xl-none d-block mt-2'>You can change the sidenav type just on desktop view.</p>
          <!-- Navbar Fixed -->
          <div class='mt-3'>
            <h6 class='mb-0'>Navbar Fixed</h6>
          </div>
          <div class='form-check form-switch ps-0'>
            <input class='form-check-input mt-1 ms-auto' type='checkbox' id='navbarFixed' onclick='navbarFixed(this)'>
          </div>
          <hr class='horizontal dark my-sm-4'>
          <form action='' method='POST'>
            <input type='submit' value='Logout' name='logout' class='btn bg-gradient-dark w-100' style='outline: none;border: none;background-color: transparent; color:#fff;'>
          </form>
        </div>
      </div>
    </div>
  ";
}

// IF User Logout.
if (isset($_POST['logout'])) {
  if (isset($_SESSION['username'])) {
    session_unset();
  } elseif (isset($_COOKIE['username'])) {
    setcookie('username', '', 0, '/');
  }
  header('Location: sign-in.php');
}
