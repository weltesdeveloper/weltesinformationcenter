<?php
   require_once './dbinfo.inc.php';
   session_start();
   
   // CHECK IF THE USER IS LOGGED ON ACCORDING
   // TO THE APPLICATION AUTHENTICATION
   if(!isset($_SESSION['username'])){
       echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.html">LOGIN PAGE</a><p>
EOD;
       exit;
   }
   // GENERATE THE APPLICATION PAGE
   $conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
   
   // 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
   // 2. USING UNIQUE VALUE FOR BACK END USER
   oci_set_client_identifier($conn, $_SESSION['username']);
   $username = htmlentities($_SESSION['username'], ENT_QUOTES);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PT. Weltes Energi Nusantara Information Center</title>
  <meta name="description" content="Input New Subcontractor">
  <meta name="author" content="Chris Hutagalung">

  <LINK href="./css/subcont.css" rel="stylesheet" type="text/css">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
  <script src="js/scripts.js"></script>
  <form class = "basic-grey" action=<?php echo $_SERVER['PHP_SELF'];?> method="post">
    
    <h1><span>Adding to the SUBCONTRACTOR Table</span></h1>
    
    <label><span>Subcontractor ID</span>
        <input id="subcontid" type="text" name="subcontid" size="10" maxlength="10" value="" /></label>
    <label><span>Name</span>
        <input id="subcontname" type="text" name="subcontname" size="20" maxlength="20" value="" /></label> 
    <label><span>Address</span>
        <input id="address" type="text" name="address" size="10" maxlength="40" value="" /></label>
    <label><span>Phone</span>
        <input id="phone" type="text" name="phone" size="10" maxlength="10" value="" /></label>
    <label><span>NPWP</span>
        <input id="npwp" type="text" name="npwp" size="10" maxlength="20" value="" /></label> 
    <label><span>Status</span>
        <input id="status" type="text" name="status" size="70" maxlength="20" value="" /></label>
    <label><span>Photo</span>
        <input id="submitphoto" type="file" name="imgfile" value="upload_photo" /></label>
     
    <label><span>&nbsp;</span><input class ="button" type="submit" name="submit" value="Submit!" /></label>
</form>
  
    <?php
   // If the submit button has been pressed...

   if (isset($_POST['submit']))
   {    
        $s = oci_parse($conn, "INSERT INTO SUBCONTRACTOR
             (SUBCONT_ID, SUBCONT_NAME, SUBCONT_ADDRESS, SUBCONT_PHONE, SUBCONT_NPWP, SUBCONT_STATUS)
              VALUES (:sid, :sname, :saddr, :sphone, :snpwp, :sstat)");
     
        oci_bind_by_name($s, ":sid", $_POST['subcontid']);
        oci_bind_by_name($s, ":sname", $_POST['subcontname']);
        oci_bind_by_name($s, ":saddr", $_POST['address']);
        oci_bind_by_name($s, ":sphone", $_POST['phone']);
        oci_bind_by_name($s, ":snpwp", $_POST['npwp']);
        oci_bind_by_name($s, ":sstat", $_POST['status']);
        
        $result = oci_execute($s, OCI_DEFAULT);

        if ($result)
            {
                oci_commit($conn); // COMMIT TRANSACTION
                echo 'INSERT TO DB COMPLETED';
            } else {
                oci_rollback($conn); // ROLLBACK INSERTION
                $m = oci_error($s);
                echo "ERROR OCCURED".$m;
            }
        oci_close($conn);
   }
?>
</body>
</html>