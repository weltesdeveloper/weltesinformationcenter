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
        <!--<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css" />-->
        <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="../AdminLTE/css/bootstrap-editable.css">
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="../jQuery/jquery-1.11.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/bootstrap-select.min.js"></script>
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../AdminLTE/js/bootstrap-editable.min.js"></script>
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
                        <label for="name" class="col-sm-1 control-label">PROJECT NAME</label>
                        <div class="col-sm-11">
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

                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label"></label>
                        <div class="col-sm-11">
                            <table class="table table-striped table-bordered" id="table-revision">
                                <thead>
                                    <tr>
                                        <th class="text-center">DWG ID</th>
                                        <th class="text-center">HEAD MARK</th>
                                        <th class="text-center">COMP TYPE</th>
                                        <th class="text-center">PROFILE</th>
                                        <th class="text-center">SURFACE</th>
                                        <th class="text-center">LENGTH</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">STATUS</th>
                                        <th class="text-center">WEIGHT</th>
                                        <th class="text-center">GROSS WEIGHT</th>
                                        <th class="text-center">DWG TYPE</th>
                                        <th class="text-center">TYPE BUILDING</th>
                                        <th class="text-center">REMARK REV</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label"></label>
                        <div class="col-sm-11">
                            <button type="button" class="btn btn-primary col-sm-12" onclick="ShowModal();">SHOW DATA</button>
                        </div>
                    </div>
                </form>
            </div> <!-- panel-body -->  
        </div> <!-- panel-default -->
        <div id="modal-revision" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content modal-lg" style="width: 1400px; margin-left: -400px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">List Revision Head Mark</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered" id="table-showrev">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">DWG ID</th>
                                    <th class="text-center" style="vertical-align: middle;">HEAD MARK</th>
                                    <th class="text-center" style="vertical-align: middle;">COMP TYPE</th>
                                    <th class="text-center" style="vertical-align: middle;">PROFILE</th>
                                    <th class="text-center" style="vertical-align: middle;">SURFACE</th>
                                    <th class="text-center" style="vertical-align: middle;">LENGTH</th>
                                    <th class="text-center" style="vertical-align: middle;">QTY</th>
                                    <th class="text-center" style="vertical-align: middle;">STATUS</th>
                                    <th class="text-center" style="vertical-align: middle;">WEIGHT</th>
                                    <th class="text-center" style="vertical-align: middle;">GROSS WEIGHT</th>
                                    <th class="text-center" style="vertical-align: middle;">DWG TYPE</th>
                                    <th class="text-center" style="vertical-align: middle;">TYPE BUILDING</th>
                                    <th class="text-center" style="vertical-align: middle;">REMARK REV</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" onclick="SubmitRevision();">Submit</button>
                    </div>
                </div>

            </div>
        </div>
        <?php
        $arraySubcont = "";
        $sql = "SELECT DISTINCT COMP_TYPE FROM MASTER_DRAWING WHERE TYPE_BLD = 'STRUCTURE' ORDER BY COMP_TYPE ASC";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        while ($row1 = oci_fetch_array($parse)) {
            $xx = "{value:'$row1[COMP_TYPE]', text: '$row1[COMP_TYPE]'},";
            $arraySubcont .= $xx;
        }

        $arraySubcont = substr($arraySubcont, 0, strlen($arraySubcont) - 1);
        $arraySubcont = "[" . $arraySubcont . "]";
        ?>
        <script>
            var source_comptype = <?php echo "$arraySubcont"; ?>;
        </script>
        <script src="ControllerJS/MainController.js"></script>

    </body>
</html>    
