<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();
// GENERATE THE APPLICATION PAGE
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);

// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$DTAwal = $_GET['DTAwal'];
$DTAkhir = $_GET['DTAkhir'];

$projectValSQL = "PROJECT_NAME LIKE '%'";

if ($_GET["projData"] <> "ALL") {
    # code...
    $projectValSQL = 'PROJECT_NAME in ';
    $projNM = '(';
    list($proj_no, $proj_code) = explode("^", $_GET["projData"]);
    if ($proj_code == "ALL") {
        $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
    } else {
        $projectNameSql = "SELECT * FROM PROJECT WHERE PROJECT_NO = '$proj_no' AND PROJECT_CODE='$proj_code' ORDER BY PROJECT_NO ASC,PROJECT_NAME";
    }

    $projectNameParse = oci_parse($conn, $projectNameSql);
    oci_execute($projectNameParse);
    while ($projectNameROW = oci_fetch_array($projectNameParse)) {
        $projNM .= "'" . $projectNameROW['PROJECT_NAME'] . "',";
    }
    $projectValSQL .= substr_replace($projNM, "", -1) . ")";
    // echo "$projectValSQL";
}

$dt1 = new DateTime($DTAwal);
$dt2 = new DateTime($DTAkhir);


header("Content-type: application/octet-stream;");
header('Content-Disposition: attachment;filename="Packing-List.xls"');
header("Pragma: no-cache");
header("Expires: 0");
echo "\xef\xbb\xbf";
//disini script laporan anda
?>
<meta charset="utf-8">
<h4>Report Packing List Not Delivery <?php echo "<b>" . $dt1->format('l, F d, Y') . "</b> - <b>" . $dt2->format('l, F d, Y') . "</b>" ?></h4>

<table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr class="hder">
        <th>Form. 5</th>
        <th rowspan="2">Packing No.</th>
        <th rowspan="2">Assembly OR<br>Marking No.</th>
        <th colspan="2">Description</th>
        <!-- <th>Main Profile</th> -->
        <th rowspan="2">Overal<br>Length<br>(mm)</th>
        <th rowspan="2">Qty<br>(Pcs)</th>
        <th rowspan="2">Unit Weight</th>
        <th rowspan="2">Sub Total Weight</th>
        <th rowspan="2">Total Weight</th>
        <th colspan="3">Dimension (mm)</th>
        <!-- <th>L</th> -->
        <!-- <th>T</th> -->
        <th rowspan="2">Volume (m<sup>3</sup>)</th>
        <th rowspan="2">Ship No.</th>
        <th rowspan="2">Do. No.</th>
        <th rowspan="2">Vehicle License</th>
        <th rowspan="2">DO. Date</th>
        <th rowspan="2">Prepared By</th>
        <th rowspan="2">Checked</th>
        </tr>
        <tr class="hder">
        <th>No</th>
        <!-- <th>Packing No.</th> -->
        <!-- <th>Assembly OR Marking No.</th> -->
        <th>Main Member Name (Component)</th>
        <th>Main Profile</th>
        <!-- <th>Ov. Lenght</th> -->
        <!-- <th>Qty</th> -->
        <!-- <th>Unit Weight</th> -->
        <!-- <th>Sub Total Weight</th> -->
        <!-- <th>Total Weight</th> -->
        <th>P</th>
        <th>L</th>
        <th>T</th>
        <!-- <th>Volume</th>
        <th>Ship No.</th>
        <th>Do.No</th>
        <th>Vehicle License</th>
        <th>Tanggal</th>
        <th>Prepared By</th>
        <th>Checked</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
