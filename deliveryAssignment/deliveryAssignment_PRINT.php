<?php
// PENTING
// Settingan Top:0mm, Bottom:5mm, left:0mm, Right:0mm //
// Shrink to feet. 100%//


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

$DONumber = $_GET['DONumber'];

if (isset($_GET['Ambil'])) { // for get multi print
    $showWT = $_GET['showWT'];
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var DONumber = "<?php echo $DONumber ?>";
            var showWT = "<?php echo $showWT ?>";
    <?php
# STRUCTURE
    $projectNameSql = "SELECT DISTINCT(PROJECT_NAME) FROM VW_DELIV_INFO WHERE DO_NO='$DONumber' ORDER BY PROJECT_NAME";
    $projectNameParse = oci_parse($conn, $projectNameSql);
    oci_execute($projectNameParse);
    while ($row = oci_fetch_array($projectNameParse)) {
        $PRJ = $row['PROJECT_NAME'];
        ?>
                var PRJ = "<?php echo $PRJ ?>"
                alert("Print Packing List STRUCTURE for Project " + PRJ);
                var URLNy = 'deliveryAssignment_PRINT.php?type=PCK_STR&DONumber=' + DONumber + '&projName=' + PRJ + '&showWT=' + showWT;
                PopupCenter(URLNy, 'WinDO_' + PRJ, '700', '842');

                if (confirm("Print Packing Photo STRUCTURE for Project " + PRJ)) {
                    var URLNy_1 = 'deliveryAssignment_PRINT.php?type=PCK_STR_photo&DONumber=' + DONumber + '&projName=' + PRJ + '&showWT=' + showWT;
                    PopupCenter(URLNy_1, 'WinDO_photo' + PRJ, '700', '842');
                } else {
                    // return false;
                }
        <?php
    }

# TANKAGE
//    $projectNameSql = "SELECT DISTINCT(PROJECT_NO),PROJECT_NAME FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE DO_NO='$DONumber' ORDER BY PROJECT_NAME";
//    $projectNameParse = oci_parse($conn, $projectNameSql);
//    oci_execute($projectNameParse);
//    while ($row = oci_fetch_array($projectNameParse)) {
//        $PROJECT_NO = $row['PROJECT_NO'];
//        $PROJ_NM = $row['PROJECT_NAME'];
    ?>
//            var PROJECT_NO = "<?php // echo $PROJECT_NO ?>";
//            var PROJ_NM = "<?php // echo $PROJ_NM ?>";
//            alert("Print Packing List TANKAGE for Project " + PROJECT_NO + " - " + PROJ_NM);
//            var URLNy_2 = 'deliveryAssignment_PRINT.php?type=PCK_TNK&DONumber=' + DONumber + '&projName=' + PROJ_NM + '&showWT=' + showWT;
//            PopupCenter(URLNy_2, 'WinDO_' + PROJ_NM, '700', '842');
//
//            if (confirm("Print Packing Photo TANKAGE for Project " + PROJECT_NO + " - " + PROJ_NM)) {
//                var URLNy_3 = 'deliveryAssignment_PRINT.php?type=PCK_TNK_photo&DONumber=' + DONumber + '&projName=' + PROJ_NM + '&showWT=' + showWT;
//                PopupCenter(URLNy_3, 'WinDO_photo' + PROJ_NM, '700', '842');
//            } else {
//                // return false;
//            }

    <?php
//    }
    ?>
        });
    </script>
    <?php
} else { // For Print single
    $DO_QRY = "SELECT * FROM MST_DELIV WHERE DO_NO='$DONumber'";
    $DO_PARSE = oci_parse($conn, $DO_QRY);
    oci_execute($DO_PARSE);
    $DO_ROW = oci_fetch_array($DO_PARSE);

    $PROJ_NO = $DO_ROW['PROJ_NO'];
    $PROJ_DESC = SingleQryFld("SELECT PROJECT_DESC FROM PROJECT WHERE PROJECT_NO='$PROJ_NO'", $conn);
    $CLIENT_NAME = SingleQryFld("SELECT CLIENT_NAME FROM MST_CLIENT WHERE CLIENT_ID in (SELECT CLIENT_ID FROM PROJECT WHERE PROJECT_NO='$PROJ_NO')", $conn);
    $CLIENT_INIT = SingleQryFld("SELECT CLIENT_INIT FROM MST_CLIENT WHERE CLIENT_ID in (SELECT CLIENT_ID FROM PROJECT WHERE PROJECT_NO='$PROJ_NO')", $conn);
    $CLIENT_ADDR = SingleQryFld("SELECT CLIENT_ADDR FROM MST_CLIENT WHERE CLIENT_ID in (SELECT CLIENT_ID FROM PROJECT WHERE PROJECT_NO='$PROJ_NO')", $conn);

    // echo "$projName -- $subcont -- $date1 -- $PROJ_NO -- $PROJ_DESC -- $CLIENT_NAME -- $SPV";
    // exit();
    ?>

    <!DOCTYPE html>
    <html>
        <head>
            <title>DELIVERY REPORT</title>
            <!-- bootstrap 3.0.2 -->
            <link href="../AdminLTE/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
            <link href="../AdminLTE/css/own.css" rel="stylesheet" type="text/css" />
            <style type="text/css">
                body {
                    top: 0;
                }

                table thead tr.hder td{
                    text-align: center;
                    vertical-align:middle;
                    /*font-weight: bold;*/
                    /*border: 1px solid black !important;*/
                }
                table thead tr td{
                    vertical-align:top;
                    font-size: 10px;
                }
                table thead tr td small{
                    font-size: 8px;
                }
                table tbody tr td{
                    font-size: 9px;
                    text-align: center;
                }
                table tfoot tr td{
                    text-align: left;
                    vertical-align:top;
                    font-size: 9px;
                }
                table tfoot.trfoot tr td{
                    text-align: center;
                    font-size: 8px;
                    vertical-align: bottom;
                }
                .tb_foot{
                    width: 400px;
                }
                .tb_foot tr td{
                    text-align: center;
                    font-size: 8px;
                    vertical-align: bottom;
                    border: 1px solid black !important;
                }

                .tdProj td{
                    background-color:#DFFFFF;
                    border: 1px solid black !important;
                }
                .tdProj2 td{
                    border-bottom: 1px solid black !important;
                }

                .bdy_dlv{
                    overflow: hidden;
                }
                .bdy_dlv tr td ,.hder_do td{
                    border: 1px solid black !important;
                }
                .hder_comptyp{
                    background-color:#F6F6F6;
                }
                @media print{
                    .tdProj td{
                        background-color:#DFFFFF !important;
                        -webkit-print-color-adjust: exact;
                    }
                    .hder_comptyp{
                        background-color:#F6F6F6 !important;
                        -webkit-print-color-adjust: exact;
                    }
                }
            </style>

            <script src="../jQuery/jquery-1.11.1.min.js"></script>
        </head>
        <!---->
        <body onload="window.print();">

            <?php if ($_GET['type'] == "DO"): ?>
                <table align="center" class="table-condensed" cellspacing="0" cellpadding="0" style="width:99%;top:0;">
                    <thead>
                        <tr class="hder">
                            <td colspan="3" rowspan="3" style="text-align:left;">
                                <img src="../images/logo.jpg" height="55">
                                <br>
                                <small>Jl. Raya Kedamean No. 168, Desa Mojo Tengah, Menganti-Gresik 61174, INDONESIA</small>
                                <br>
                                <small>Telp. : (031) 7913777 (Hunting) Fax.: (031) 7912047</small>
                            </td>
                            <td colspan="2" style="border: 1px solid black !important;"><font size="5" ><b>DELIVERY ORDER</b></font></td>
                        </tr>
                        <tr>
                          <!-- <td colspan="3"><small>Jl. Raya Kedamean No. 168, Desa Mojo Tengah, Menganti-Gresik 61174, INDONESIA</small></td> -->
                            <td>Vehicle No : <b><?php echo $DO_ROW['VHC_NO']; ?></b></td>
                            <td style="text-align:right;">Driver : <b><?php echo $DO_ROW['DVR']; ?></b></td>
                        </tr>
                        <tr>
                          <!-- <td colspan="3"><small>Telp. : (031) 7913777 (Hunting) Fax.: (031) 7912047</small></td> -->
                            <td colspan="2">Transporter : <?php echo $DO_ROW['T_PORTER']; ?></td>
                        </tr>
                        <!-- <tr>
                          <td colspan="3"><small>Please deliver the goods itemized below,</small></td>
                        </tr> -->
                        <tr>
                            <td colspan="3" rowspan="4">
                                <div class="table-bordered" style="height:95px;padding:5px;border: 1px solid black !important;">
                                    TO : <?php echo "<b>$CLIENT_NAME</b><br>" . $DO_ROW['DO_ADDR'] . "<br>" . $DO_ROW['DO_CITY']; ?>
                                    <br>
                                    Attn : <?php echo $DO_ROW['ATTN'] ?>
                                </div>
                            </td>
                            <td>DO No. </td>
                            <td style="font-size: 14px;">: <b><?php echo $DO_ROW['DO_NO']; ?></b></td>
                        </tr>
                        <tr>
                            <td>Date </td>
                            <td>: <?php echo $DO_ROW['DO_DATE']; ?></td>
                        </tr>
                        <tr>
                            <td>PO No. </td>
                            <td>: <?php echo $DO_ROW['PO_NO']; ?></td>
                        </tr>
                        <tr>
                            <td>Job No. </td>
                            <td>: <?php echo $DO_ROW['PROJ_NO']; ?></td>
                        </tr> 
                        <tr class="hder hder_do">
                            <td>No</td>
                            <td>Colli No.</td>
                            <td>Packing Type</td>
                            <td>Qty</td>
                            <td>Unit</td>
                        </tr>
                    </thead>
                    <tbody class="bdy_dlv">
                        <?php
                        $i = 0;

                        // packing STRUCTURE
                        $projectNameSql = "SELECT DISTINCT(COLI_NUMBER),PACK_TYP FROM VW_DELIV_INFO WHERE DO_NO='$DONumber' ORDER BY COLI_NUMBER";
                        $projectNameParse = oci_parse($conn, $projectNameSql);
                        oci_execute($projectNameParse);
                        while ($row = oci_fetch_array($projectNameParse)) {
                            $i++;
                            // if ($i==26) {
                            // break;
                            // }
                            // for ($i=0; $i < 5 ; $i++) { 
                            ?>
                            <tr>
                                <td><?php echo $i ?></td>                                        
                                <td><?php echo $row['COLI_NUMBER'] ?></td> 
                                <td><?php echo $row['PACK_TYP'] ?></td>
                                <td>1</td>
                                <td>COLLI</td>
                            </tr>
                            <?php
                            // }
                        }

                        // packing TANKAGE
//                        $projectNameSql = "SELECT DISTINCT(COLI_NUMBER),PACK_TYP FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE DO_NO='$DONumber' ORDER BY COLI_NUMBER";
//                        $projectNameParse = oci_parse($conn, $projectNameSql);
//                        oci_execute($projectNameParse);
//                        while ($row = oci_fetch_array($projectNameParse)) {
//                            $JUMLAH_ITEM = SingleQryFld("SELECT SUM(PACK_QTY) FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE COLI_NUMBER = '$row[COLI_NUMBER]'", $conn);
//                            $i++;
                            ?>
<!--                            <tr>
                                <td><?php // echo $i ?></td>                                        
                                <td><?php // echo $row['COLI_NUMBER'] ?></td> 
                                <td><?php // echo $row['PACK_TYP'] ?></td>
                                <td><?php // echo $JUMLAH_ITEM; ?></td>
                                <td>SEGMENT</td>
                            </tr>-->
                            <?php
                            // }
//                        }

                        $row_td = intval($i);
                        $row_td_per_page = 24; // setting pada mozilla page setup SCALE 92%
                        $pnjangHal = ceil($row_td / $row_td_per_page); //1;
                        $ts = intval($row_td_per_page * $pnjangHal);
                        $sisa_row = $ts - $row_td; //0;

                        /* echo $pnjangHal.' -- '.$row_td.' -- '.$sisa_row; */

                        for ($k = 1; $k <= ($sisa_row); $k++) {
                            ?>
                            <tr>
                                <td colspan="5" id="td_flexible">&nbsp;</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>		  
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="width:5cm;">Note :</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Delivered By;</td>
                                        <td class="text-center">Approved By;</td>
                                        <td class="text-center" colspan="2">Prepared &amp; Checked By;</td>
                                    </tr>
                                    <tr style="height:55px;">
                                        <td>
                                            <?php
                                            if ($DO_ROW['DO_REMS'] != '') {
                                                echo $DO_ROW['DO_REMS']->load();
                                            }
                                            ?>
                                        </td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;"><i><small>WEN</small></i><br><br><br><br>( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )</td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;"><i><small>
                                                    <?php
                                                    $retVal = ($CLIENT_INIT == 'IGG') ? 'MK' : '&nbsp;';
                                                    echo $retVal;
                                                    ?>
                                                </small></i><br><br><br><br>( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )</td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;"><i><small><?php echo $CLIENT_INIT ?></small></i><br><br><br><br>( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )</td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;">( <?php echo strtoupper($DO_ROW['DVR']); ?> )</td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;">( EDIYANTO )</td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;">( DADANG )</td>
                                        <td style="vertical-align:bottom;text-align:center;border: 1px solid black !important;">( <?php echo strtoupper($DO_ROW['DO_SPV']); ?> )</td>
                                    </tr>
                                    <tr>
                                        <td><small>Printed by : <?php echo $username ?></small></td>
                                        <td class="text-left" style="border-bottom: 1px solid black !important;">Date:</td>
                                        <td class="text-left" style="border-bottom: 1px solid black !important;">Date:</td>
                                        <td class="text-left" style="border-bottom: 1px solid black !important;">Date:</td>
                                        <td style="border-bottom: 1px solid black !important;">&nbsp;</td>
                                        <td class="text-center" style="border-bottom: 1px solid black !important;">Factory Manager</td>
                                        <td class="text-center" style="border-bottom: 1px solid black !important;">Logistic Checker</td>
                                        <td class="text-center" style="border-bottom: 1px solid black !important;">Supervisor</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">-Detail Packing List Terlampir.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="8">
                                            -Packing List Terlampir adalah bagian yang tidak terpisahkan dari delivery order ini.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>      
                    </tfoot>
                </table>
                <script type="text/javascript">
                    var hg_per_page = 620;
                    var TinggiBody = $('.bdy_dlv').height();
                    var td_flexible = $('#td_flexible').height();
                    var pnjangHal = Math.ceil(parseInt(TinggiBody) / hg_per_page);

                    var maxHeight = hg_per_page * (pnjangHal);
                    var emptyTD_hg = maxHeight - TinggiBody;
                    // $('#td_flexible').height(emptyTD_hg);
                    td_flexible = $('#td_flexible').height();

                    console.log(td_flexible + ' -- t.body ' + TinggiBody + ' -- p. hal ' + Math.ceil(pnjangHal) + ' -- max hg ' + maxHeight);
                </script>
            <?php elseif ($_GET['type'] == "PCK_STR"):
                $projName = $_GET['projName'];
                $showWT = $_GET['showWT'];
                $TOT_WEIGHT = SingleQryFld("SELECT SUM(UNIT_PCK_WT) FROM VW_PCK_INFO DTL WHERE COLI_NUMBER in (SELECT DISTINCT(COLI_NUMBER) FROM VW_DELIV_INFO WHERE DO_NO='$DONumber' AND COLI_NUMBER=DTL.COLI_NUMBER)", $conn);
                $TOT_VOLUME = SingleQryFld("SELECT SUM(PACK_LEN*PACK_HT*PACK_WID) FROM MST_PACKING PCK WHERE COLI_NUMBER in (SELECT DISTINCT(COLI_NUMBER) FROM VW_DELIV_INFO WHERE DO_NO='$DONumber' AND COLI_NUMBER=PCK.COLI_NUMBER)", $conn);
                $TOT_VOLUME = round((($TOT_VOLUME) / 1000000000), 2);
                ?>
                <table align="center" class="table-condensed table-bordered" cellspacing="0" cellpadding="0" style="width:100%;top:0;" >
                    <thead>
                        <tr>
                            <td colspan="14">
                                <table width="100%">
                                    <tr>
                                        <td rowspan="3" class="text-left"><img src="../images/logo.jpg" height="55"></td>
                                        <td rowspan="3" style="vertical-align:middle;"><font size="5"><b>PACKING LIST</b></font></td>
                                        <td style="vertical-align:middle;">Date Delivery. <?php echo $DO_ROW['DO_DATE'] ?></td>
                                    </tr>
                                    <tr class="hder">
                                        <td>Vehicel No. <?php echo $DO_ROW['VHC_NO'] ?></td>
                                    </tr>
                                    <tr class="hder">
                                        <td>Driver. <?php echo $DO_ROW['DVR'] ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">DO NO : <b><span style="font-size: 14px;"><?php echo $DONumber ?></span></b></td>
                            <td colspan="4">PO NO : <?php echo $DO_ROW['PO_NO'] ?></td>
                            <td colspan="7">SPK NO : <?php echo $DO_ROW['SPK_NO'] ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Client : <?php echo $CLIENT_NAME ?></td>
                            <td colspan="4">JOB : <?php echo $PROJ_NO ?></td>
                            <td colspan="7" id="wt0">Tot.Wt : <?php echo number_format($TOT_WEIGHT, 1) ?> kg</td>
                        </tr>
                        <tr class="tdProj2">
                            <td colspan="3">Fabricator : PT. WELTES ENERGI NUSANTARA</td>
                            <td colspan="4">SUBJOB : <?php echo $projName ?></td>
                            <td colspan="7">Tot.Vol : <?php echo number_format($TOT_VOLUME, 1) ?> m<sup>3</sup></td>
                        </tr>
                        <tr class="hder tdProj">
                            <td style="width:10px;"><small>Form.5</small></td>
                            <td rowspan="2"><b>Packing No.</b></td>
                            <td rowspan="2" style="width:auto;"><b>Head Mark</b></td>
                            <td colspan="2"><b>Description</b></td>
                            <td rowspan="2"><b>Ov.<br>Length<br>(mm)</b></td>
                            <td rowspan="2"><b>Qty<br><small>(Pcs)</small></b></td>
                            <td rowspan="2" id="wt1"><b>Unit<br>WT</b></td>
                            <td rowspan="2" id="wt2"><b>Sub<br>Tot<br>WT</b></td>
                            <td rowspan="2" id="wt3"><b>Tot<br>WT<br><small>(Kg)</small></b></td>
                            <td colspan="3"><b>Dimension (mm)</b></td>
                            <td rowspan="2"><b>Vol<br>(m<sup>3</sup>)</b></td>
                        </tr>
                        <tr class="hder tdProj">
                            <td><b>No</b></td>
                            <td><b>Comp Type</b></td>
                            <td><b>Main Profile</b></td>
                            <td><b>P</b></td>
                            <td><b>L</b></td>
                            <td><b>T</b></td>
                        </tr>
                    </thead>
                    <tbody class="bdy_dlv" >
                        <?php
                        $row_td = 0;
                        $i = 1;
                        $SUBTOT_WT = 0;
                        $SUBTOT_VOL = 0;
                        // where WELTESADMIN.PACKING_DATE between $DateFirst and $DateLast
                        $sqlPck = oci_parse($conn, "SELECT PCK.* FROM MST_PACKING PCK WHERE COLI_NUMBER in (SELECT DISTINCT(COLI_NUMBER) FROM VW_DELIV_INFO WHERE DO_NO='$DONumber' AND PROJECT_NAME='$projName' AND COLI_NUMBER=PCK.COLI_NUMBER) ORDER BY PCK.COLI_NUMBER");
                        oci_execute($sqlPck);
                        while ($rowPck = oci_fetch_array($sqlPck)) {
                            $row_td++;
                            $COLI_NUMBER = $rowPck['COLI_NUMBER'];

                            $PACKING_LENGTH = $rowPck['PACK_LEN'];
                            $PACKING_WIDTH = $rowPck['PACK_WID'];
                            $PACKING_HEIGHT = $rowPck['PACK_HT'];
                            $PACKING_VOLUME = round((($PACKING_LENGTH * $PACKING_WIDTH * $PACKING_HEIGHT) / 1000000000), 2);
                            $PACKING_WEIGHT = round(SingleQryFld("SELECT SUM(UNIT_PCK_WT) FROM VW_PCK_INFO WHERE COLI_NUMBER='$COLI_NUMBER'", $conn, 1));
                            $SHIPMENT_NO = "&nbsp";
                            if (!empty($rowPck['SHIPMENT_NO'])) {
                                // $SHIPMENT_NO   = $rowPck['SHIPMENT_NO'];
                            }

                            $VehicleNo = SingleQryFld("SELECT VHC_NO FROM MST_DELIV WHERE DO_NO = '$DONumber'", $conn);
                            $DO_DATE = SingleQryFld("SELECT DO_DATE FROM MST_DELIV WHERE DO_NO = '$DONumber'", $conn);

                            $SUBTOT_WT += round($PACKING_WEIGHT, 2);
                            $SUBTOT_VOL += round($PACKING_VOLUME, 2);
                            ?>
                            <tr class="isi">
                                <td><?php echo $i ?></td>
                                <td><?php echo $COLI_NUMBER ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td id="wt4">&nbsp;</td>
                                <td id="wt5">&nbsp;</td>
                                <td id="wt6"><?php echo $PACKING_WEIGHT ?></td>
                                <td><?php echo $PACKING_LENGTH ?></td>
                                <td><?php echo $PACKING_WIDTH ?></td>
                                <td><?php echo $PACKING_HEIGHT ?></td>
                                <td><?php echo $PACKING_VOLUME ?></td>
                            </tr>
                            <?php
                            $SubTotWg = "";
                            $TotWg = "";
                            $fnlTotWg = "";

                            $j = 0;
                            $query = "SELECT * from WELTESADMIN.MASTER_DRAWING LEFT JOIN WELTESADMIN.DTL_PACKING ON WELTESADMIN.MASTER_DRAWING.HEAD_MARK=WELTESADMIN.DTL_PACKING.HEAD_MARK where DWG_STATUS = 'ACTIVE' AND WELTESADMIN.DTL_PACKING.COLI_NUMBER='" . $COLI_NUMBER . "' order By WELTESADMIN.DTL_PACKING.HEAD_MARK";
                            // echo "$query";
                            $sqlPPck = oci_parse($conn, $query);
                            oci_execute($sqlPPck);
                            $jml = oci_num_fields($sqlPPck);
                            while ($rowPPck = oci_fetch_array($sqlPPck)) {
                                $row_td++;
                                $j++;
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
                                // $fnlTotWg  = $TotWg;
                                // }
                                ?>
                                <tr class="isi">
                                    <td><?php echo "&nbsp;" ?></td>
                                    <td>&nbsp;</td>
                                    <td class="text-left"><?php echo $HEAD_MARK ?></td>
                                    <td><?php echo $COMP_TYPE ?></td>
                                    <td><?php echo $PROFILE ?></td>
                                    <td><?php echo $OVLENGTH ?></td>
                                    <td><?php echo $UNIT_QTY ?></td>
                                    <td id="wt7"><?php echo $UNIT_WEIGHT ?></td>
                                    <td id="wt8"><?php echo $SubTotWg ?></td>
                                    <td id="wt9">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <?php
                            }
                            $i++;
                            // if ($i==13) {
                            // break;
                            // }
                        }

                        $row_td_per_page = 26; // setting page setup mozilla SCALE 92%
                        $pnjangHal = ceil($row_td / $row_td_per_page); //1;
                        // $nextHAL = $row_td - $row_td_per_page;
                        // if ($nextHAL>0) {
                        //   $pnjangHal = ceil($nextHAL/26);
                        // }		  

                        $add_row = 0; //$pnjangHal - 1 ; // cz if page more than 1 page no 2 dst jumlh row 26
                        // if($showWT == 'show'){
                        // $add_row = $pnjangHal;
                        // }

                        $ts = intval($row_td_per_page * $pnjangHal);
                        $sisa_row = $ts - $row_td; //0;
                        // if ($nextHAL>0) {
                        //   $sisa_row = (26*$pnjangHal)%$nextHAL;
                        // } else {
                        //   $sisa_row = $row_td_per_page-$row_td;
                        // }
                        // echo $pnjangHal.' -- '.$row_td.' -- '.$sisa_row.' add row = '.$add_row;

                        for ($k = 1; $k <= ($sisa_row + $add_row); $k++) {
                            ?>
                            <tr class="isi">
                                <td colspan="14" id="td_flexible" class="text-left">&nbsp;<?php //echo $row_td+$k                                                      ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>  
                    <tfoot class="trfoot">
                        <tr>
                            <td colspan="3" style="vertical-align:middle;">Approved By ; PT. WELTES ENERGI NUSANTARA</td>
                            <td colspan="4" id="wt10">Tot. WT <?php echo strtolower($projName) ?>: <br><b><?php echo number_format($SUBTOT_WT, 1) ?></b> <sup>Kg</sup></td>
                            <td colspan="7">Tot. Vol <?php echo strtolower($projName) ?>: <br><b><?php echo number_format($SUBTOT_VOL, 1) ?></b> <sup>m3</sup></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <table cellpadding="0" cellspacing="0" class="tb_foot" style="width:100%;">
                                    <tr style="height:55px;">
                                        <td>( EDIYANTO )</td>
                                        <td>( DADANG )</td>
                                        <td>( <?php echo strtoupper($DO_ROW['DO_SPV']); ?> )</td>
                                    </tr>
                                    <tr>
                                        <td>Factory Manager</td>
                                        <td>Logistic Checker</td>
                                        <td>Supervisor</td>
                                    </tr>
                                </table>
                            </td>
                            <td colspan="11">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Delivery By;</td>
                                    </tr>
                                    <tr style="height:55px;">
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small>WEN</small></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php
                                                    $retVal = ($CLIENT_INIT == 'IGG') ? 'MK' : '&nbsp;';
                                                    echo $retVal;
                                                    ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php echo $CLIENT_INIT ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( <?php echo strtolower($DO_ROW['DVR']) ?> )<br><i><small>DVR</small></i></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var showWT = "<?php echo $showWT ?>";
                        var hg_per_page_real = 721;
                        var hg_per_page = 738;
                        var TinggiBody = $('.bdy_dlv').height();
                        var pnjangHal = Math.ceil(parseInt(TinggiBody) / hg_per_page_real);

                        if (showWT == 'hide') {
                            $('td[id ^= wt]').text('-');

                            if (TinggiBody <= hg_per_page_real) { // 1 page
                                hg_per_page = 690;
                                pnjangHal = 1;
                            } else { // more than 1 page
                                hg_per_page = 600;
                                pnjangHal = Math.ceil(parseInt(TinggiBody) / hg_per_page_real);
                            }
                        } else {
                            if (TinggiBody <= hg_per_page) {
                                // hg_per_page = 710;
                            }
                        }
                        // console.log("per page "+hg_per_page);

                        // var td_flexible   = $('#td_flexible').height();
                        // $('.bdy_dlv').css("height","15cm");
                        // var maxHeight     = hg_per_page*(pnjangHal);

                        // var emptyTD_hg    = maxHeight-TinggiBody;
                        // if (emptyTD_hg < 0 ) { emptyTD_hg=0 };

                        // $('#td_flexible').css("height","15cm");
                        // td_flexible   = $('#td_flexible').height();
                        // console.log(emptyTD_hg+' -- t.body '+TinggiBody+' -- p. hal '+ Math.ceil(pnjangHal)+' -- max hg '+maxHeight);
                    });
                </script>

            <?php elseif ($_GET['type'] == "PCK_STR_photo"):
                $DONumber = $_GET['DONumber'];
                $projName = $_GET['projName'];
                $showWT = $_GET['showWT'];
                ?>
                <style type="text/css">
                    .tbl tbody tr td{
                        padding:0px !important;						
                    }
                </style>
                <table align="center" cellspacing="0" cellpadding="0" style="width:100%;top:0;" class="table-condensed" >
                    <thead>
                        <tr>
                            <th colspan="3" class=""><span style="width: 20%;"><img src="../images/logo.jpg" height="55"></span> STRUCTURE Packing Photos for <?php echo $DONumber; ?></th>
                        </tr>
                        <tr>                           
                            <th class="text-center">coli number</th>
                            <th class="text-center">coli number</th>
                            <th class="text-center">coli number</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php
                        $i = 0;
                        $j = 0;
                        $sql = "SELECT DISTINCT(VP.COLI_NUMBER),MPI.IMG_NAME FROM "
                                . " VW_DELIV_INFO VP INNER JOIN MST_PACKING_IMG MPI ON VP.COLI_NUMBER = MPI.COLI_NUMBER "
                                . " WHERE DO_NO = '$DONumber' AND PROJECT_NAME = '$projName' ORDER BY COLI_NUMBER";
                        $sqlPck = oci_parse($conn, $sql);
                        oci_execute($sqlPck);
                        $jmlColi = oci_fetch_all($sqlPck, $output);
                        oci_execute($sqlPck);
                        while ($rowPck = oci_fetch_array($sqlPck)) {
                            $COLI_NUMBER = $rowPck['COLI_NUMBER'];
                            $img_name = $rowPck['IMG_NAME'];
                            if ($i % 3 == 0) {
                                $j ++;
                                ?>
                                <tr style="text-align:center;">							  
                                    <td style="height: 190px;">								  
                                        <table style="width:95%;" align="center" class="table-condensed table-bordered tbl">
                                            <tr class="text-left">
                                                <td><?php echo $i + 1 ?></td>
                                                <td><label style="font-size:14px;"><?php echo $COLI_NUMBER ?></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">                                                   
                                                    <div style="float:right">
                                                        <img id="img_colliREAL_<?php echo $i ?>" class="hide" src="../packingAssignment/img_packing/<?php echo $img_name ?>">
                                                        <img id="img_colli_<?php echo $i ?>" src="../packingAssignment/img_packing/<?php echo $img_name ?>" height="150" width="200">
                                                    </div>                                                        
                                                </td>
                                            </tr>
                                        </table>
                                    </td>                                    							  
                                    <?php
                                } else {
                                    ?>							  
                                    <td style="height: 190px;">
                                        <table style="width:95%;" align="center" class="table-condensed table-bordered tbl">
                                            <tr class="text-left">
                                                <td><?php echo $i + 1 ?></td>
                                                <td><label style="font-size:14px;"><?php echo $COLI_NUMBER ?></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">                                                    
                                                    <div style="float:right">
                                                        <img id="img_colliREAL_<?php echo $i ?>" class="hide" src="../packingAssignment/img_packing/<?php echo $img_name ?>">
                                                        <img id="img_colli_<?php echo $i ?>" src="../packingAssignment/img_packing/<?php echo $img_name ?>" height="150" width="200">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>	
                                    <?php
                                    if ($j == 2) {
                                        echo "</tr>";
                                        $j = 0;
                                    } else {
                                        $j ++;
                                    }
                                }
                                $i++;
                            }
                            $row_td_per_page = 9; // setting mozzila page setup SCALE 92%
                            $pnjangHal = ceil($jmlColi / $row_td_per_page); //1;
                            //echo $pnjangHal . " -- ";

                            $ts = intval($row_td_per_page * $pnjangHal);
                            $sisa_row = $ts - $jmlColi; //0;
                            $td_last = ($sisa_row % 3);
                            //echo $sisa_row . " - " . $td_last;
                            for ($k = 1; $k <= $td_last; $k++) {
                                // echo "$k <br>";
                                if ($k == $td_last) {
                                    echo '<td style="height: 190px;">Empty</td></tr>';
                                } else {
                                    echo '<td style="height: 190px;">Empty</td>';
                                }
                            }
                            $tr_new = ($sisa_row - $td_last) / 3;
                            //echo $tr_new;
                            for ($k = 1; $k <= $tr_new; $k++) {
                                echo '<tr>'
                                . '<td style="height: 190px;">Empty</td>'
                                . '<td style="height: 190px;">Empty</td>'
                                . '<td style="height: 190px;">Empty</td>'
                                . '</tr>';
                            }
                            ?>
                    </tbody>  
                    <tfoot class="trfoot">
                        <tr>
                            <td colspan="3"><hr/></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <hr/><hr/><hr/><hr/>
                                <table width="100%" cellpadding="0" cellspacing="0" border="1">
                                    <tr>
                                        <td>Factory Manager</td>
                                        <td>Logistic Checker</td>
                                        <td>Supervisor</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Delivery By;</td>
                                    </tr>
                                    <tr style="height:55px;">
                                        <td>( EDIYANTO )</td>
                                        <td>( DADANG )</td>
                                        <td>( <?php echo strtoupper($DO_ROW['DO_SPV']); ?> )</td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small>WEN</small></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php
                                                    $retVal = ($CLIENT_INIT == 'IGG') ? 'MK' : '&nbsp;';
                                                    echo $retVal;
                                                    ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php echo $CLIENT_INIT ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( <?php echo strtolower($DO_ROW['DVR']) ?> )<br><i><small>DVR</small></i></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('img[id ^= img_colliREAL_]').each(function () {
                            var id = $(this).attr('id').replace('img_colliREAL_', '');
                            var image = new Image();
                            image.src = $(this).attr("src");
                            image.onload = function () {
                                var hgThs = this.height;
                                console.log('height: ' + hgThs);
                                if (parseInt(hgThs) <= 170) {
                                    $('#img_colli_' + id).height(hgThs);
                                    console.log('Smaller Image');
                                }
                            };
                        });
                    });
                </script>

            <?php elseif ($_GET['type'] == "PCK_TNK"):
                $DONumber = $_GET['DONumber'];
                $projName = $_GET['projName'];
                $showWT = $_GET['showWT'];
                $TOT_WEIGHT = SingleQryFld("SELECT SUM(WEIGHT*PACK_QTY) FROM VW_PCK_INFO@WELTES_TANKAGE_LINK DTL WHERE COLI_NUMBER in (SELECT DISTINCT(COLI_NUMBER) FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE DO_NO='$DONumber' AND COLI_NUMBER=DTL.COLI_NUMBER)", $conn);
                $TOT_VOLUME = SingleQryFld("SELECT SUM(PACK_VOL) FROM MST_PACKING@WELTES_TANKAGE_LINK PCK WHERE COLI_NUMBER in (SELECT DISTINCT(COLI_NUMBER) FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE DO_NO='$DONumber' AND COLI_NUMBER=PCK.COLI_NUMBER)", $conn);
                $TOT_VOLUME = round((($TOT_VOLUME) / 1000000000), 2);
                ?>
                <table align="center" class="table-condensed table-bordered" cellspacing="0" cellpadding="0" style="width:100%;top:0;" >
                    <thead>
                        <tr>
                            <td colspan="15">
                                <table width="100%">
                                    <tr>
                                        <td rowspan="3" class="text-left"><img src="../images/logo.jpg" height="55"></td>
                                        <td rowspan="3" style="vertical-align:middle;"><font size="5"><b>PACKING LIST</b></font></td>
                                        <td style="vertical-align:middle;">Date Delivery. <?php echo $DO_ROW['DO_DATE'] ?></td>
                                    </tr>
                                    <tr class="hder">
                                        <td>Vehicel No. <?php echo $DO_ROW['VHC_NO'] ?></td>
                                    </tr>
                                    <tr class="hder">
                                        <td>Driver. <?php echo $DO_ROW['DVR'] ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">DO NO : <b><span style="font-size: 14px;"><?php echo $DONumber ?></span></b></td>
                            <td colspan="4">PO NO : <?php echo $DO_ROW['PO_NO'] ?></td>
                            <td colspan="8">SPK NO : <?php echo $DO_ROW['SPK_NO'] ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Client : <?php echo $CLIENT_NAME ?></td>
                            <td colspan="4">JOB : <?php echo $PROJ_NO ?></td>
                            <td colspan="8" id="wt0">Tot.Wt : <?php echo number_format($TOT_WEIGHT, 1) ?> kg</td>
                        </tr>
                        <tr class="tdProj2">
                            <td colspan="3">Fabricator : PT. WELTES ENERGI NUSANTARA</td>
                            <td colspan="4">SUBJOB : <?php echo $projName ?></td>
                            <td colspan="8">Tot.Vol : <?php echo number_format($TOT_VOLUME, 1) ?> m<sup>3</sup></td>
                        </tr>
                        <tr class="hder tdProj">
                            <td style="width:10px;"><small>Form.5</small></td>
                            <td rowspan="2"><b>Packing No.</b></td>
                            <td rowspan="2" style="width:auto;"><b>DWG No.</b></td>
                            <td colspan="5"><b>Profile</b></td>
                            <td rowspan="2"><b>Qty<br><small>(Pcs)</small></b></td>
                            <td rowspan="2" id="wt1"><b>Unit<br>WT</b></td>
                            <!-- <td rowspan="2" id="wt2"><b>Sub<br>Tot<br>WT</b></td> -->
                            <!-- <td rowspan="2" id="wt3"><b>Tot<br>WT<br><small>(Kg)</small></b></td> -->
                            <td colspan="3"><b>Dimension (mm)</b></td>
                            <td rowspan="2"><b>Vol<br>(m<sup>3</sup>)</b></td>
                            <td rowspan="2"><b>Photo</b><br><i>(attachment)</i></td>
                        </tr>
                        <tr class="hder tdProj">
                            <td><b>No</b></td>
                            <td><b>Material</b></td>
                            <td><b>Desc.</b></td>
                            <td><b>Pjg<br>(mm)</b></td>
                            <td><b>Lbr<br>(mm)</b></td>
                            <td><b>Tbl<br>(mm)</b></td>
                            <td><b>P</b></td>
                            <td><b>L</b></td>
                            <td><b>T</b></td>
                        </tr>
                    </thead>
                    <tbody class="bdy_dlv" >
                        <?php
                        $row_td = 0;
                        $i = 1;
                        $SUBTOT_WT = 0;
                        $SUBTOT_VOL = 0;
                        $sqlPck = oci_parse($conn, "SELECT DISTINCT(COLI_NUMBER),PACK_LEN,PACK_WID,THICKNESS_SIZE,PACK_VOL FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE DO_NO = '$DONumber' AND PROJECT_NAME = '$projName' ORDER BY COLI_NUMBER");
                        oci_execute($sqlPck);
                        while ($rowPck = oci_fetch_array($sqlPck)) {
                            $row_td++;
                            $COLI_NUMBER = $rowPck['COLI_NUMBER'];

                            $PACKING_LENGTH = doubleval($rowPck['PACK_LEN']);
                            $PACKING_WIDTH = doubleval($rowPck['PACK_WID']);
                            $PACKING_HEIGHT = doubleval($rowPck['THICKNESS_SIZE']);
                            $PACKING_VOLUME = $PACKING_LENGTH * $PACKING_WIDTH * $PACKING_HEIGHT / 1000000000;

                            $SUBTOT_VOL += round($PACKING_VOLUME, 2);
                            $jmlPhotos = SingleQryFld("SELECT COUNT(*) FROM IMG_PACKING@WELTES_TANKAGE_LINK WHERE COLI_NUMBER = '$COLI_NUMBER'", $conn);
                            ?>
                            <tr class="isi" style="border-top: 2px solid gray;">
                                <td><?php echo $i ?></td>
                                <td colspan="9" class="text-left"><b><?php echo $COLI_NUMBER ?></b></td>
                                <!--<td colspan="9" style="color: gray;">&nbsp;</td>-->
                                <td><?php echo number_format($PACKING_LENGTH, 0) ?></td>
                                <td><?php echo number_format($PACKING_WIDTH, 0) ?></td>
                                <td><?php echo number_format($PACKING_HEIGHT, 0) ?></td>
                                <td><?php echo number_format($PACKING_VOLUME, 2) ?></td>
                                <td><?php
                                    $retVal = ($jmlPhotos == 0) ? 'no Photo' : 'see no ' . $i;
                                    echo $retVal
                                    ?></td>
                            </tr>
                            <?php
                            $sql = "SELECT DISTINCT COMP_TYPE FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE COLI_NUMBER='" . $COLI_NUMBER . "' ORDER BY COMP_TYPE";
                            $parse = oci_parse($conn, $sql);
                            oci_execute($parse);
                            while ($row = oci_fetch_array($parse)) {
                                $row_td++;
                                echo "<tr class='isi'>";
                                echo "<td>&nbsp;</td>";
                                echo "<td>&nbsp;</td>";
                                echo "<td colspan='13' class='text-left hder_comptyp'><i><b>" . $row['COMP_TYPE'] . "</b></i></td>";
                                echo "</tr>";

                                $SubTotWg = "";
                                $TotWg = "";
                                $fnlTotWg = "";

                                $query = "SELECT * FROM VW_DLV_INFO@WELTES_TANKAGE_LINK WHERE COLI_NUMBER='" . $COLI_NUMBER . "' AND COMP_TYPE='$row[COMP_TYPE]' ORDER BY HEAD_MARK_NUM,HEAD_MARK,HEAD_MARK_DTL_NUM";
                                // echo "$query";
                                $sqlPPck = oci_parse($conn, $query);
                                oci_execute($sqlPPck);
                                while ($rowPPck = oci_fetch_array($sqlPPck)) {
                                    $row_td++;
                                    $HEAD_MARK = $rowPPck['HEAD_MARK_DTL'];
                                    $MD_REMS = $rowPPck['MD_REMS'];
                                    $DESCRIPTION = $rowPPck['DESCRIPTION'];
                                    $MATERIAL_TYPE = $rowPPck['MATERIAL_TYPE'];
                                    $PACK_QTY = $rowPPck['PACK_QTY'];
                                    $UNIT_WEIGHT = floatval($rowPPck['WEIGHT']);
                                    $LENGTH_SIZE = $rowPPck['LENGTH_SIZE'];
                                    $WIDTH_SIZE = $rowPPck['WIDTH_SIZE'];
                                    $THICK_SIZE = $rowPPck['THICKNESS_SIZE'];
                                    $SUBTOT_WT += ($UNIT_WEIGHT * $PACK_QTY);
                                    ?>
                                    <tr class="isi">
                                        <td><?php // echo "$row_td"                        ?></td>
                                        <td>&nbsp;</td>
                                        <td class="text-left"><?php echo $HEAD_MARK ?></td>
                                        <td><?php echo $MATERIAL_TYPE ?></td>
                                        <td><?php echo $DESCRIPTION ?></td>
                                        <td><?php echo $LENGTH_SIZE ?></td>
                                        <td><?php echo $WIDTH_SIZE ?></td>
                                        <td><?php echo $THICK_SIZE ?></td>
                                        <!--<td><?php // echo $MD_REMS                          ?></td>-->
                                        <td><?php echo $PACK_QTY ?></td>
                                        <td id="wt7"><?php echo number_format($UNIT_WEIGHT, 1) ?></td>
                                        <!-- <td id="wt8"><?php echo $SubTotWg ?></td> -->
                                        <!-- <td id="wt9">&nbsp;</td> -->
                                        <td colspan="4"><?php echo $MD_REMS ?></td>
                    <!--                                        <td><?php // echo $WIDTH_SIZE                             ?></td>
                                        <td><?php // echo $THICK_SIZE                             ?></td>
                                        <td>&nbsp;</td>-->
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                }
                            }
                            // if ($i==13) {
                            // break;
                            // }
                            $i++;
                        }

                        $row_td_per_page = 24;
                        $pnjangHal = ceil($row_td / $row_td_per_page);
                        $add_row = 0;
                        $ts = intval($row_td_per_page * $pnjangHal);
                        $sisa_row = $ts - $row_td;
