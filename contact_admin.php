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

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <title> Contact WELTES Admin </title>
    
    <link type="text/css" rel="stylesheet"   href="css/goldenform/golden-forms.css"/>
    <link type="text/css" rel="stylesheet"   href="css/goldenform/font-awesome.min.css"/>
    
    <!--[if lte IE 9]>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="js/jquery.placeholder.min.js"></script>
    <![endif] -->
            
	<!--[if IE 9]>
		<link type="text/css" rel="stylesheet" href="css/golden-forms-ie9.css">
	<![endif]-->
    
	<!--[if lte IE 8]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>       
		<link type="text/css" rel="stylesheet" href="css/golden-forms-ie8.css">    
	<![endif]-->    
    
</head>

<body class="bg-linen">

    <div class="gforms">
    
        <div class="golden-forms wrapper">
            <form>
                <div class="form-title">
                    <h2>Contact Admin Form</h2>
                </div><!-- end .form-title section -->
                <div class="form-enclose">
                    <div class="form-section">
                       <section>
                       		<label for="names" class="lbl-text">Your Names:</label>
                            <label class="lbl-ui">
                            	<input type="text" name="names" id="names" class="input" placeholder="Enter Name" autofocus />
                            </label>                              
                       </section>
                       <section>
                       		<label for="email" class="lbl-text">Email Address:</label>
                            <label class="lbl-ui">
                            	<input type="email" name="email" id="email" class="input" placeholder="Email Address"/>
                            </label>                           
                       </section>  

                       <section>
                       		<label for="tele" class="lbl-text">Telephone / Mobile:</label>
                            <label class="lbl-ui">
                            	<input type="tel" name="tele" id="tele" class="input" placeholder="+44 772"/>
                            </label>                            
                       </section>
                                 
                       <section>
                       		<label for="msg" class="lbl-text">Complaints:</label>
                            <label class="lbl-ui">
                            	<textarea id="msg" name="msg" class="textarea no-resize" ></textarea>
                            </label>                          
                       </section>
                   </div><!-- end .form-section section -->
                </div><!-- end .form-enclose section -->
                <div class="form-buttons">
                    <section> 
                       <input type="submit" value="Submit Complaint" class="button blue" name="submit"/>
                       <input type="reset" value="Reset Form" class="button" />  
                    </section>                
                </div><!-- end .form-buttons section -->
            </form>
            <?php
            
            if (isset($_POST['submit'])){
                date_default_timezone_set('Asia/Jakarta');
                
                $res = oci_parse($conn, "INSERT INTO COMPLAINTS (SUBMITTER, EMAIL, TELEPHONE, COMPLAINT_DETAILS, SUBMISSION_DATE)
                    VALUES (:sb, :em, :tel, :det, SYSDATE)");
                
                oci_bind_by_name($res, ":sb", $_POST['names']);
                oci_bind_by_name($res, ":em", $_POST['email']);
                oci_bind_by_name($res, ":tel", $_POST['tele']);
                oci_bind_by_name($res, ":det", $_POST['msg']);
                
                $res_final = oci_execute($res, OCI_DEFAULT);
                
                 if ($res_final){
                    oci_commit($conn); // COMMIT TRANSACTION
                     echo "<script>document.alert('Your Complaint has been submitted to the Database Admin')</script>";
                     } else {
                oci_rollback($conn); // ROLLBACK INSERTION
                //$m = oci_error($res_final);
               echo "<script>alert('ERROR HAS OCCURED !!!')</script>";
                oci_close($conn);}
            }
            ?>
        </div><!-- end .golden-forms section --> 
    </div><!-- end .gforms section -->
    <div></div><!-- end section -->
    <div></div><!-- end section -->
</body>
</html>