// where WELTESADMIN.PACKING_DATE between $DateFirst and $DateLast

        $sql_pack = "SELECT PCK.* FROM MST_PACKING PCK "
                . " WHERE $projectValSQL AND "
                . " ACT_PACK_DATE BETWEEN TO_DATE('$DTAwal 00:00:01', 'MM/DD/YYYY HH24:MI:SS') AND "
                . " TO_DATE('$DTAkhir 23:59:59', 'MM/DD/YYYY HH24:MI:SS') AND "
                . " PCK_STAT = 'ACTIVE' "
                . " AND DLV_STAT = 'ND' "
                . " ORDER BY SUBSTR(PCK.COLI_NUMBER, LENGTH(PCK.COLI_NUMBER)-4)";
        //echo $sql;
        $sqlPck = oci_parse($conn, $sql_pack);
        oci_execute($sqlPck);
        while ($rowPck = oci_fetch_array($sqlPck)) {

            $COLI_NUMBER = $rowPck['COLI_NUMBER'];

            $PACKING_LENGTH = $rowPck['PACK_LEN'];
            $PACKING_WIDTH = $rowPck['PACK_WID'];
            $PACKING_HEIGHT = $rowPck['PACK_HT'];
            $PACKING_VOLUME = round((($PACKING_LENGTH * $PACKING_WIDTH * $PACKING_HEIGHT) / 1000000000), 2);
            $PACKING_WEIGHT = SingleQryFld("SELECT SUM(UNIT_PCK_WT) FROM VW_PCK_INFO WHERE COLI_NUMBER='$COLI_NUMBER'", $conn);
            $SHIPMENT_NO = "&nbsp";
            if (!empty($rowPck['SHIPMENT_NO'])) {
                // $SHIPMENT_NO		= $rowPck['SHIPMENT_NO'];
            }

            $DONumber = SingleQryFld("SELECT DO_NO FROM DTL_DELIV WHERE COLI_NUMBER = '$COLI_NUMBER'", $conn);
            $VehicleNo = SingleQryFld("SELECT VHC_NO FROM MST_DELIV WHERE DO_NO = '$DONumber'", $conn);
            $DO_DATE = SingleQryFld("SELECT DO_DATE FROM MST_DELIV WHERE DO_NO = '$DONumber'", $conn);
            ?>
            <tr class="isi">
            <td><?php echo $i ?></td>
            <td><?php echo $COLI_NUMBER ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $PACKING_WEIGHT ?></td>
            <td><?php echo $PACKING_LENGTH ?></td>
            <td><?php echo $PACKING_WIDTH ?></td>
            <td><?php echo $PACKING_HEIGHT ?></td>
            <td><?php echo $PACKING_VOLUME ?></td>
            <td><?php echo $SHIPMENT_NO ?></td>
            <td><?php echo $DONumber ?></td>
            <td><?php echo $VehicleNo ?></td>
            <td><?php echo $DO_DATE ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <?php
            $SubTotWg = "";
            $TotWg = "";
            $fnlTotWg = "";

            $j = 0;
            $query = "SELECT * from WELTESADMIN.MASTER_DRAWING LEFT JOIN WELTESADMIN.DTL_PACKING ON WELTESADMIN.MASTER_DRAWING.HEAD_MARK=WELTESADMIN.DTL_PACKING.HEAD_MARK where WELTESADMIN.DTL_PACKING.COLI_NUMBER='" . $COLI_NUMBER . "' AND MASTER_DRAWING.DWG_STATUS = 'ACTIVE' ORDER BY WELTESADMIN.DTL_PACKING.HEAD_MARK";
//		 echo "$query";
            $sqlPPck = oci_parse($conn, $query);
            oci_execute($sqlPPck);
            $jml = oci_num_fields($sqlPPck);
            while ($rowPPck = oci_fetch_array($sqlPPck)) {
                $j++;
                $HEAD_MARK = $rowPPck['HEAD_MARK'];
                $COMP_TYPE = $rowPPck['COMP_TYPE'];
                $PROFILE = $rowPPck['PROFILE'];
                $OVLENGTH = $rowPPck['LENGTH'];
                $UNIT_QTY = $rowPPck['UNIT_PCK_QTY'];
                $UNIT_WEIGHT = $rowPPck['WEIGHT'];
                $SubTotWg = $rowPPck['WEIGHT'] * $rowPPck['UNIT_PCK_QTY'];
                // $TotWg += $SubTotWg;
                // echo "$j == $jml<br>";
                // if ($j==$jml) {
                // $fnlTotWg 	= $TotWg;
                // }
                ?>
                <tr class="isi">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><?php echo $HEAD_MARK ?></td>
                <td><?php echo $COMP_TYPE ?></td>
                <td><?php echo $PROFILE ?></td>
                <td><?php echo $OVLENGTH ?></td>
                <td><?php echo $UNIT_QTY ?></td>
                <td><?php echo $UNIT_WEIGHT ?></td>
                <td><?php echo $SubTotWg ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
                <?php
            }
            $i++;
        }
        ?>

    </tbody>	
</table>

