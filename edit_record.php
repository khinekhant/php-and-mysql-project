<?php
session_start();
if(!isset($_SESSION['user_level']) or $_SESSION['user_level'] != 1){
  header('Location: login.php');
  exit();
}
 ?>
 <!doctype html>
<html lang=en>
<head>
<title>Edit a record</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
p { text-align:center; }
input.fl-left { float:left; }
#submit { float:left; }
</style>
</head>
<body>
  <div id="container">
    <?php include("header-admin.php"); ?>
    <?php include("nav.php"); ?>
    <?php include("info-col.php"); ?>
     <div id="content"><!-- Start of the page-specific content. -->
       <h2>Edit a Record</h2>
       <?php
       if((isset($_GET['id'])) && (is_numeric($_GET['id']))){
         $id=$_GET['id'];
       } else if((isset($_POST['id'])) && (is_numeric($_POST['id']))){
         $id=$_POST['id'];
       }else{
         echo '<p class="error">This page has been accessed in error.</p>';
         include('footer.php');
         exit();
       }
       require "mysqli-connect-postal.php";
       if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $errors=array();
         if(!preg_match("/^[A-Z][a-zA-z ]+$/",$_POST['fname'])) $errors[]="Name not valid";
         else if(empty($_POST['fname'])) $errors[]='Please Enter name';
         else $fname = mysqli_real_escape_string($db_con,trim($_POST['fname']));

         if(!preg_match("/^[A-Z][a-zA-z ]+$/",$_POST['lname'])) $errors[]="Name not valid";
         else if(empty($_POST['lname'])) $errors[]='Please Enter name';
         else $lname = mysqli_real_escape_string($db_con,trim($_POST['lname']));

         if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) $errors[]="Email not valid";
         else if(empty($_POST['email'])) $errors[]="You forgot email";
         else $email=mysqli_real_escape_string($db_con,trim($_POST['email']));

         if(empty($_POST['class']){
           $errors[]='Please select one of the member class';
         }else{
           $class=mysqli_real_escape_string($db_con,trim($_POST['class']));
         }
         if(empty($_POST['paid'])){
           $paid=NULL;
         }else{
           $paid=mysqli_real_escape_string($db_con,trim($_POST['paid']));
         }

       if(empty($errors)){
         //check whether the same email for different id already exist
         $query="SELECT user_id from users where email='$email'and user_id != $id";
         $result=@mysqli_query($db_con,$query);
         if(mysqli_num_rows($result)==0){
           //update the record
           $query="UPDATE users SET fname='$fname',lname='$lname',email='$email',class='$class',paid='$paid'
           where user_id=$id limit 1";
           $result =@mysqli_query($db_con,$query);
           if(mysqli_affected_rows($db_con)==1){
             echo '<h3>The user has been edited.</h3>';
           }else {
             echo '<p class="error">User could not be edited</p>';
             echo '<p>'. mysqli_error($db_con).'<br/>Query: '.$query.'</p>';
           }
         }else {
           //email already existed
           echo '<p class="error">An account already existed with that email</p>';
         }
         }else{
           echo '<p class="error">The following error(s) occured:<br>';
           foreach ($errors as $emsg) {
             # code...
             echo '-$emsg<br>';
           }
       }
     }

       $query="SELECT fname,lname,email,class,paid from users where user_id=$id";
       $result=mysqli_query($db_con,$query);
       if(mysqli_num_rows($result)==1){
         $row=mysqli_fetch_array($result,MYSQLI_NUM);
         echo '<form action="edit_record.php" method="post">
         <p><label class="label" for="fname">First Name</label>
         <input name="fname" class="fl-left" type="text" id="fname" size="30" maxlength="30" value="'.$row[0].'"></p>
         <p><label class="label" for="lname">Last Name</label>
         <input name="lname" class="fl-left" type="text" id="lname" size="30" maxlength="40" value="'.$row[1].'"></p>
         <p><label class="label" for="email">Email Address:</label>
         <input class="fl-left" type="text" name="email" size="30" maxlength="50" value="' . $row[2] . '"></p>
         <p><label class="label" for="class">Membership Class :</label>
         <input class="fl-left" type="text" name="class" size="30" maxlength="50" value=" '.$row[3].'"></p>
         <p><label class="label" for="paid">Paid? :</label>
         <input class="fl-left" type="text" name="paid" size="30" maxlength="50" value=" '.$row[4].'"></p>
         <br><p><input id="submit" type="submit" name="submit" value="Edit"></p>
         <br><input type="hidden" name="id" value="'.$id.'"/>
         </form>';
       }else { // The user could not be validated
	echo '<p class="error">This page has been accessed in error.</p>';
}
mysqli_close($db_con);
include ('footer.php');
?>
</div>
</div>
</body>
</html>
