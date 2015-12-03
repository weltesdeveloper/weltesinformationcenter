<?php

require_once('./dbinfo.inc.php');
session_start();


function login_form($message)
{
 echo <<<EOD
    <title>PT. WELTES ENERGI NUSANTARA</title>
    <LINK href="./css/login.css" rel="stylesheet" type="text/css">
<div id="wrapper">

	<form name="login-form" class="login-form" action="" method="post">
	
		<div class="header">
		<h1>PT. Weltes Energi Nusantara</h1>
		<span>Enter username & password</span>
		</div>
	
		<div class="content">
		<input name="username" type="text" class="input username" placeholder="Username" />
		<div class="user-icon"></div>
		<input name="password" type="password" class="input password" placeholder="Password" />
		<div class="pass-icon"></div>		
		</div>

		<div class="footer">
		<input type="submit" name="submit" value="Login" class="button" />
		</div>
	</form>
</div>
<div class="gradient"></div>

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
    <body style="font-family: Arial, sans-serif;">
    <frameset rows = "100,*">
    <frame src = "header.php"/>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
     <LINK href="./css/sidemenu.css" rel="stylesheet" type="text/css">

     <div id='cssmenu'>
        <ul>
        
         <li><a href='#'><span>INPUT NEW</span></a>
             <ul>
                <li><a href='input.php'><span>Database entry</span></a></li>
                <li><a href='input_new_component.php'><span>Component/s</span></a></li>
                <li><a href='subcont_entry.php'><span>Subcontractor</span></a></li>
            </ul>
                </li>
         <li><a href='#'><span>UPDATE</span></a>
            <ul>
                <li><a href='./update_bar/update_fabrication.php'><span>Fabrication Progress</span></a></li>
                <li><a href='./update_bar/update_paint_progress.php'><span>Painting Progress</span></a></li>
                <li><a href='./update_bar/update_packing_progress.php'><span>Packing Progress</span></a></li>
                <li><a href='./update_bar/update_component_status.php'><span>Component Readiness</span></a></li>
         <li><a href='#'><span>SHOW</span></a>
            <ul>
                <li><a href='fab_progress.php'><span>Fabrication Progress</span></a></li>
                <li><a href='paint_progress.php'><span>Painting Progress</span></a></li>
                <li><a href='packing_progress.php'><span>Packing Progress</span></a></li>
                <li><a href='show_db.php'><span>Master Database</span></a></li>
                <li><a href='show_master_comp.php'><span>Component List</span></a></li>
            </ul></li>  
          <li><a href='subcont_assignment.php'><span>Assign Subcont & Due Date</span></a></li>
          
          <li class='last'><a href='component_monitoring/component_process.php'><span>Monitor Component</span></a></li>
          <li class='last'><a href='contact_admin.php'><span>Contact Admin</span></a></li>
    
        </ul>
    </div>
    </body>
    <footer>
    PT. WELTES ENERGI NUSANTARA : <script type="text/javascript">document.write(Date());</script> 
    </footer>
EOD;
 }
 else {
    // No rows matched so login failed
    login_form('<script>alert("LOGIN FAILED !!! \nPLEASE ENTER APPROPRIATE USER NAME AND PASSWORD")</script>');
  }
}
?>

