<?php
session_start();
if(!isset($_SESSION['user_level']) or ($_SESSION['user_level']!=1)){
  header("Location:login.php");
  exit();
}
 ?>

<!doctype html>
<html lang=en>
<head>
<title>View users page</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
</head>
<body>
<div id="container">
<?php include('header-admin.php'); ?>
<?php include('nav.php'); ?>
<?php include("info-col.php") ?>
<div id="content">
  <h2>Registered Users</h2>
  <p>
    <?php
    require 'loginSql_connect.php';
  //  $q="SELECT CONCAT(lname,',',fname) , DATE_FORMAT(registration_date,'%M %d, %Y')
  //  FROM users ORDER BY registration_date ASC";
  $q="SELECT lname,fname,email,DATE_FORMAT(registration_date,'%M,%d,%y')
  As regdate, user_id FROM users ORDER BY registration_date ASC";
    $result=@mysqli_query($db_con,$q);
    if($result){
      echo '<table>
      <tr>
      <td><b>Edit</b></td>
      <td><b>Delete</b></td>
      <td><b>First Name</b></td>
      <td><b>Last Name</b></td>
      <td><b>Email</b></td>
      <td><b>Date Registered</b></td>
      </tr>';

      while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
        echo '<tr>
        <td align="left"><a href="edit_user.php ? id='. $row['user_id'] . '">Edit</a></td>
        <td align="left"><a href="delete_user.php ? id=' .$row['user_id'] .'">Delete</a></td>
        <td align="left">'.$row['lname'].'</td>
        <td align="left">'.$row['fname'].'</td>
        <td align="left">'.$row['regdate'].'</td>
        </tr>';

      }
      echo '</table>';
      mysqli_free_result($result);
    }else{
      echo '<p class="error">The current users could not be retrived. Sorry for any inconvenience.</p>';
      echo '<p>'.mysqli_connect_error($db_con).'<br><br/>Query: '.$q.'</p>';
    }
    mysqli_close($db_con);
    ?>
  </p>
</div>
</body>
</html>
