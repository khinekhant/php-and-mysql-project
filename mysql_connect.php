<?php
DEFINE("DB_USER","horatio");
DEFINE("DB_NAME","simpleIdb");
DEFINE("DB_PW","hmsvictory");
DEFINE("DB_HOST","localhost");

$db_con=@mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME)
OR die('Could not connect to database'. mysqli_connect_error());

mysqli_set_charset($db_con,'utf8');

?>
