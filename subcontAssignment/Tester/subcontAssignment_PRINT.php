<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
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

$projName = $_GET['projName'];
$subcont = $_GET['subcont'];
$date1 = $_GET['date1'];
$time1 = strval($_GET['time1']) . ":00";

$dt1 = new dateTime($date1);

$PROJ_NO = SingleQryFld("SELECT PROJECT_NO FROM PROJECT WHERE PROJECT_NAME='$projName'", $conn);
$PROJ_DESC = SingleQryFld("SELECT PROJECT_DESC FROM PROJECT WHERE PROJECT_NAME='$projName'", $conn);
$CLIENT_NAME = SingleQryFld("SELECT CLIENT_NAME FROM MST_CLIENT WHERE CLIENT_ID in (SELECT CLIENT_ID FROM PROJECT WHERE PROJECT_NAME='$projName')", $conn);

$SPV_SQL = "SELECT DISTINCT(SPV_FAB) FROM MASTER_DRAWING_ASSIGNED WHERE PROJECT_NAME='$projName' AND SUBCONT_ID='$subcont' AND ASSIGNMENT_DATE >= TO_DATE('$date1 $time1', 'MM/DD/YYYY hh24:mi:ss') AND ASSIGNMENT_DATE <= TO_DATE ('$date1 23:59:59', 'MM/DD/YYYY hh24:mi:ss')";
// echo "$SPV_SQL<hr>";
$SPV = SingleQryFld($SPV_SQL, $conn);

$QC_INSP_SQL = "SELECT DISTINCT(QC_INSP) FROM MASTER_DRAWING_ASSIGNED WHERE PROJECT_NAME='$projName' AND SUBCONT_ID='$subcont' AND ASSIGNMENT_DATE >= TO_DATE('$date1 $time1', 'MM/DD/YYYY hh24:mi:ss') AND ASSIGNMENT_DATE <= TO_DATE ('$date1 23:59:59', 'MM/DD/YYYY hh24:mi:ss')";
// echo "$QC_INSP_SQL<hr>";
$QC_INSP = SingleQryFld($QC_INSP_SQL, $conn);


