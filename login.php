<!doctype html>
<html lang=en>
<head>
  <title>The Login Page</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="includes.css">
</head>
<body>
  <div id="container">
    <?php include("login_header.php"); ?>
    <?php include("nav.php"); ?>
    <?php include("info-col.php"); ?>
    <div id="content">
      <?php
      if($_SERVER['REQUEST_METHOD']=='POST'){
      require 'loginSql_connect.php';
      if(!empty($_POST['email'])) $email=mysqli_real_escape_string($db_con,trim($_POST['email']));
      else {
        $email=FALSE;
        echo "<p class='error'>Please enter email</p>";
      }
      if(!empty($_POST['psword'])) $pw=mysqli_real_escape_string($db_con,trim($_POST['psword']));
      else{
        $pw=FALSE;
        echo '<p class="error">Please enter password</p>';
      }
      if($email && $pw){
        $query="SELECT user_id,fname,user_level FROM users WHERE email='$email' AND psword=SHA1('$pw') and paid='Yes'";
        $result=mysqli_query($db_con,$query);
        if(mysqli_num_rows($result)==1){
          session_start();
          $_SESSION=mysqli_fetch_array($result,MYSQLI_ASSOC);
          $_SESSION['user_level']=(int) $_SESSION['user_level'];
          $url=($_SESSION['user_level']==1)?'admin-page.php':'members-page.php';
          header('Location: '.$url);
          exit();
          mysqli_free_result($result);
          mysqli_close($db_con);
          //forgot this line
          ob_end_clean();
        }else{
          echo '<p class="error">Email and password entered do not match.<br>
          <b>Perhaps you have not paid for member fees.<b>
          If you have not registered yet, please register first.</p>';
        }
      }else{
        echo "<p class='error'>please try again</p>";
      }
      mysqli_close($db_con);
      }

      ?>
      <div id='loginfields'>
        <?php include('login_page.inc.php'); ?>
      </div><br>
      <?php include('footer.php');?>
    </div>
  </div>
</body>
</html>
