<!doctype html>
<html>
<head>
  <title>Register Page</title>
  <meat charset=utf-8>
    <link rel="stylesheet" type="text/css" href="includes.css">
    <style type="text/css">
    p.error{color:red; font-size: 105%; font-weight: bold; text-align: center;}
    #midcol { width:98%; margin:auto; }
  input, select { margin-bottom:5px; }
  h2 { margin-bottom:0; margin-top:5px; }
  h3.content { margin-top:0; }
  .cntr { text-align:center; }
    </style>
</head>
<body>
  <div id="container">
    <?php include("register_header.php"); ?>
    <?php include("nav.php"); ?>
    <?php include("info-col-cards.php");?>
<div id="content">
  <p>
    <?php
  require 'mysqli-connect-postal.php';
  if($_SERVER['REQUEST_METHOD']=='POST'){
    $errors=array();
    //check the form fields
    if(empty($_POST['title'])) $errors[]='Please enter tite';
      else $title=mysqli_real_escape_string($db_con,trim($_POST['title']));

    if(!preg_match("/^[A-Z][a-zA-Z ]+$/",$_POST['fname']) || empty($_POST['fname']))
		$errors[]="You forgot first name";
      else  $fname=mysqli_real_escape_string($db_con,trim($_POST['fname']));

    if(!preg_match("/^[A-Z][a-zA-Z ]+$/",$_POST['lname']) || empty($_POST['lname']))
		$errors[]="You forgot last name";
    else $lname=mysqli_real_escape_string($db_con,trim($_POST['lname']));

    if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) || empty($_POST['email']))
		$errors[]="You forgot email";
    else $email=mysqli_real_escape_string($db_con,trim($_POST['email']));

    if(!empty($_POST['psword1'])){
      if(!preg_match("/^.*(?=.{8,12})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $_POST["psword1"])) $errors[]="Your password is not valid,try again";
      else if ($_POST['psword1'] != $_POST['psword2']) $errors[] = 'Your two password did not match.';
		  else $psword = mysqli_real_escape_string($db_con,trim($_POST['psword1']));
  }else {
    $errors[]="You forgot to enter password";
  }
  if(empty($_POST['uname'])){
    $errors[]='You forgot to enter your membership class';
  }else{
    $uname=trim($_POST['uname']);
  }
  if(empty($_POST['class'])){
    $errors[]='You forgot to enter your membership class';
  }else{
    $class=trim($_POST['class']);
  }
  if(empty($_POST['addr1'])){
    $errors[]='You forgot to enter your address.';
  }else{
    $addr1=mysqli_real_escape_string($db_con,trim($_POST['addr1']));
  }
  if(empty($_POST['addr2'])){
    $addr2=NULL;
  }else{
    $addr2=mysqli_real_escape_string($db_con,trim($_POST['addr2']));
  }
  if(empty($_POST['city'])){
    $errors[]='You forgot to enter your city.';
  }else{
    $city=mysqli_real_escape_string($db_con,trim($_POST['city']));
  }
  if(empty($_POST['country'])){
    $errors[]='You forgot to enter your country.';
  }else{
    $country=mysqli_real_escape_string($db_con,trim($_POST['country']));
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
  }else if(!empty($_POST['phone'])){
    $ph=preg_replace('/\D+/','',($_POST['phone']));
    //$phone=mysqli_real_escape_string($db_con,trim($_POST['phone']));
    $phone=$ph;
  }

//if no errors
  if(empty($errors)){
    //require 'mysql_connect.php';
    $query="SELECT user_id from users where email='$email'";
    $result=mysqli_query($db_con,$query);
    if(mysqli_num_rows($result)==0){
      //email not the same with any registered email, so query info into table
      $query="INSERT INTO users (user_id,title,fname,lname,email,psword,registration_date,uname,
      class,addr1,addr2,city,country,pcode,phone,paid) values('','$title',
      '$fname','$lname','$email',sha1 ('$psword'),NOW(),'$uname', '$class',
      '$addr1', '$addr2', '$city', '$country', '$pcode', '$phone','$pd')";
		$result = @mysqli_query ($dbcon, $query); // Run the query
    if($result){
      header("location:register-thanks.php");
      exit();
    }else{
        echo '<h2>System Error</h2><p class="error">Could not register. We apologize for any inconvenience.</p>';
        echo'<p>'. mysqli_error($db_con) .'<br><br>Query: '.$query.'</p>';
       }
       mysqli_close($db_con);
       include('footer.php');
       exit();
     }else{
      echo	'<p class="error">The email address is not acceptable because it is already registered</p>';
    }

  }else{
    echo "<h2>Wild Error appears in the forest!</h2>
    <p class='error'>Errors are as following:<br>";
    foreach ($errors as $emsg) {
      echo"=> $emsg<br>";
    }
    echo '</p>';
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
?>


<div id="midcol">
<h2>Membership Registration</h2>
<h3 class="content">Items marked with an asterisk* are essential</h3>
<p class='cntr'><b>Membership classes:</b> Standard 1 year:30, Standard 5years:125,
  Armed Forces 1 year:5,
  <br>Under 21 one year:2,&nbsp; other:if you can't afford 30
  please give what you can(minimum 15)</p>

  <form action="register-page.php" method="post">
    <p><label class="label" for="title">Title*</label>
    <input id="title" type="text" name="title" size="15" maxlength="12"
    value="<?php if(isset($_POST['title'])) echo $_POST['title']; ?>">
  </p>

  <p><label class="label" for="fname">First Name:</label>
    <input id="fname" name="fname" type="text" size="30" maxlength="30" value="<?php if(isset($_POST['fname']))echo $_POST['fname']; ?>">
  </p>

  <p><label class="label" for="lname">Last Name:</label>
    <input id="lname" name="lname" type="text" size="30" maxlength="40" value="<?php if(isset($_POST['lname'])) echo $_POST['lname']; ?>">
  </p>

  <p><label class="label" for="email">Email:</label>
    <input id="email" name="email" type="text" size="30" maxlength="60" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
  </p>

  <p><label class="label" for="psword1">Password:</label>
    <input id="psword1" type="password" name="psword1" size="12" maxlength="12" value="<?php if(isset($_POST['psword1'])) echo $_POST['psword1']; ?>">&nbsp;Between 8 and 12
  </p>

  <p><label class="label" for="psword2">Confirm Password:</label>
    <input id="psword2" type="password" name="psword2" size="12" maxlength="12" value="<?php if (isset($_POST['psword2'])) echo $_POST['psword2']; ?>" >
  </p>
  <p><label class="label" for="uname">Secret User Name*</label>
  <input id="uname" type="text" name="uname" size="12" maxlength="12" value="<?php if (isset($_POST['uname'])) echo $_POST['uname']; ?>">&nbsp;6
  	to 12 characters</p>


  <p><label class="label" for="class">Membership Class*</label></p>
    <select name="class">
      <option value="">- Select -</option>
      <option value="30" <?php if(isset($_POST['class']) AND ($_POST['class']=='30'))
      echo 'selected="selected"'; ?>>Standard 1 year 30</option>
      <option value="125" <?php if(isset($_POST['class']) AND ($_POST['class']=='125'))
      echo 'selected="selected"';?>> Standard 5 years 125</option>
      <option value="5"<?php if(isset($_POST['class']) AND ($_POST['class']=='5'))
      echo 'selected="selected"';?>>Armed forces 1 year 5</option>
      <option value="2"<?php if (isset($_POST['class']) AND ($_POST['class'] == '2'))
      echo ' selected="selected"'; ?>>Under 22 1 year 2**</option>
      <option value="15"<?php if(isset($_POST['class']) AND ($_POST['class']=='15'))
      echo 'selected="selected"';?>>Minimum 1 year 15</option>
    </select>


    <p><label class="label" for="addr1">Address*</label>
    <input id="addr1" type="text" name="addr1" size="30" maxlength="30"
    value="<?php if(isset($_POST['addr1'])) echo $_POST['addr1'];?>">
  </p>

    <p><label class="label" for="addr2">Address</label>
    <input id="addr2" type="text" name="addr2" size="30" maxlength="30"
    value="<?php if(isset($_POST['addr2'])) echo $_POST['addrr2'];?>">
  </p>

    <p><label class="label" for="city">City*</label>
      <input id="city" type="text" name="city" size="30" maxlength="30"
      value="<?php if(isset($_POST['city'])) echo $_POST['city'];?>">
    </p>

    <p><label class="label" for="country">Country*</label>
      <input id="country" type="text" name="country" size="30" maxlength="30"
      value="<?php if(isset($_POST['country'])) echo $_POST['country'];?>">
    </p>

    <p><label class="label" for="pcode">Post code*</label>
      <input id="pcode" type="text" name="pcode" size="15" maxlength="15"
      value="<?php if (isset($_POST['pcode'])) echo $_POST['pcode']; ?>">

    <p><label class="label" for="phone">Telephone</label>
    <input id="phone" type="text" name="phone" size="30" maxlength="30"
    value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>">
  </p>

    <p><input id="submit" type="submit" name="submit" value="register">
    </p>


</form>
<?php include('footer.php'); ?>
</p>
</div>
</div>
</body>
</html>
