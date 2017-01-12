<?php
session_start();
if (!isset($_SESSION['user_level']) or ($_SESSION['user_level'] != 1))
{
header("Location: login.php");
exit();
}
?>

<!doctype html>
<html lang=en>
<head>
<title>Delete a record</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
p{text-align: center;}
h3 {text-align: center;}

#submit-yes{margin-left: 220px;}
#submit-no{margin-left: 20px;}
</style>
</head>
<body>
<div id="container">
<?php include("header-admin.php"); ?>
<?php include("nav.php"); ?>
<?php include("info-col.php"); ?>
<div id="content"><!-- Start of content for the delete page -->
<h2>Delete a Record</h2>
<?php
//get the id of the user that admin want to Delete
if(isset($_GET['id']) && is_numeric($_GET['id'])){
  $id=$_GET['id'];
}elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
  $id=$_POST['id'];
}else{
  echo '<p class="error">This page has been accessed in error</p>';
  include 'footer.php';
  exit();
}
require "loginSql_connect.php";
if($_SERVER['REQUEST_METHOD']=='POST'){
  if($_POST['sure']=='Yes'){
    $query="DELETE from users where user_id=$id LIMIT 1";
    $result=mysqli_query($db_con,$query);
    if(mysqli_affected_rows($db_con)==1){
      echo '<h3>The record has been deleted.</h3>';
		} else { // If the query did not run OK.
			echo '<p class="error">The record could not be deleted.<br>Probably because it does not exist or due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbcon ) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		    }
  }else{
    header("Location: admin-viewusers-page.php");
    exit();
  }
}else{
$query="SELECT CONCAT(fname, ' ',lname) as name from users where user_id=$id";
$result=mysqli_query($db_con,$query);
if(mysqli_num_rows($result)==1){
  $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
  echo "<h3>Are you sure you want to permanently delete ".$row['name']."? </h3>";
  echo '<form action="delete_record.php" method="post">
  <input id="submit-yes" type="submit" name="sure" value="Yes">
	<input id="submit-no" type="submit" name="sure" value="No">

	<input type="hidden" name="id" value="' . $id . '">
	</form>';

}else{
  echo '<p class="error">This page has been accessed in error.</p>';
}
}
mysqli_close($db_con );
	//	echo '<p>&nbsp;</p>';

include ('footer.php');
?>
</div>
</div>
</body>
</html>
