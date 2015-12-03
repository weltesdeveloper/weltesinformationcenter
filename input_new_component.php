<?php
require_once './dbinfo.inc.php';
session_start();

// CHECK IF THE USER IS LOGGED ON ACCORDING
// TO THE APPLICATION AUTHENTICATION

if (!isset($_SESSION['username'])) {
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
        <meta name="description" content="Input New Profile Page">
        <meta name="author" content="Chris Hutagalung">

        <LINK href="./css/subcont.css" rel="stylesheet" type="text/css">
        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body>
        <script src="js/scripts.js"></script>

        <form class = "basic-grey" action = <?php echo $_SERVER['PHP_SELF']; ?> method="post">
            <label><span>PROJECT NAME</span>
                <input type="text" name="projectname" size="10" maxlength="20" value="" /></label>
            <label><span>PROJECT NUMBER</span>
                <input type="text" name="projectnumber" size="10" maxlength="20" value="" /></label>
            <label><span>BASEPLATE</span>
                <input type="text" name="baseplate" size="10" maxlength="20" value="" /></label>
            <label><span>THICKNESS</span>
                <input type="text" name="thickness" size="10" maxlength="20" value="" /></label>
            <label><span>COMP NAME</span>
                <input type="text" name="name" size="10" maxlength="20" value="" /></label> 
            <label><span>PROFILE TYPE</span>
                <input type="text" name="profiletype" size="15" maxlength="20" value="" /></label>
            <label><span>REMARKS</span>
                <input type="text" name="remarks" size="10" maxlength="30" value="" /></label>
            <label><span>QTY REQUIRED</span>
                <input type="number" name="qtyrequired" size="10" maxlength="15" value="" /></label> 
            &nbsp
            &nbsp

            <label><span>&nbsp;</span><input class ="resetbutton" type="reset" name="submit" value="RESET" />
                <span>&nbsp;</span><input class ="button" type="submit" name="submit" value="SUBMIT" /></label>
        </form>

        <?php
        //If the submit button has been pressed...
        if (isset($_POST['submit'])) {
            $s = oci_parse($conn, "INSERT INTO COMPONENT_CUTTING
             (PROJECT_NAME, PROJECT_NO, BASE_PLATE, THICKNESS, NAME, QTY_REQUIRED, QTY_CNCED, REMARKS, PROFILE_TYPE, REQUEST_STATUS, CNC, SCATOR, MANUAL)
             VALUES (:projName, :projNum, :basePlate, :thick, :nm, :qtyReq, 0, :rem, :profile, 'OPEN', 0,0,0)");

            oci_bind_by_name($s, ":projName", $_POST['projectname']);
            oci_bind_by_name($s, ":projNum", $_POST['projectnumber']);
            oci_bind_by_name($s, ":basePlate", $_POST['baseplate']);
            oci_bind_by_name($s, ":thick", $_POST['thickness']);
            oci_bind_by_name($s, ":nm", $_POST['name']);
            oci_bind_by_name($s, ":qtyReq", $_POST['qtyrequired']);
            oci_bind_by_name($s, ":rem", $_POST['remarks']);
            oci_bind_by_name($s, ":profile", $_POST['profiletype']);

            $result = oci_execute($s, OCI_DEFAULT);

            if ($result) {
                oci_commit($conn); // COMMIT TRANSACTION
                echo '<script>alert("INSERT TO COMPONENT DB COMPLETED");</script>';
            } else {
                oci_rollback($conn); // ROLLBACK INSERTION
                $m = oci_error($s);
                echo "Error Save [" . $m['message'] . "]";
            }
            oci_close($conn);
        }
        ?>
    </body>
</html>