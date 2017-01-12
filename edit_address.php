<?php
session start(); #1
if (!isset($ SESSION['user level']) or ($ SESSION['user level'] != 1))
{
header("Location: login.php");
exit();
}
?>
<!doctype html>
<html lang=en>
<head>
<title>Edit an address or phone number</title>
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
<?php include("info-col-cards.php"); ?>
<div id="content">
  <h2>Edit an address or phone number</h2>
  <?php
  if(isset($_GET['id']) && (is_numeric($_GET['id']))){
    $id=$_GET['id'];
  }elseif (isset($_POST['id']) && (is_numeric($_POST['id']))) {
    $id=$_POST['id'];
  }else{
    echo "<p class='error'>Error Accessing!</p>";
    include('footer.php');
    exit();
  }
  //check form input
  //
  require "mysqli-connect-postal.php";
  if($_SERVER['REQUEST_METHOD']=='POST'){
    $errors=array();
    $tit=trim($_POST['title']);
    $stripped=mysqli_real_escape_string($db_con,strip_tags('$title'));
    $length=mb_strlen($stripped,'utf8');

    if($length<1) $errors[]="Enter the title";
    else $title = $stripped;

  if(empty($_POST['fname'])) $errors[]="Enter first name.";
  elseif (!preg_match("/^[A-Z][a-zA-Z ]+$/",$_POST['fname'])) $errors[]="Name not valid";
  else $fname=$_POST['fname'];

  if(!preg_match("/^[A-Z][a-zA-z ]+$/",$_POST['lname'])) $errors[]="Name not valid";
  else if(empty($_POST['lname'])) $errors[]='Please Enter name';
  else $lname = mysqli_real_escape_string($db_con,trim($_POST['lname']));

  $ad1=trim($_POST['addr1']);
  $stripped=mysqli_real_escape_string($db_con,strip_tags('$ad1'));
  $length=mb_strlen($stripped,'utf8');
  if($length<1) $errors[]="Enter address";
  else $addr1=$stripped;

  $ad2 = trim($_POST['addr2']);
// Strip HTML and apply escaping
$stripped = mysqli_real_escape_string($dbcon, strip_tags($ad2));
// Get string lengths
$strlen = mb_strlen($stripped, 'utf8');
// Check stripped string
if( $strlen < 1 ) {
    $addr2=NULL;
}else{
$addr2 = $stripped;
}
// Trim the city
$ct = trim($_POST['city']);
// Strip HTML and apply escaping
$stripped = mysqli_real_escape_string($dbcon, strip_tags($ct));
// Get string lengths
$strlen = mb_strlen($stripped, 'utf8');
// Check stripped string
if( $strlen < 1 ) {
    $errors[] = 'You forgot to enter your city.';
}else{
$city = $stripped;
}
// Trim the county
$conty = trim($_POST['country']);
// Strip HTML and apply escaping
$stripped = mysqli_real_escape_string($dbcon, strip_tags($conty));
// Get string lengths
$strlen = mb_strlen($stripped, 'utf8');
// Check stripped string
if( $strlen < 1 ) {
    $errors[] = 'You forgot to enter your county.';
}else{
$country = $stripped;
}

if(empty($_POST['pcode'])){
  $errors[]='You forgot to enter your post code.';
}else if(!checkPostCode($_POST['pcode'])){
  $errors[]='Invalid postal code.';
}
else{
  $pcode=mysqli_real_escape_string($db_con,trim($_POST['pcode']));
}

if(empty($_POST['phone'])){
  $phone=NULL;
}else{
  $phone=preg_replace("/\D+/","",$_POST['phone']);
}
 if(empty($errors)){
   $query="UPDATE users set tile='$title',fname='$fname', lname='$lname', addr1='$addr1',addr2='$addr2',
   city='$city',country='$country',pcode='$pcode',phone='$phone' where user_id=$id limit 1";

   $result=mysqli_query($db_con,$query);

   if(mysqli_affected_rows($result)==1) echo "<h3>Data has been updated</h3>";
   else {
     echo "<p class='error'>Failure updating data.</p>";
     //debugging msg
     echo '<p>'. mysqli_error($db_con).'<br>Query: '.$query.'</p>';
   }

 }else { // Display the errors
echo '<p class="error">The following error(s) occurred:<br>';
foreach ($errors as $msg) { // Echo each error.
echo " - $msg<br />\n";
}
}

}

