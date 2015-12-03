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
    <!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Chris Immanuel">
    
    <!-- Le styles -->
    <link href="WeltesMonitoring/assets/css/bootstrap.css" rel="stylesheet">
    <link href="WeltesMonitoring/assets/css/login.css" rel="stylesheet">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <script type="text/javascript" src="jQuery/jquery-1.11.0.js"></script>

    <style type="text/css">
      body {
        padding-top: 30px;
      }
    </style>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

  	<!-- Google Fonts call. Font Used Open Sans & Raleway -->
	<link href="http://fonts.googleapis.com/css?family=Raleway:400,300" rel="stylesheet" type="text/css">
  	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">

    </head>
  <body>

  	<!-- NAVIGATION MENU -->

    <div class="navbar-nav navbar-inverse navbar-fixed-top">
        <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="projectSelector.php"><img src="WeltesMonitoring/assets/img/smallwenlogo.png" alt=""> PT. WELTES ENERGI NUSANTARA</a>
        </div> 
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              

            </ul>
          </div><!--/.nav-collapse -->
        </div>
    </div>

    <div class="container">
        <div class="row">
   		<div class="col-lg-offset-4 col-lg-4" style="margin-top:100px">
   			<div class="block-unit" style="text-align:center; padding:10px 10px 10px 10px;">
                            <form method="post" action="monitorIndex.php">
                            <?php      
                                $projectParse = oci_parse($conn, 'SELECT DISTINCT PROJECT_NAME FROM MASTER_DRAWING '
                                        . 'ORDER BY PROJECT_NAME ASC');
                                oci_execute($projectParse);

                                echo '<select name="cd-dropdown" id="cd-dropdown" class="cd-select">';
                                    echo '<OPTION VALUE="">PROJECT SELECT</OPTION>';
                                    while($row = oci_fetch_array($projectParse,OCI_ASSOC)){
                                        $projectName = $row ['PROJECT_NAME'];
                                        echo "<OPTION VALUE='$projectName'>$projectName</OPTION>";
                                    }
                                echo '</select>';
                            ?>
                                    <input type="submit" name="submit">
                            </form>
                        </div>
                </div>
        </div>
    </div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    
  
</body></html>
EOD;
 }
 else {
    // No rows matched so login failed
    login_form('<script>alert("LOGIN FAILED !!! \nPLEASE ENTER APPROPRIATE USER NAME AND PASSWORD")</script>');
  }
}
?>

