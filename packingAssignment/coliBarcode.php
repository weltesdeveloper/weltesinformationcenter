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

$coliNumber = strval($_GET['coliNumber']);
$PCKPrntSze = strval($_GET['PCKPrntSze']);

//set it to writable location, a place for temp generated PNG files
$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'barcode/temp' . DIRECTORY_SEPARATOR;

//html PNG location prefix
$PNG_WEB_DIR = 'barcode/temp/';

include "barcode/qrlib.php";

//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
    mkdir($PNG_TEMP_DIR);


//processing form input
//remember to sanitize user input in real-life solution !!!
$errorCorrectionLevel = 'H';
// if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
//     $errorCorrectionLevel = $_REQUEST['level'];    

$matrixPointSize = 7;
// if (isset($_REQUEST['size']))
//     $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
//it's very important!
if (trim($coliNumber) == '')
    die('data cannot be empty! <a href="?">back</a>');

// user data
$filename = $PNG_TEMP_DIR . md5($coliNumber) . '.png';
if (!file_exists($filename)) {
    # code...
    QRcode::png($coliNumber, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PT. WELTES ENERGI NUSANTARA | COLI BARCODE</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="PT. Weltes Energi Nusantara PACKING ASSIGNMENT">
        <meta name="author" content="Chris Immanuel">

        <!-- Le styles -->
        <link rel="icon" type="image/ico" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="revisionCss/own.css">
        <style>
            body{
                background-color: #F8F8F8;
            }
            .center {
                margin-left: auto;
                margin-right: auto;
                width: 100%;
                background-color: #fff;
                /*height: auto;*/
                vertical-align: middle;
            }
        </style>
    </head>
    <!--  -->
    <body onload="ShowHide('<?php echo $PCKPrntSze; ?>');">
        <script type="text/javascript">
            function ShowHide(act) {
                // alert("OKKK");
                // body...
                var PCKSize = $("#PCKSize");
                var PCKSize1 = $("#PCKSize1");
                var PCKSize2 = $("#PCKSize2");
                var PCKSize3 = $("#PCKSize3");
                var PCKSize4 = $("#PCKSize4");
                if (act == "hide") {
                    PCKSize.text("");
                    PCKSize1.text("");
                    PCKSize2.text("");
                    PCKSize3.text("");
                    PCKSize4.text("");
                } else {
                    // PCKSize.show();
                    // PCKSize2.show();
                    // $("#SHPack").text("-");
                }
                window.print();
                window.close();
            }
        </script>
        <?php
        $sql = "SELECT PCK.* FROM MST_PACKING PCK WHERE PCK.COLI_NUMBER = '$coliNumber' ";
// echo "$sql";
        $sqlPck = oci_parse($conn, $sql);
        oci_execute($sqlPck);
        $rowPck = oci_fetch_array($sqlPck, OCI_BOTH);

        $PACKING_LENGTH = $rowPck['PACK_LEN'];
        $PACKING_WIDTH = $rowPck['PACK_WID'];
        $PACKING_HEIGHT = $rowPck['PACK_HT'];
        $PROJECT_NAME = $rowPck['PROJECT_NAME'];
        $ZON_LOC = $rowPck['ZON_LOC'];
        $PACK_TYP = $rowPck['PACK_TYP'];
        $BOX_WT = $rowPck['BOX_WT'];
        $PACKING_VOLUME = round((($PACKING_LENGTH * $PACKING_WIDTH * $PACKING_HEIGHT) / 1000000000), 2);
        $PACKING_WEIGHT = SingleQryFld("SELECT SUM(UNIT_PCK_WT) FROM VW_PCK_INFO WHERE COLI_NUMBER='$coliNumber'", $conn);

        $PROJNO = SingleQryFld("SELECT PROJECT_NO FROM VW_PROJ_INFO WHERE PROJECT_NAME_OLD = '$PROJECT_NAME'", $conn);
        $PROJNM_NEW = SingleQryFld("SELECT PROJECT_NAME_NEW FROM VW_PROJ_INFO WHERE PROJECT_NAME_OLD = '$PROJECT_NAME'", $conn);
        ?>
        <div class="container">
            <table align="center" class="center">
                <thead>
                    <tr>
                        <th colspan="5">
                            <img src="img_packing/weltesLogo.jpg">
                        </th>
                        <th colspan="3" rowspan="4">
                            <img class="table-bordered" src="<?php echo $PNG_WEB_DIR . basename($filename); ?>" width="200" height="200">
                            <!-- <div class="col-xs-11 table-bordered" style="text-align:center;"> -->
                            <?php //echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';  ?>
                            <!-- </div> -->
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5" style="padding:0 0 0 0;margin:0 0 0 0;">
                            <h1>
                                <font size="1px" style="float:left;"><i><?php echo $PROJNO . ". " . $PROJNM_NEW ?></i></font>
                                <b><?php echo $coliNumber; ?></b>
                            </h1>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5">
                            <div id="PCKSize" class="row">
                                <div class="col-xs-4">
                                    Length: <b><?php echo $PACKING_LENGTH ?></b> <small>mm</small>
                                </div>
                                <div class="col-xs-3">
                                    Width: <b><?php echo $PACKING_WIDTH ?></b> <small>mm</small>
                                </div>
                                <div class="col-xs-5">
                                    Height: <b><?php echo $PACKING_HEIGHT ?></b> <small>mm</small>
                                </div>
                            </div>
                        </th>
                        <!-- <th colspan="2" style="padding-left:5px;"><div id="PCKSize1">Width: <b><?php //echo $PACKING_WIDTH   ?></b> <small>mm</small></div></th> -->
                        <!-- <th colspan="1"><div id="PCKSize2">Height: <b><?php //echo $PACKING_HEIGHT   ?></b> <small>mm</small></div></th> -->
                    </tr>
                    <tr>
                        <th colspan="5">
                            <div id="PCKSize3" class="row">
                                <div class="col-xs-7">
                                    Gross Weight: <b><?php echo $PACKING_WEIGHT + $BOX_WT ?></b> <small>kg</small>
                                </div>
                                <div class="col-xs-5">
                                    Net Weight: <b><?php echo $PACKING_WEIGHT ?></b> <small>kg</small>
                                </div>
                            </div>
                        </th>
                        <!-- <th colspan="3" style="padding-left:5px;"><div id="PCKSize4">Net Weight: <b><?php //echo $PACKING_WEIGHT  ?></b> <small>kg</small></div></th> -->
                    </tr>
                    <tr>
                        <th colspan="5">&nbsp;</th>
                        <th colspan="3"><div class="col-xs-12" style="text-align:left;"><h5>Packing Type: <b><?php echo $PACK_TYP ?></b></h5></div></th>
                    </tr>
                    <tr >
                        <th style="width:50px;">&nbsp;</th>
                        <th style="text-align:center;">Head Mark</th>
                        <th style="text-align:center;width:100px;">QTY (pcs)</th>
                        <th style="width:50px;">&nbsp;</th>
                        <th style="text-align:center;" colspan="2">Head Mark</th>
                        <th style="text-align:center;width:100px;">QTY (pcs)</th>
                        <th style="width:50px;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $j = 0;
                    $query = "SELECT * from WELTESADMIN.MASTER_DRAWING LEFT JOIN WELTESADMIN.DTL_PACKING ON WELTESADMIN.MASTER_DRAWING.HEAD_MARK=WELTESADMIN.DTL_PACKING.HEAD_MARK where WELTESADMIN.DTL_PACKING.COLI_NUMBER='" . $coliNumber . "' AND DWG_STATUS='ACTIVE' order By WELTESADMIN.DTL_PACKING.HEAD_MARK";
// echo "$query";
                    $sqlPPck = oci_parse($conn, $query);
                    oci_execute($sqlPPck);
                    while ($rowPPck = oci_fetch_array($sqlPPck, OCI_BOTH)) {

                        $HEAD_MARK = $rowPPck['HEAD_MARK'];
                        $COMP_TYPE = $rowPPck['COMP_TYPE'];
                        $PROFILE = $rowPPck['PROFILE'];
                        $OVLENGTH = $rowPPck['LENGTH'];
                        $UNIT_QTY = $rowPPck['UNIT_PCK_QTY'];
                        $UNIT_WEIGHT = $rowPPck['WEIGHT'];
                        $SubTotWg = ($UNIT_QTY * $UNIT_WEIGHT);
                        // $TotWg += $SubTotWg;
                        // echo "$j == $jml<br>";
                        // if ($j==$jml) {
                        // $fnlTotWg    = $TotWg;
                        // }
                        // for ($i=0; $i < 14 ; $i++) { 

                        if ($j % 2 == 0) {
                            # code...
                            ?>
                            <tr style="text-align:center;" class="table table-striped table-condensed">
                                <td>&nbsp;</td>
                                <td><div class="table-bordered"><?php echo $HEAD_MARK ?></div></td>
                                <td><div class="table-bordered"><?php echo $UNIT_QTY ?></div></td>
                                <td>&nbsp;</td>
                                <?php
                            } else {
                                ?>
                                <td colspan="2"><div class="table-bordered"><?php echo $HEAD_MARK ?></div></td>
                                <td><div class="table-bordered"><?php echo $UNIT_QTY ?></div></td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php
                        }
                        $j++;
                        // }  
                    }
                    if ($j % 2 <> 0) {
                        echo "<td colspan='2'><div class=\"table-bordered\">&nbsp;</div></td><td><div class=\"table-bordered\">&nbsp;</div></td><td>&nbsp;</td></tr>";
                    }
                    ?>
                </tbody>
            </table>        
        </div>
        <!-- JS SRC -->
        <script src="../js/bootstrap.min.js"></script>
        <script src="../jQuery/jquery-1.11.0.min.js"></script>
    </body>
</html>