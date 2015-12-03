<?php

require_once('./dbinfo.inc.php');
session_start();

function login_form($message)
{
 echo <<<EOD
    <!DOCTYPE html>
    <!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
    <!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
    <!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA</title>
        <LINK href="css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body> 
        <div class="container">
            <section id="content">
                <form action="" method="post">		
                    <h1>COMPONENT</h1>
                    <div>
                        <input name="username" id="username" type="text" class="input username" placeholder="Username" />
                    </div>
                    <div>
                        <input name="password" id="password" type="password" class="input password" placeholder="Password" />
                    </div>
                    <div>
                        <input type="submit" value="LOGIN"/>
                    </div>
                </form>
                <div class="button">
			<a href="index.html">Main Menu</a>
		</div><!-- button -->
            </section>
        </div>
    </body>
    </html>

EOD;
}
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    login_form('');
} else {
    
    // Check validity of the supplied username & password
    $conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
    // Use a "bootstrap" identifier for this administration page
    oci_set_client_identifier($conn, 'admin');
        $s = oci_parse($conn, 
                'select app_username from weltes_sec_admin.weltes_authentication 
                 where app_username = :un_bv and app_password = :pw_bv');
    
    oci_bind_by_name($s, ":un_bv", $_POST['username']);
    oci_bind_by_name($s, ":pw_bv", $_POST['password']);
    oci_execute($s);
 
    $r = oci_fetch_array($s, OCI_ASSOC);
    if ($r) {
    // The password matches: the user can use the application
    // Set the user name to be used as the client identifier in
    // future HTTP requests:
    $_SESSION['username'] = $_POST['username'];
    echo <<<EOD
    <a href="SmartAdmin/index.php">
EOD;
 }
 else {
    // No rows matched so login failed
    login_form('<script>alert("LOGIN FAILED !!! \nPLEASE ENTER APPROPRIATE USER NAME AND PASSWORD")</script>');
  }
}
?>

