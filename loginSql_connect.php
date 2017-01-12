<?php
DEFINE('DB_USER','william');
DEFINE('DB_PW','catonlap');
DEFINE('DB_HOST','localhost');
DEFINE('DB_NAME','logindb');

$db_con=mysqli_connect(DB_HOST,DB_USER,DB_PW,DB_NAME) or die('Connection Failed'. mysqli_connect_error());
mysqli_set_charset($db_con,'utf8');
 ?>