//                        echo $pnjangHal." -- ".$sisa_row." -- $row_td";

                        for ($k = 1; $k <= ($sisa_row + $add_row); $k++) {
                            ?>
                            <tr class="isi">
                                <td colspan="15" id="td_flexible" class="text-left">&nbsp;<?php // echo $row_td+$k                                                   ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>  
                    <tfoot class="trfoot">
                        <tr>
                            <td colspan="4" style="vertical-align:middle;">Approved By ; PT. WELTES ENERGI NUSANTARA</td>
                            <td colspan="5" id="wt10">Tot. WT <?php echo strtolower($projName) ?>: <br><b><?php echo number_format($SUBTOT_WT, 1) ?></b> <sup>Kg</sup></td>
                            <td colspan="8">Tot. Vol <?php echo strtolower($projName) ?>: <br><b><?php echo number_format($SUBTOT_VOL, 1) ?></b> <sup>m3</sup></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table cellpadding="0" cellspacing="0" class="tb_foot" style="width:100%;">
                                    <tr style="height:55px;">
                                        <td>( EDIYANTO )</td>
                                        <td>( DADANG )</td>
                                        <td>( <?php echo strtoupper($DO_ROW['DO_SPV']); ?> )&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>Factory Manager</td>
                                        <td>Logistic Checker</td>
                                        <td>Supervisor Job</td>
                                    </tr>
                                </table>
                            </td>
                            <td colspan="12">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Delivery By;</td>
                                    </tr>
                                    <tr style="height:55px;">
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small>WEN</small></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php
                                                    $retVal = ($CLIENT_INIT == 'IGG') ? 'MK' : '&nbsp;';
                                                    echo $retVal;
                                                    ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php echo $CLIENT_INIT ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( <?php echo strtolower($DO_ROW['DVR']) ?> )<br><i><small>DVR</small></i></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script type="text/javascript">
                    $(document).ready(function () {
                        var showWT = "<?php echo $showWT ?>";
                        var hg_per_page_real = 721;
                        var hg_per_page = 738;
                        var TinggiBody = $('.bdy_dlv').height();
                        var pnjangHal = Math.ceil(parseInt(TinggiBody) / hg_per_page_real);

                        if (showWT == 'hide') {
                            $('td[id ^= wt]').text('-');

                            if (TinggiBody <= hg_per_page_real) { // 1 page
                                hg_per_page = 690;
                                pnjangHal = 1;
                            } else { // more than 1 page
                                hg_per_page = 600;
                                pnjangHal = Math.ceil(parseInt(TinggiBody) / hg_per_page_real);
                            }
                        } else {
                            if (TinggiBody <= hg_per_page) {
                                // hg_per_page = 710;
                            }
                        }
                    });
                </script>

            <?php elseif ($_GET['type'] == "PCK_TNK_photo"):
                $DONumber = $_GET['DONumber'];
                $projName = $_GET['projName'];
                $showWT = $_GET['showWT'];
                ?>
                <style type="text/css">
                    .tbl tbody tr td{
                        padding:0px !important;						
                    }
                </style>
                <table align="center" cellspacing="0" cellpadding="0" style="width:100%;top:0;" class="table-condensed" >
                    <thead>
                        <tr>
                            <th colspan="3" class=""><span style="width: 20%;"><img src="../images/logo.jpg" height="55"></span> Packing Photos for <?php echo $DONumber; ?></th>
                        </tr>
                        <tr>                           
                            <th class="text-center">coli number</th>
                            <th class="text-center">coli number</th>
                            <th class="text-center">coli number</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        <?php
                        $i = 0;
                        $j = 0;
                        $sql = "SELECT DISTINCT (IMG_PACKING.COLI_NUMBER), "
                                . "IMG_PACKING.IMG_NAME "
                                . "FROM VW_DLV_INFO@WELTES_TANKAGE_LINK INNER JOIN IMG_PACKING@WELTES_TANKAGE_LINK "
                                . "ON VW_DLV_INFO.COLI_NUMBER = IMG_PACKING.COLI_NUMBER "
                                . "WHERE DO_NO = '$DONumber' "
                                . "ORDER BY COLI_NUMBER";
                        $sqlPck = oci_parse($conn, $sql);
                        oci_execute($sqlPck);
                        $jmlColi = oci_fetch_all($sqlPck, $output);
                        oci_execute($sqlPck);
                        while ($rowPck = oci_fetch_array($sqlPck)) {
                            $COLI_NUMBER = $rowPck['COLI_NUMBER'];
                            $img_name = $rowPck['IMG_NAME'];
                            if ($i % 3 == 0) {
                                $j ++;
                                ?>
                                <tr style="text-align:center;">							  
                                    <td style="height: 190px;">								  
                                        <table style="width:95%;" align="center" class="table-condensed table-bordered tbl">
                                            <tr class="text-left">
                                                <td><?php echo $i + 1 ?></td>
                                                <td><label style="font-size:14px;"><?php echo $COLI_NUMBER ?></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">                                                   
                                                    <div style="float:right">
                                                        <img id="img_colliREAL_<?php echo $i ?>" class="hide" src="/weltestankage/content/maindiv_content/update/packing/upload/<?php echo $img_name ?>">
                                                        <img id="img_colli_<?php echo $i ?>" src="/weltestankage/content/maindiv_content/update/packing/upload/<?php echo $img_name ?>" height="150">
                                                    </div>                                                        
                                                </td>
                                            </tr>
                                        </table>
                                    </td>                                    							  
                                    <?php
                                } else {
                                    ?>							  
                                    <td style="height: 190px;">
                                        <table style="width:95%;" align="center" class="table-condensed table-bordered tbl">
                                            <tr class="text-left">
                                                <td><?php echo $i + 1 ?></td>
                                                <td><label style="font-size:14px;"><?php echo $COLI_NUMBER ?></label></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">                                                    
                                                    <div style="float:right">
                                                        <img id="img_colliREAL_<?php echo $i ?>" class="hide" src="/weltestankage/content/maindiv_content/update/packing/upload/<?php echo $img_name ?>">
                                                        <img id="img_colli_<?php echo $i ?>" src="/weltestankage/content/maindiv_content/update/packing/upload/<?php echo $img_name ?>" height="150">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>	
                                    <?php
                                    if ($j == 2) {
                                        echo "</tr>";
                                        $j = 0;
                                    } else {
                                        $j ++;
                                    }
                                }
                                $i++;
                            }
                            $row_td_per_page = 9;
                            $pnjangHal = ceil($jmlColi / $row_td_per_page); //1;
                            //echo $pnjangHal . " -- ";

                            $ts = intval($row_td_per_page * $pnjangHal);
                            $sisa_row = $ts - $jmlColi; //0;
                            $td_last = ($sisa_row % 3);
                            //echo $sisa_row . " - " . $td_last;
                            for ($k = 1; $k <= $td_last; $k++) {
                                // echo "$k <br>";
                                if ($k == $td_last) {
                                    echo '<td style="height: 190px;">Empty</td></tr>';
                                } else {
                                    echo '<td style="height: 190px;">Empty</td>';
                                }
                            }
                            $tr_new = ($sisa_row - $td_last) / 3;
                            //echo $tr_new;
                            for ($k = 1; $k <= $tr_new; $k++) {
                                echo '<tr>'
                                . '<td style="height: 190px;">Empty</td>'
                                . '<td style="height: 190px;">Empty</td>'
                                . '<td style="height: 190px;">Empty</td>'
                                . '</tr>';
                            }
                            ?>
                    </tbody>  
                    <tfoot class="trfoot">
                        <tr>
                            <td colspan="3"><hr/></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <table width="100%" cellpadding="0" cellspacing="0" border="1">
                                    <tr>
                                        <td>Factory Manager</td>
                                        <td>Logistic Checker</td>
                                        <td>Supervisor</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Received By;</td>
                                        <td class="text-center">Delivery By;</td>
                                    </tr>
                                    <tr style="height:55px;">
                                        <td>( EDIYANTO )</td>
                                        <td>( DADANG )</td>
                                        <td>( <?php echo strtoupper($DO_ROW['DO_SPV']); ?> )</td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small>WEN</small></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php
                                                    $retVal = ($CLIENT_INIT == 'IGG') ? 'MK' : '&nbsp;';
                                                    echo $retVal;
                                                    ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( &nbsp;&nbsp;&nbsp;.......&nbsp;&nbsp;&nbsp; )<br><i><small><?php echo $CLIENT_INIT ?></small></i></td>
                                        <td class="text-center" style="vertical-align:bottom;">( <?php echo strtolower($DO_ROW['DVR']) ?> )<br><i><small>DVR</small></i></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('img[id ^= img_colliREAL_]').each(function () {
                            var id = $(this).attr('id').replace('img_colliREAL_', '');
                            var image = new Image();
                            image.src = $(this).attr("src");
                            image.onload = function () {
                                var hgThs = this.height;
                                console.log('height: ' + hgThs);
                                if (parseInt(hgThs) <= 170) {
                                    $('#img_colli_' + id).height(hgThs);
                                    console.log('Smaller Image');
                                }
                            };
                        });
                    });
                </script>
            <?php endif ?>
        </body>
    </html>    
    <?php
}
?>