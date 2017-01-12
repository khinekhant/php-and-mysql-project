<?php
if(!isset($_SESSION['user_level']) or $_SESSION['user_level']!=1){
  header("Location:login.php");
  exit();
}
 ?>
<!doctype html>
<html lang=en>
<head>
<title>View found address page</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
p{text-align: center;}
table,tr{width:850px;}
</style>
</head>
<body>
  <?php
  require "mysqli-connect-postal.php";
  echo '<p>If no record is shown, this is because of an
  incorrect or missing entry inthe search form.
  <br>Click the Search button and try again</p>';
  $fname=$_POST['fname'];
  $fname=mysqli_real_escape_string($db_con,$fname);
  $lname=$_POST['lname'];
  $lname=mysqli_real_escape_string($db_con,$lname);

  $query="SELECT title,lname,fname,addr1,addr2,city,country,phone,
  user_id from users where fname='$fname' and lname='$lname'";
  $result=mysqli_query($db_con,$query);
  if($result){
    echo '<table>
    <tr><td><b>Edit<b></td>
    <td><b>Title</b></td>
    <td><b>Last Name</b></td>
<td><b>First Name</b></td>
<td><b>Addrs1</b></td>
<td><b>Addrs2</b></td>
<td><b>City</b></td>
<td><b>County</b></td>
<td><b>Pcode</b></td>
<td><b>Phone</b></td>
</tr>';
while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
  echo '<tr>
  <td><a href="edit_address.php ? id='.$row['user_id'].'">Edit</a></td>
  <td>'.$row['title'].'</td>
  <td>'.$row['lname'].'</td>
  <td>' . $row['fname'] . '</td>
<td>' . $row['addr1'] . '</td>
<td>' . $row['addr2'] . '</td>
<td>' . $row['city'] . '</td>
<td>' . $row['county'] . '</td>
<td>' . $row['pcode'] . '</td>
<td>' . $row['phone'] . '</td>
</tr>';
}
    echo '</table>';
    mysqli_free_result($result);
  }else{
    echo '<p clas="error">Data could not retrieved</p>';
    echo '<p>'.mysqli_error($db_con).'<brQuery: '.$query.'</p>';
  }
  $query="SELECT COUNT(user_id) from users";
  $result=mysqli_query($db_con,$query);
  $row=mysqli_fetch_array($result,MYSQLI_NUM);
  $members=$row[0];
  mysqli_close($db_con);
  echo "<p>Total membership: $members</p>";
  ?>
<?php include('footer.php');?>
</div>
</body>
</html>
