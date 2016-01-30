<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
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

if (isset($_POST['cd-dropdown']))
    $_SESSION['cd-dropdown'] = $_POST['cd-dropdown'];
date_default_timezone_set('Asia/Jakarta'); //CDT
$current_date = date('H:i:s');

//error_reporting(E_ALL);
header("Content-type: application/octet-stream");
$formattedFileName = date("m/d/Y_h:i", time());
$job = urldecode($_GET['job']);
if ($job == "ALL") {
    $job = '%';
}
$subjob = urldecode($_GET['subjob']);
if ($subjob == "[ALL]") {
    $subjob = '%';
}

$start = $_GET['start'];
$end = $_GET['end'];
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Disposition: attachment;filename="GeneralReportFor ' . $job . '_' . $subjob . '-' . $start . '-' . $end . '.xls"');
header("Pragma: no-cache");
header("Expires: 0");
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo "DELIVERY REPORT FOR $job AND SUBJOB $subjob PERIODE $start~$end"; ?></h3>
            </div><!-- /.box-header -->
            <div>
                <table id="example1" border="1">
                    <thead>                        
                        <tr>
                        <th style="vertical-align: middle; text-align:center">JOB</th>
                        <th style="vertical-align: middle; text-align:center">SUBJOB</th>
                        <th style="vertical-align: middle; text-align:center">DO NUMBER</th>
                        <th style="vertical-align: middle; text-align:center">COLI NUMBER</th>
                        <th style="vertical-align: middle; text-align:center">HEAD MARK</th>
                        <th style="vertical-align: middle; text-align:center">COMP TYPE</th>
                        <th style="vertical-align: middle; text-align:center">PANJANG(mm)</th>
                        <th style="vertical-align: middle; text-align:center">LEBAR(mm)</th>
                        <th style="vertical-align: middle; text-align:center">TINGGI(mm)</th>
                        <th style="vertical-align: middle; text-align:center">VOLUME(m<sup>3</sup>)</th>
                        <th style="vertical-align: middle; text-align:center">UNIT PCK</th>
                        <th style="vertical-align: middle; text-align:center">WEIGHT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT DISTINCT DO_NO, "
                                . "DO_DATE, "
                                . "PO_NO, "
                                . "VHC_NO, "
                                . "T_PORTER, "
                                . "DVR, "
                                . "SPK_NO, "
                                . "PROJECT_NO, "
                                . "PROJECT_NAME_NEW "
                                . "FROM VW_DELIV_INFO  "
                                . "WHERE TO_DATE (TO_CHAR (DO_DATE, 'MM/DD/YYYY'), 'MM/DD/YYYY') "
                                . "BETWEEN TO_DATE ('$start','MM/DD/YYYY') "
                                . "AND TO_DATE ('$end', 'MM/DD/YYYY') "
                                . "AND PROJECT_NO LIKE '$job' "
                                . "AND PROJECT_NAME_NEW LIKE '$subjob' "
                                . "ORDER BY DO_NO ";
                        $parse = oci_parse($conn, $sql);
                        oci_execute($parse);
                        while ($row = oci_fetch_array($parse)) {
                            ?>
                            <tr>
                            <td style="background-color: #9acfea;">
                                <?php echo $row['PROJECT_NO']; ?>
                            </td>
                            <td style="background-color: #9acfea;">
                                <?php echo $row['PROJECT_NAME_NEW']; ?>
                            </td>
                            <td colspan="10" style="background-color: #9acfea; text-align: left">
                                <?php echo $row['DO_NO']; ?>
                            </td>
                            </tr>

                            <?php
                            $coli_sql = "SELECT DISTINCT COLI_NUMBER, "
                                    . "PACK_LEN, "
                                    . "PACK_HT, "
                                    . "PACK_WID, "
                                    . "PACK_VOL AS PACK_VOL, "
                                    . "PROJECT_NO, "
                                    . "PROJECT_NAME_NEW "
                                    . "FROM VW_DELIV_INFO "
                                    . "WHERE DO_NO = '$row[DO_NO]'";
                            $coli_parse = oci_parse($conn, $coli_sql);
                            oci_execute($coli_parse);
                            while ($row1 = oci_fetch_array($coli_parse)) {
                                ?>
                                <tr>
                                <td><?php echo $row1['PROJECT_NO']; ?></td>
                                <td><?php echo $row1['PROJECT_NAME_NEW']; ?></td>
                                <td></td>
                                <td colspan="3" style="background-color: wheat"><?php echo $row1['COLI_NUMBER']; ?></td>
                                <td style="background-color: wheat"><?php echo number_format($row1['PACK_LEN'], 2); ?></td>
                                <td style="background-color: wheat"><?php echo number_format($row1['PACK_WID'], 2); ?></td>
                                <td style="background-color: wheat"><?php echo number_format($row1['PACK_HT'], 2); ?></td>
                                <td style="background-color: wheat"><?php echo number_format($row1['PACK_VOL'] / 1000000000, 2); ?></td>
                                <td colspan="2" style="background-color: wheat"></td>

                                </tr>
                                <?php
                                $hm_sql = "SELECT "
                                        . "HEAD_MARK, "
                                        . "COMP_TYPE, "
                                        . "UNIT_PCK_QTY, "
                                        . "UNIT_PCK_WT, "
                                        . "PROJECT_NO, "
                                        . "PROJECT_NAME_NEW "
                                        . "FROM VW_DELIV_INFO "
                                        . "WHERE COLI_NUMBER='$row1[COLI_NUMBER]' "
                                        . "AND DO_NO = '$row[DO_NO]' "
                                        . "ORDER BY HEAD_MARK ASC";
                                $hm_parse = oci_parse($conn, $hm_sql);
                                oci_execute($hm_parse);
                                while ($row2 = oci_fetch_array($hm_parse)) {
                                    ?>
                                    <tr>
                                    <td><?php echo $row2['PROJECT_NO']; ?></td>
                                    <td><?php echo $row2['PROJECT_NAME_NEW']; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $row2['HEAD_MARK']; ?></td>
                                    <td><?php echo $row2['COMP_TYPE']; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo number_format($row2['UNIT_PCK_QTY'], 2); ?></td>
                                    <td><?php echo number_format($row2['UNIT_PCK_WT'], 2); ?></td>

                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>