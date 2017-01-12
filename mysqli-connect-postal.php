<?php
$db_user='cabbage';
$db_pw='in4aPin4aL';
$db_host='localhost';
$db_name='finalpost';

$db_con=mysqli_connect($db_host,$db_user,$db_pw,$db_name)
or die ("Could not connect. ".mysqli_connect_error());
mysqli_set_charset($db_con,'utf8');
 ?>