//check postcode validity
//
   function checkPostcode (&$toCheck) {

  // Permitted letters depend upon their position in the postcode.
  $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
  $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
  $alpha3 = "[abcdefghjkpmnrstuvwxy]";                            // Character 3
  $alpha4 = "[abehmnprvwxy]";                                     // Character 4
  $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
  $BFPOa5 = "[abdefghjlnpqrst]{1}";                               // BFPO character 5
  $BFPOa6 = "[abdefghjlnpqrstuwzyz]{1}";                          // BFPO character 6

  // Expression for BF1 type postcodes
  $pcexp[0] =  '/^(bf1)([[:space:]]{0,})([0-9]{1}' . $BFPOa5 . $BFPOa6 .')$/';

  // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
  $pcexp[1] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

  // Expression for postcodes: ANA NAA
  $pcexp[2] =  '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

  // Expression for postcodes: AANA NAA
  $pcexp[3] =  '/^('.$alpha1.'{1}'.$alpha2.'{1}[0-9]{1}'.$alpha4.')([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

  // Exception for the special postcode GIR 0AA
  $pcexp[4] =  '/^(gir)([[:space:]]{0,})(0aa)$/';

  // Standard BFPO numbers
  $pcexp[5] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';

  // c/o BFPO numbers
  $pcexp[6] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';

  // Overseas Territories
  $pcexp[7] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';

  // Anquilla
  $pcexp[8] = '/^ai-2640$/';

  // Load up the string to check, converting into lowercase
  $postcode = strtolower($toCheck);

  // Assume we are not going to find a valid postcode
  $valid = false;

  // Check the string against the six types of postcodes
  foreach ($pcexp as $regexp) {

    if (preg_match($regexp,$postcode, $matches)) {

      // Load new postcode back into the form element
		  $postcode = strtoupper ($matches[1] . ' ' . $matches [3]);

      // Take account of the special BFPO c/o format
      $postcode = preg_replace ('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

      // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
      if (preg_match($pcexp[7],strtolower($toCheck), $matches)) $postcode = 'AI-2640';

      // Remember that we have found that the code is valid and break from loop
      $valid = true;
      break;
    }
  }


  // Return with the reformatted valid postcode in uppercase if the postcode was
  // valid
  if ($valid){
	  $toCheck = $postcode;
		return true;
	}
	else return false;
}
$query="SELECT title,fname,lname,addr1,addr2,city,country,pcode,phone from users
        where user_id=$id";
  $result=mysqli_query($db_con,$query);
  if(mysqli_num_rows($result)==1){
    $row=mysqli_fetch_array($result,MYSQLI_NUM);

    echo '<form action="edit_address.php" method="post">
<p><label class="label" for="title">Title:</label><input class="fl-left" id="title"
type="text" name="title" size="30" maxlength="30" value="' . $row[0] . '"></p>
<p><label class="label" for="fname">First Name:</label><input class="fl-left" id="fname"
type="text" name="fname" size="30" maxlength="30" value="' . $row[1] . '"></p>
<p><label class="label" for="lname">Last Name:</label><input class="fl-left" id="lname"
type="text" name="lname" size="30" maxlength="40" value="' . $row[2] . '"></p>
<p><label class="label" for="addr1">Address:</label><input class="fl-left" id="addr1"
type="text" name="addr1" size="30" maxlength="50" value="' . $row[3] . '"></p>
<p><label class="label" for="addr2">Address:</label><input class="fl-left"
id="addr2"type="text" name="addr2" size="30" maxlength="50" value="' . $row[4] . '"></p>
<p><label class="label" for="city">City:</label><input class="fl-left" id="city"
type="text" name="city" size="30" maxlength="30" value="' . $row[5] . '"></p>
<p><label class="label" for="county">County:</label><input class="fl-left"
id="county"type="text" name="country" size="30" maxlength="30" value="' . $row[6] . '"></p>
<p><label class="label" for="pcode">Post Code:</label><input class="fl-left"
id="pcode"type="text" name="pcode" size="15" maxlength="15" value="' . $row[7] . '"></p>
<p><label class="label" for="phone">Phone:</label><input class="fl-left" id="phone"
type="text" name="phone" size="15" maxlength="15" value="' . $row[8] . '"></p>
<br><br><p><input id="submit" type="submit" name="submit" value="Edit"></p><br>
<input type="hidden" name="id" value="'.$id.'">
</form>';
}else{
  echo '<p class="error">Page has been accessed in error</p>';
}

mysqli_close($dbcon);
include ('includes/footer.php');
  ?>
</div>
</div>
</body>
</html>
