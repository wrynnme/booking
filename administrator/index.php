<?php
  require_once('./../config.php');
  @session_start();
  // CHECK LOGIN
  if (!isset($_SESSION['emp_id'])) {
    hd("/booking/logout");
  }elseif (strtolower($_SESSION['emp_position']) != "administrator") {
    hd("/booking/");
  }
  include './header.php';
?>
<?php
 ?>
<html style="background:#ccc;">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
      @font-face {
        font-family: 'Eclipse Demo';
        src: url('Eclipse Demo.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
      }
      .font_eclipse2 {
          font-family: 'Eclipse Demo';
          text-decoration: none;
          font-size: 16px;
      }
    </style>
  </head>
  <body>
  
  </body>
</html>
