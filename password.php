<html lang="en">
<head>
<title>Change password</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
p.error{font-size: 20px; font-family: times new roman; color: red; font-weight: bold; text-align: center;}
h3{text-align: center;}
</style>
</head>
<body>
  <div id="container">
<?php include "pw_header.php"; ?>
<?php include "nav.php"; ?>
<?php include "info-col.php"; ?>
	<div id="content">
    <?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
      require "loginSql_connect.php";
      $errors=array();
      if(empty($_POST['email'])){
        $errors[]='Please enter email address';
      }else{
        $email=mysqli_real_escape_string($db_con, trim($_POST['email']));
      }
      if(empty($_POST['cpsword'])){
        $errors[]="Please enter current password";
      }else{
        $currentPw=mysqli_real_escape_string($db_con,$_POST['cpsword']);
      }
      if(!empty($_POST['psword1'])){
         if(!preg_match("/^.*(?=.{8,12})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $_POST["psword1"])) $errors[]="Your password is not valid,try again";
         if ($_POST['psword1'] != $_POST['psword2']) $errors[]="Password not match";
         $pw=mysqli_real_escape_string($db_con,$_POST['psword1']);
      }else{
        $errors[]="Please enter password";
      }

    if(empty($errors)){
      $query="SELECT user_id FROM users WHERE (email='$email' AND psword=SHA1('$currentPw'))";
      $result=@mysqli_query($db_con,$query);
      $num=@mysqli_num_rows($result);
      if($num==1){
        $row=mysqli_fetch_array($result,MYSQLI_NUM);
        $query="UPDATE users SET psword=SHA1('$pw') WHERE user_id=$row[0]";
        $result=@mysqli_query($dbcon,$query);
        if(mysqli_affected_rows($db_con)==1){
          echo '<h2>Thank you!</h2>
          <h3>Password has been updated</h3>';
        }else{
          echo '<h2>System Error</h2><p class="error">Password could not be changed</p>';
          echo '<p>'.mysqli_error($db_con).'<br><br>Query: ' .$query.'</p>';
        }
        mysqli_close($db_con);
        include('footer.php');
        exit();
      }else { // Invalid email address/password combination.
			echo '<h2>Error!</h2>
			<p class="error">The email address and password do not match those on file.</p>';
		}
  }else{
    echo "<h2>Error!!</h2><p class='error'>Following errors occured: <br/>";
    foreach ($errors as $emsg){
      echo "-$emsg<br>";
    }
    echo '</p>';
  }
  mysqli_close($db_con);
}


     ?>
  <h2>Change Password</h2>
  <form action="password.php" method="post">
    <p><label class="label" for="email">Email Address:</label>
      <input id="email" type="text" name="email" size='30' maxlength="60" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
    </p>
    <p><label class="label" for="cpsword">Current Password:</label>
      <input id="cpsword" name="cpsword" type="password" size="12" maxlength="12" value="<?php if(isset($POST['cpsword'])) echo $_POST['cpsword'];?>">
    </p>
    <p><label class="label" for="psword1">New Password:</label>
      <input id="psword1" type="password" name="psword1" size="12" maxlength="12" value="<?php if (isset($_POST['psword'])) echo $_POST['psword']; ?>">
    </p>
    <p><label class="label" for="psword2">Confirm New Password:</label>
      <input id="psword2" type="password" name="psword2" size="12" maxlength="12" value="<?php if(isset($_POST['psword2'])) echo $_POST['psword2']; ?>">
    </p>
    <p><input type="submit" name="submit" id="submit" value="Confirm"></p>
  </form>
  <?php include ('footer.php'); ?></p>
	</div><!-- End of the page-specific content. -->
	</div>
</body>
</html>
