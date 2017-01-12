<?php
session_start();
if(!isset($_SESSION['user_level']) or ($_SESSION['user_level']!=1)){
  header("Location:login.php");
  exit();
}
?>
<html lang=en>
<head>
<title>Search page</title>
<meta charset=utf-8>
<link rel="stylesheet" type="text/css" href="includes.css">
<style type="text/css">
</style>
</head>
<body>
  <div id="container">
    <?php include("header-admin.php");?>
    <?php include("nav.php"); ?>
    <?php include("info-col.php"); ?>
	   <div id="content">
       <h2>Search for a record</h2>
       <h3 style='color:red;''>Both field are required</h3>
       <form action="view-found-record.php" method="post">
         <p><label class='label' for='fname'>First Name</label>
           <input type="text" name="fname" id="fname" size="30" maxlength="30" value="<?php if(isset($_POST['fname'])) echo $_POST['fname'];?>">
         </p>
         <p><label class="label" for="lname">Last Name:</label>
           <input id="lname" type="text" name="lname" size="30" maxlength="40" value="<?php if (isset($_POST['lname'])) echo $_POST['lname']; ?>"></p>
	<p><input id="submit" type="submit" name="submit" value="Search"></p>
</form>
<?php include ('footer.php'); ?>
<!-- End of the search page content. -->
</div>
</div>
</body>
</html>