// echo "$projName -- $subcont -- $date1 -- $PROJ_NO -- $PROJ_DESC -- $CLIENT_NAME -- $SPV";
// exit();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>DAILY ASSIGNMENT REPORT</title>
        <!-- bootstrap 3.0.2 -->
        <link href="../../AdminLTE/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all" />
        <link href="../../AdminLTE/css/own.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            /* table {
               width: 97%;
             }*/
            table tbody tr td{
                font-size: 9px;
            }
            table thead tr.pross th{
                width: 40px;
            }
            table thead tr.hder th{
                text-align: center;
            }
            table thead tr th{
                vertical-align:middle;
                font-size: 8px;
            }
            table tfoot tr td{
                text-align: left;
                vertical-align:middle;
                font-size: 8px;
            }

            .tdProj tr td{
                background-color:#DFFFFF;
            }
            .tdComp tr td{
                background-color:#F3F4B8;
            }

            @media print{
                .tdProj tr td{
                    background-color:#DFFFFF !important;
                    -webkit-print-color-adjust: exact;
                }
                .tdComp tr td{
                    background-color:#F3F4B8 !important;
                    -webkit-print-color-adjust: exact;
                }
            }
        </style>
    </head>
    <!-- -->
    <body onload="window.print();
            window.close();">
              <?php if ($_GET['type'] == "FABR" or $_GET['type'] == "FABR_REV"): ?>
            <table align="center" class="table-condensed table-bordered" cellspacing="0" cellpadding="0">
                <thead>
                    <tr class="hder">
                        <th colspan="3" style="text-align:left;"><img src="../../images/logo.jpg" height="45"></th>
                        <th colspan="9" style="font-size: 11px;"><b>LAPORAN HARIAN FABRIKASI <?php
                                if ($_GET['type'] == "FABR_REV") {
                                    echo "REVISI";
                                }
                                ?></b></th>
                        <th colspan="10"><b>TANGGAL DRAWING TURUN</b><br><b style="font-size: 11px;"><?php echo $dt1->format('d-F-Y') ?></b></th>
                    </tr>
                    <tr>
                        <th colspan="12"><b>PROJECT : <?php echo $PROJ_DESC; ?></b></th>
                        <th colspan="10"><b>CLIENT : <?php echo $CLIENT_NAME; ?></b></th>
                    </tr>
                    <tr>
                        <th colspan="12"><b>JOB NO : <?php echo $PROJ_NO; ?></b></th>
                        <th colspan="10"><b>SPV : <?php echo $SPV; ?></b></th>
                    </tr>
                    <tr>
                        <th colspan="12"><b>SUB JOB : <?php echo $projName; ?></b></th>
                        <th colspan="10"><b>SUBCONT : <?php echo $subcont ?></b></th>
                    </tr>
                    <tr class="hder">
                        <th rowspan="3">No</th>
                        <th rowspan="3">Head Mark</th>
                        <th rowspan="3">Profile</th>
                        <th rowspan="3">Length <sup>mm</sup></th>
                        <th rowspan="3">Qty</th>
                        <th rowspan="3">Wt <sup>kg</sup></th>
                        <th rowspan="3">Tot Wt <sup>kg</sup></th>
                        <th rowspan="3">Target Finish</th>
                        <th colspan="12">Progress</th>
                        <th rowspan="2" colspan="2">Qc Passed Fab</th>
                    </tr>
                    <tr class="hder">
                        <th colspan="2">MARK</th>
                        <th colspan="2">CUTT</th>
                        <th colspan="2">ASSY</th>
                        <th colspan="2">WELD</th>
                        <th colspan="2">DRILL</th>
                        <th colspan="2">FIN</th>
                    </tr>
                    <tr class="hder pross">
                        <th>Qty</th>
                        <th>Tgl</th>
                        <th>Qty</th>
                        <th>Tgl</th>
                        <th>Qty</th>
                        <th>Tgl</th>
                        <th>Qty</th>
                        <th>Tgl</th>
                        <th>Qty</th>




                        <th>Tgl</th>
                        <th>Qty</th>
                        <th>Tgl</th>
                        <th>Qty</th>
                        <th>Tgl</th>
                    </tr>
                </thead>
                <?php
                $projectNameSql = "SELECT MD.PROFILE, MD.LENGTH,MD.WEIGHT,MD.COMP_TYPE, MDA.* "
                        . " FROM MASTER_DRAWING_ASSIGNED MDA INNER JOIN MASTER_DRAWING MD "
                        . " ON MD.HEAD_MARK=MDA.HEAD_MARK AND MD.PROJECT_NAME=MDA.PROJECT_NAME "
                        . " WHERE MDA.PROJECT_NAME='$projName' AND MDA.SUBCONT_ID='$subcont' AND "
                        . " ASSIGNMENT_DATE >= TO_DATE('$date1 $time1', 'MM/DD/YYYY hh24:mi:ss') AND "
                        . " ASSIGNMENT_DATE <= TO_DATE ('$date1 23:59:59', 'MM/DD/YYYY hh24:mi:ss') AND MD.DWG_STATUS='ACTIVE'"
                        . " ORDER BY MD.COMP_TYPE , TO_NUMBER(REGEXP_REPLACE(MDA.HEAD_MARK,'[a-zA-Z''-]',''))";
                // echo "$projectNameSql -- $selectedDate1 --$selectedDate2";
                $projectNameParse = oci_parse($conn, $projectNameSql);
                oci_execute($projectNameParse);
                ?>
                <tbody>
                    <?php
                    $i = 0;
                    $TOT_WT_TOT = 0;
                    while ($row = oci_fetch_array($projectNameParse)) {
                        $i++;
                        $TOT_WT = $row['ASSIGNED_QTY'] * $row['WEIGHT'];
                        $date_Assg = new dateTime($row['ASSIGNED_DUE_DATE']);
                        ?>
                        <tr>
                            <td><?php echo $i ?></td>                                        
                            <td><?php echo $row['HEAD_MARK'] ?></td> 
                            <td><?php echo $row['PROFILE'] ?></td>
                            <td><?php echo number_format($row['LENGTH'], 0); ?></td>
                            <td><?php echo $row['ASSIGNED_QTY']; ?></td>
                            <td><?php echo number_format($row['WEIGHT'], 1); ?></td>
                            <td><?php echo number_format($TOT_WT, 1); ?></td>
                            <td><?php echo $date_Assg->format("d/m/y") ?></td>
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
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                        $TOT_WT_TOT += $TOT_WT;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align:right;" colspan="6"><b>Total : </b></td>
                        <td><b><?php echo number_format($TOT_WT_TOT, 1) ?></b></td>
                        <td colspan="15">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2">KETERANGAN : </td>
                        <td colspan="20">** Qty Di Isi Jumlah Item Yang Sudah Di QC Setiap Tahap Produksi</td>
                    </tr>
                    <tr>
                        <td colspan="20">** Apabila Belum Ada Yang Selesai Sama Sekali Namun Sudah Mulai Proses, Di Beri Tanda (<b>.</b>)</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center;">Subkon<br><br><br><br>(&nbsp;<?php echo strtolower($subcont) ?>&nbsp;)</td>
                        <td colspan="2" style="text-align:center;">SPV. Fabrikasi<br><br><br><br>(&nbsp;<?php echo strtolower($SPV) ?>&nbsp;)</td>
                        <td colspan="3" style="text-align:center;">QC. Inspector<br><br><br><br>(&nbsp;<?php echo strtolower($QC_INSP) ?>&nbsp;)</td>
                        <td colspan="3" style="text-align:center;">Dibuat Tanggal<br><?php echo date("d/m/Y") ?><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                        <td colspan="5" style="text-align:center;">PPIC Division<br><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                        <td colspan="7">Foto Lengkap<br>[ &nbsp; ] Ya<br>[ &nbsp; ] Tidak</td>
                    </tr>
                </tfoot>
            </table>
        <?php elseif ($_GET['type'] == "PAINT" or $_GET['type'] == "PAINT_REV"): ?>
            <table align="center" class="table-condensed table-bordered" cellspacing="0" cellpadding="0">
                <thead>
                    <tr class="hder">
                        <th colspan="3" style="text-align:left;"><img src="../../images/logo.jpg" height="45"></th>
                        <th colspan="9"><h5><b>LAPORAN HARIAN PAINTING <?php
                        if ($_GET['type'] == "PAINT_REV") {
                            echo "REVISI";
                        }
                        ?></b></h5></th>
            <th colspan="9"><b>TANGGAL DRAWING TURUN</b><br><h5><b><?php echo $dt1->format('d-F-Y') ?></b></h5></th>
    </tr>
    <tr>
        <th colspan="12"><b>PROJECT : <?php echo $PROJ_DESC; ?></b></th>
        <th colspan="9"><b>CLIENT : <?php echo $CLIENT_NAME; ?></b></th>
    </tr>
    <tr>
        <th colspan="12"><b>JOB NO : <?php echo $PROJ_NO; ?></b></th>
        <th colspan="9"><b>SPV : <?php echo $SPV; ?></b></th>
    </tr>
    <tr>
        <th colspan="12"><b>SUB JOB : <?php echo $projName; ?></b></th>
        <th colspan="9"><b>SUBCONT : <?php echo $subcont ?></b></th>
    </tr>
    <tr class="hder">
        <th rowspan="3">No</th>
        <th rowspan="3">Head Mark</th>
        <th rowspan="3">Profile</th>
        <th rowspan="3">Length <sup>mm</sup></th>
        <th rowspan="3">Qty</th>
        <th rowspan="3">Area M<sup>2</sup></th>
        <th rowspan="3">Tot Area M<sup>2</sup></th>
        <th rowspan="2" colspan="2">Recv. From Fab.</th>
        <th colspan="8">Progress</th>
        <th rowspan="2" colspan="2">Qc Passed Paint</th>
        <th rowspan="2" colspan="2">Trf. To Pack</th>
    </tr>
    <tr class="hder">
        <th colspan="2">BLAST</th>
        <th colspan="2">PRIM</th>
        <th colspan="2">INTMD</th>
        <th colspan="2">FIN</th>
    </tr>
    <tr class="hder pross">
        <th>Qty</th>
        <th>Tgl</th>
        <th>Qty</th>
        <th>Tgl</th>
        <th>Qty</th>
        <th>Tgl</th>
        <th>Qty</th>
        <th>Tgl</th>
        <th>Qty</th>
        <th>Tgl</th>
        <th>Qty</th>
        <th>Tgl</th>
        <th>Qty</th>
        <th>Tgl</th>
    </tr>
    </thead>
    <?php
    $projectNameSql = "SELECT MD.PROFILE, MD.LENGTH,MD.SURFACE,MD.COMP_TYPE, MDA.* "
            . " FROM MASTER_DRAWING_ASSIGNED MDA INNER JOIN MASTER_DRAWING MD ON MD.HEAD_MARK=MDA.HEAD_MARK "
            . " AND MD.PROJECT_NAME=MDA.PROJECT_NAME "
            . " WHERE MDA.PROJECT_NAME='$projName' AND MDA.SUBCONT_ID='$subcont' "
            . " AND ASSIGNMENT_DATE >= TO_DATE('$date1 $time1', 'MM/DD/YYYY hh24:mi:ss') "
            . " AND ASSIGNMENT_DATE <= TO_DATE ('$date1 23:59:59', 'MM/DD/YYYY hh24:mi:ss') "
            . " AND MD.DWG_STATUS='ACTIVE' "
            . " ORDER BY MD.COMP_TYPE , TO_NUMBER(REGEXP_REPLACE(MDA.HEAD_MARK,'[a-zA-Z''-]',''))";
    $projectNameParse = oci_parse($conn, $projectNameSql);
    oci_execute($projectNameParse);
    ?>
    <tbody>
        <?php
        $i = 0;
        $TOT_SURF_TOT = 0;
        while ($row = oci_fetch_array($projectNameParse)) {
            $i++;
            $TOT_SURF = $row['ASSIGNED_QTY'] * $row['SURFACE'];
            $date_Assg = new dateTime($row['ASSIGNED_DUE_DATE']);
            ?>
            <tr>
                <td><?php echo $i ?></td>                                        
                <td><?php echo $row['HEAD_MARK'] ?></td> 
                <td><?php echo $row['PROFILE'] ?></td>
                <td><?php echo number_format($row['LENGTH'], 0); ?></td>
                <td><?php echo $row['ASSIGNED_QTY']; ?></td>
                <td><?php echo number_format($row['SURFACE'], 2); ?></td>
                <td><?php echo number_format($TOT_SURF, 2); ?></td>
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
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php
            $TOT_SURF_TOT += $TOT_SURF;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align:right;" colspan="6"><b>Total : </b></td>
            <td><b><?php echo number_format($TOT_SURF_TOT, 2) ?></b></td>
            <td colspan="14">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2">KETERANGAN : </td>
            <td colspan="19">** Qty Di Isi Jumlah Item Yang Sudah Di QC Setiap Tahap Produksi</td>
        </tr>
        <tr>
            <td colspan="19">** Apabila Belum Ada Yang Selesai Sama Sekali Namun Sudah Mulai Proses, Di Beri Tanda (<b>.</b>)</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">Subkon<br><br><br><br>(&nbsp;<?php echo strtolower($subcont) ?>&nbsp;)</td>
            <td colspan="2" style="text-align:center;">SPV. Fabrikasi<br><br><br><br>(&nbsp;<?php echo strtolower($SPV) ?>&nbsp;)</td>
            <td colspan="4" style="text-align:center;">QC. Inspector<br><br><br><br>(&nbsp;<?php echo strtolower($QC_INSP) ?>&nbsp;)</td>
            <td colspan="3" style="text-align:center;">Dibuat Tanggal<br><?php echo date("d/m/Y") ?><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="4" style="text-align:center;">PPIC Division<br><br><br><br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;........&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            <td colspan="6">Foto Lengkap<br>[ &nbsp; ] Ya<br>[ &nbsp; ] Tidak</td>
        </tr>
    </tfoot>
    </table>
<?php endif ?>
</body>
</html>    
