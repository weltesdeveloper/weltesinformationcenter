<?php
require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';
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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | MD REVISION</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara Master Drawing Revision">
        <meta name="author" content="Chris Immanuel">
        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-select.css" />
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css" />
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="../jQuery/jquery-1.11.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script language="javascript" type="text/javascript"  src="../js/bootstrap-select.min.js"></script>
        <script language="javascript" type="text/javascript"  src="revisionJs/mdRevision.js"></script>
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><font size="5"><b>ADMIN TOOLS</b></font> ~ 
                    <font color="#CC0000" size="5"><b>MASTER DRAWING REVISION</b></font>
                    <font color="green" size="3">(Merubah Profile, length, Surface, Weight)</font>
                </h3>
            </div> <!-- panel heading -->
            <div class="panel-body">
                <form class="form-horizontal">

                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">PROJECT NAME</label>
                        <div class="col-sm-10">
                            <?php
                            $sql_project = "SELECT * FROM VW_PROJ_INFO WHERE PROJECT_TYP='STRUCTURE' ORDER BY PROJECT_NO,PROJECT_NAME_NEW";
                            $proj_result = oci_parse($conn, $sql_project);

                            oci_execute($proj_result);

                            echo '<select class="form-control" name="projName" id="projName" data-live-search="true">' . '<br>';
                            echo '<option value=" " selected="" disabled="">' . "" . '</OPTION>';

                            while ($row = oci_fetch_array($proj_result)) {
                                $proj = $row['PROJECT_NAME_OLD'];
                                echo "<OPTION VALUE='$proj'>" . $row['PROJECT_NO'] . " - " . $row['PROJECT_NAME_NEW'] . "</OPTION>";
                            }
                            echo '</select>';
                            ?>
                        </div>
                    </div>

                    <div class="form-group" id="revisionComp"></div>
                    <div class="form-group" id="revisionHeadmark"></div>
                    <div class="form-group" id="reviseableElements"></div>

                    <div class="panel-footer" style="overflow:hidden;text-align:right;">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" class="btn btn-success btn-sm" name="revise" value="REVISE HEADMARK">
                                <!--<input type="submit" class="btn btn-danger btn-sm" name="delete" value="DELETE HEADMARK">-->
                            </div>
                        </div> 
                    </div> <!-- panel-footer -->

                </form>
            </div> <!-- panel-body -->  
        </div> <!-- panel-default -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('#projName').selectpicker();
            });
        </script>
    </body>
</html>    
