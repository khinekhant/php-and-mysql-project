<?php
session_start();
if(!isset($_SESSION['user_level']) or ($_SESSION['user_level']!=1))
{
  header("Location: login.php");
  exit();
}
 ?>
<!doctype html>
<html lang=en>
<head>
<title>Admin view users page for an administrator</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
p { text-align:center;
}
</style>
</head>
<body>
<div id="container">
<?php include("header-admin.php"); ?>
<?php include("nav.php"); ?>
<?php include("info-col.php"); ?>
<div id="content"><!-- Start of table display page content of  -->
<h2>Registered members displayed four per page</h2>
<p>
  <?php
  require "mysqli-connect-postal.php";
  $pagerows=4;
  if(isset($_GET['p']) && is_numeric($_GET['p'])){
    $page=$_GET['p'];
  }else{
    $query="SELECT COUNT(user_id) FROM users";
    $result=@mysqli_query($db_con, $query);
    $row=@mysqli_fetch_array($result, MYSQLI_NUM);
    $records=$row[0];

    if($records > $pagerows){
      $page=ceil($records/$pagerows);
    }else{
      $page=1;
    }
  }

  if(isset($_GET['s']) && is_numeric($_GET['s'])){
    $start=$_GET['s'];
  }else{
    $start=0;
  }

  $query="SELECT lname,fname,email,DATE_FORMAT(registration_date,'%M %d, %Y')
  AS regdate,user_id,class,paid FROM users ORDER BY registration_date DESC LIMIT $start,$pagerows";
  $result=@mysqli_query($db_con,$query);
  $members=mysqli_num_rows($result);
  if($result){
    echo '<table>
    <tr><td><b>Edit</b></td>
    <td><b>Delete</b></td>
    <td><b>Last Name</b></td>
    <td><b>First Name</b></td>
    <td><b>Email</b></td>
    <td><b> Date Registration</b></td>
    <td><b>Class<b></td>
    <td><b>Paid<b></td>
    </tr>';

    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
      echo '<tr>
      <td><a href="edit_record.php?id='.$row['user_id'].'">Edit</a></td>
      <td><a href="delete_record.php ? id='.$row['user_id'].'">Delete</a></td>
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
  }else{
    echo '<p class="error">The current users could not be retrived. Sorry for any inconvenience.</p>';
    echo '<p>'.mysqli_error($db_con).'<br><br/>Query: '.$query.'</p>';

  }
  $query="SELECT COUNT(user_id) FROM users";
  $result=@mysqli_query($db_con,$query);
  $row=mysqli_fetch_array($result,MYSQLI_NUM);
  $members=$row[0];
  mysqli_close($db_con);
  echo "<p>Total membership: $members</p>";
  if( $page > 1){
    echo '<p>';
    $current_page=($start/$pagerows)+1;

    if($current_page!=1){
    echo '<a href="admin-viewusers-page.php?s='.($start-$pagerows).'">
    Previous</a>';
    }
    if($current_page!=$page){
      echo '<a href="admin-viewusers-page.php?s='.($start+$pagerows).'">
      Next</a>';
    }
    echo '</p>';
  }
  ?>
</div>
<?php include("footer.php");?>
</div>
</body>
</html>
