<?php
session_start();
if(!isset($_SESSION['user_level']) or ($_SESSION['user_level'] !=0)){
  header("Location:login.php");
  exit();
}
 ?>
 <!doctype html>
 <html lang="en">
 <head>
   <title>Members page</title>
    <meta charset=utf-8>
     <link rel="stylesheet" type="text/css" href="includes.css">
      <style type="text/css">
      #mid-right-col { text-align:center; margin:auto;
}
      #midcol h3 { font-size:130%; margin-top:0; margin-bottom:0;
}
      </style>
  </head>
  <body>
    <div id="container">
      <?php include("header-member.php"); ?>
      <?php include("nav.php"); ?>
      <?php include("info-col.php"); ?>
      <div id="content">
        <?php
        echo "<h2>Welcome to Members' Page";
        if(isset($_SESSION['fname'])){
          echo "{$_SESSION['fname']}";
        }
        echo '</h2>';
         ?>
  <div id="midcol">
   <div id="mid-left-col">
    <h3>Member's Events</h3>
     <p>The members page content. The members page content. The members
     <br>The members page content. The members page content. The members <br>The members page content.
         The members page content. The member<br>The members page content. The members page content.
         The members </p></div>
    <div id="mid-right-col">
      <h3>Special offers to member only</h3>
      <p><b>T-shirts &pound;10.0</b></p>
      <img alt="polo shirt" title="polo shirt" height="207" width="280" src="images/polo.png"><br>
      <br>
    </div>
  </div></div>
</div>
      <?php include("footer.php"); ?>
    </body>
    </html>
