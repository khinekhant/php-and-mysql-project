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
<title>View found record page</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
p { text-align:center; }
</style>
</head>
<body>
<div id="container">
<?php include("header-admin.php"); ?>
<?php include("nav.php"); ?>
<?php include("info-col.php"); ?>
<div id="content"><!-- Start of the page-specific content. -->
<h2>Search Result</h2>
<?php
require 'loginSql_connect.php';
echo '<p>If no record is shown, this is because you had an incorrect or missing entry.</p>';
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$lname=mysqli_real_escape_string($db_con,trim($_POST['lname']));
$query="SELECT lname,fname,email,DATE_FORMAT(registration_date, '%M %d, %Y') As regdate, user_id,class,paid FROM users
where lname='$lname' and fname='$fname' order by registration_date desc";
$result=mysqli_query($db_con,$query);
if($result){
  echo '<table>
  <tr>
  <td><b>Edit</b></td>
  <td><b>Delete</b></td>
  <td><b>Last Name</b></td>
<td><b>First Name</b></td>
<td><b>Email</b></td>
<td><b>Date Registered</b></td>
<td><b>Class<b></td>
<td><b>Paid<b></td>
</tr>';

while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
  echo '<tr>
  <td><a href="edit_record.php? id='.$row['user_id'].'">Edit</a></td>
  <td><a href="delete_record.php? id='.$row['user_id'].'">Delete</a></td>
  <td>'.$row['lname'].'</td>
  <td>'.$row['fname'].'</td>
  <td>' .$row['email'] .'</td>
   <td>' .$row['regdate'] .'</td>
   <td>'.$row['class'].'</td>
   <td>'.$row['paid'].'</td>
  </tr>';
}
echo '</table>';
mysqli_free_result($result);
} else {
  echo '<p class="error">Member data could not retreived.</p>';
  echo '<p>'.mysqli_error($db_con). '<br>Query: '.$query.'</p>';
}
$query="SELECT COUNT(user_id) from users";
$result=@mysqli_query($db_con,$query);
$row=mysqli_fetch_array($result,MYSQLI_NUM);
mysqli_close($db_con);
echo "<p>Total Memberships: ".$row[0]."</p>";
?>
</div><!-- End of administration page content. -->
<?php include("footer.php"); ?>
</div>
</body>
</html>
