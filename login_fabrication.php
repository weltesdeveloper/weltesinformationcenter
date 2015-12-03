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
                    <h1>PROCESS DIVISION</h1>
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
                'SELECT APP_USERNAME,APP_PASSWORD,LEV.* FROM WELTES_SEC_ADMIN.WELTES_AUTHENTICATION AUT LEFT OUTER JOIN WELTES_SEC_ADMIN.WELTES_AUTH_LEVEL LEV ON LEV.APP_USR_CODE=AUT.APP_USR_CODE 
                 WHERE APP_USERNAME = :UN_BV AND APP_PASSWORD = :PW_BV');
    
    oci_bind_by_name($s, ":UN_BV", $_POST['username']);
    oci_bind_by_name($s, ":PW_BV", $_POST['password']);
    oci_execute($s);
 
    $r = oci_fetch_array($s, OCI_ASSOC);
    if ($r) {
    // The password matches: the user can use the application
    // Set the user name to be used as the client identifier in
    // future HTTP requests:
    $_SESSION['username'] = $_POST['username'];

    // HAK AKSES
    $FAB_ACCS       = $r['FAB_ACCS'];
    $PAINT_ACCS     = $r['PAINT_ACCS'];
    $PACK_ACCS      = $r['PACK_ACCS'];
    $DELIV_ACCS     = $r['DELIV_ACCS'];
    $REPORT_ACCS    = $r['REPORT_ACCS'];
    $OBSERVER_ACCS  = $r['OBSERVER_ACCS'];


    echo <<<EOD
    <!DOCTYPE html>
    <html lang="en" class="no-js">
        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
            <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
            <title>PT. WELTES ENERGI NUSANTARA | Process Division</title>
            <meta name="description" content="Process Division WEN" />
            <meta name="keywords" content="Weltes, energi, nusantara, process, division" />
            <meta name="author" content="Chris Immanuel" />
            <link rel="shortcut icon" href="images/wenico.png"> 
            <link rel="stylesheet" type="text/css" href="css/default.css" />
            <link rel="stylesheet" type="text/css" href="css/component.css" />

            <script src="jQuery/jquery-1.11.1.min.js"></script>
            <script src="js/modernizr.custom.js"></script>
            <!-- <script src="js/retinaReady.js"></script> -->
	</head>
   
        <body>
         <div class="container">	
			<!-- Codrops top bar -->
			<div class="codrops-top clearfix">
				 <a class="codrops-icon codrops-icon-prev" href="index.html"><span>MAIN MENU | </span>PT. WELTES ENERGI NUSANTARA | GATEWAY V1.00 |</a>
				<!--<span class="right"><a class="codrops-icon codrops-icon-drop" href="contact_admin.php"><span>Support System &nbsp &nbsp|</span></a>HELLO Mr/Mrs. $_POST[username] &nbsp</span> -->
			</div>
			<header>
				<h1>PROCESS DIVISION<span>Menu page,</span></h1>	
			</header>
			<div class="main clearfix">
				<nav id="menu" class="nav">					
					<ul>
						<li>
							<a href="AdminLTE/adminLogin.php" target="_blank">
								<span class="icon">
									<i aria-hidden="true" class="icon-home"></i>
								</span>
								<span>MONITORING</span>
							</a>
						</li>
						<li>
							<a href="Content/Fabrication/Update/Global/update_fabrication.php" target="_blank">
								<span class="icon"> 
									<i aria-hidden="true" class="icon-services"></i>
								</span>
								<span>FABRICATION</span>
							</a>
						</li>
						<li>
							<a href="Content/Painting/Update/Global/update_painting.php" target="_blank">
								<span class="icon">
									<i aria-hidden="true" class="icon-portfolio"></i>
								</span>
								<span>PAINTING</span>
							</a>
						</li>
                                                <li>
                                                    <a href="Content/Painting/Update/Khusus_OPNAME/update_painting.php" target="_blank">
                                                        <span class="icon">
                                                            <i aria-hidden="true" class="icon-portfolio"></i>
                                                        </span>
                                                        <span>PAINT OPNAME</span>
                                                    </a>
                                                </li>
						<li>
							<a href="packingAssignment/packingAssignmentIndexNEW.php" target="_blank">
								<span class="icon">
									<i aria-hidden="true" class="icon-blog"></i>
								</span>
								<span>PACKING</span>
							</a>
						</li>
						<li>
                            <a href="deliveryAssignment/deliveryAssignment.php" target="_blank">
                                <span class="icon">
                                    <i aria-hidden="true" class="icon-team"></i>
                                </span>
                                <span>DELIVERY</span>
                            </a>
                        </li>
					</ul>
				</nav>
			</div>
		</div><!-- /container -->		
        </body>
            </html>
EOD;
 }
 else {
    // No rows matched so login failed
    login_form('<script>alert("LOGIN FAILED !!! \nPLEASE ENTER APPROPRIATE USER NAME AND PASSWORD")</script>');
  }
}
?>

