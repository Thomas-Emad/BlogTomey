<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 Error | TomBlog</title>
  <style>
    body {
      background-color: #f8f9fa !important;
    }

    .error {
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    a {
      text-decoration: none;
      padding: 10px 15px;
      display: block;
      background-color: #fff;
      border-radius: 10px;
      color: #000;
      margin: 0 auto;
      width: fit-content;
      box-shadow: 0px 0px 5px 0px #999;
    }
  </style>
</head>

<body>
  <div class="error">
    <img src="images/design_site/404.png" alt="404 Page" width="400px">

    <?php
    if (!isset($_GET['num_site'])) {
      echo '<a href="index.php">Go Home</a>';
    } else {
      $num_site = $_GET['num_site'];
      if ($num_site == 0) {
        echo '<a href="Dashboard/pages/dashboard.php">Go Home</a>';
      } else {
        echo '<a href="index.php">Go Home</a>';
      }
    }


    ?>

  </div>
</body>

</html>