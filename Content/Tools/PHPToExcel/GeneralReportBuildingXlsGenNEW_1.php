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

error_reporting(E_ALL);

$projectName = $_GET['projname'];
$dlv_stat = $_GET['dlv_stat'];
$erc_stat = $_GET['erc_stat'];
$dlv_sql = "";
$erc_sql = "";
if ($dlv_stat != "ALL") {
    $dlv_sql = " AND DLV_QTY <> TOTAL_QTY ";
}
if ($erc_stat != "ALL") {
    $erc_sql = " AND ERECT_UPD_QTY <> TOTAL_QTY ";
}

header("Content-type: application/octet-stream");
$formattedFileName = date("m/d/Y_h:i", time());
// simpan file excel dengan nama umr2013.xls
//saat file berhasil di buat, otomatis pop up download akan muncul
header('Content-Disposition: attachment;filename="GeneralReportFor ' . $projectName . '_' . $formattedFileName . '.xls"');
header("Pragma: no-cache");
header("Expires: 0");
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo 'General Information Report For ' . $projectName; ?></h3>
            </div><!-- /.box-header -->
            <div>
                <table>
                    <tr>
                        <th>Delivery Status :</th>
                        <th><?php echo $dlv_stat; ?></th>
                        <th>Erection Status :</th>
                        <th><?php echo $erc_stat; ?></th>
                    </tr>
                </table>
                <table id="example1" border="1">
                    <thead>                        
                        <tr>
                            <th style="vertical-align: middle; text-align:center">HEADMARK</th>
                            <th style="vertical-align: middle; text-align:center">COMP<br>TYPE</th>
                            <th style="vertical-align: middle; text-align:center">PROFILE</th>
                            <th style="vertical-align: middle; text-align:center">TOT. QTY</th>
                            <th style="vertical-align: middle; text-align:center">NETT WEIGHT<br>(Pcs)</th>
                            <th style="vertical-align: middle; text-align:center">GROSS WEIGHT<br>(Pcs)</th>
                            <th style="vertical-align: middle; text-align:center">TOT. SURF</th>
                            <th style="vertical-align: middle; text-align:center">TOT. NETT WT</th>
                            <th style="vertical-align: middle; text-align:center">TOT. GROSS WT</th>
                            <th style="vertical-align: middle; text-align:center">UNIT. LENGTH</th>
                            <th style="vertical-align: middle; text-align:center">TOTAL<br>ASG. QTY</th>
                            <th style="vertical-align: middle; text-align:center">SUBCON (qty)</th>
                            <th style="vertical-align: middle; text-align:center">SPV FAB</th>
                            <th style="vertical-align: middle; text-align:center">QC INSP</th>
                            <th style="vertical-align: middle; text-align:center">MARK</th>
                            <th style="vertical-align: middle; text-align:center">CUTT</th>
                            <th style="vertical-align: middle; text-align:center">ASSY</th>
                            <th style="vertical-align: middle; text-align:center">WELD</th>
                            <th style="vertical-align: middle; text-align:center">DRIL</th>
                            <th style="vertical-align: middle; text-align:center">FAB FIN</th>
                            <th style="vertical-align: middle; text-align:center">FAB FIN DATE</th>
                            <th style="vertical-align: middle; text-align:center">FAB QC</th>
                            <th style="vertical-align: middle; text-align:center">BLA</th>
                            <th style="vertical-align: middle; text-align:center">PRI</th>
                            <th style="vertical-align: middle; text-align:center">INT</th>
                            <th style="vertical-align: middle; text-align:center">PNT FIN</th>
                            <th style="vertical-align: middle; text-align:center">PNT FIN DATE</th>
                            <th style="vertical-align: middle; text-align:center">PNT QC</th>
                            <th style="vertical-align: middle; text-align:center">PACK QTY</th>
                            <th style="vertical-align: middle; text-align:center">DLV QTY</th>
                            <th style="vertical-align: middle; text-align:center">PACK INFO</th>
                            <th style="vertical-align: middle; text-align:center">DO NO</th>
                            <th style="vertical-align: middle; text-align:center">STATUS DELIVERY</th>
                            <th style="vertical-align: middle; text-align:center">ERECT QTY</th>
                            <th style="vertical-align: middle; text-align:center">STATUS ERECTION</th>
                            <th class="text-center">NOTE</th>
                            <!--<th class="text-center">OPNAME PAINTING</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $generalInfoSql = "WITH XX "
                                . "AS(SELECT "
                                . "PROJECT_NAME, "
                                . "HEAD_MARK, "
                                . "MAX (FINISHING_PAINT_DATE) FINISHING_PAINT_DATE "
                                . "FROM COMP_VW_INFO "
                                . "WHERE PROJECT_NAME = '$projectName' "
                                . "GROUP BY PROJECT_NAME, HEAD_MARK) "
                                . "SELECT VDI.*, XX.FINISHING_PAINT_DATE "
                                . "FROM VW_DRAWING_INFO VDI "
                                . "LEFT OUTER JOIN XX "
                                . "ON XX.HEAD_MARK = VDI.HEAD_MARK "
                                . "AND XX.PROJECT_NAME = VDI.PROJECT_NAME "
                                . "WHERE VDI.PROJECT_NAME = '$projectName' $dlv_sql $erc_sql "
                                . "ORDER BY VDI.COMP_TYPE,TO_NUMBER (REGEXP_REPLACE (VDI.HEAD_MARK, '[^[:digit:]]', NULL))";
//                        echo $generalInfoSql;
                        $generalInfoParse = oci_parse($conn, $generalInfoSql);
                        oci_execute($generalInfoParse);
                        while ($row = oci_fetch_array($generalInfoParse)) {
                            ?>
                            <tr>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['HEAD_MARK']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['COMP_TYPE']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['PROFILE']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['TOTAL_QTY']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['WEIGHT']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['GR_WEIGHT']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['SURFACE']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['WEIGHT']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['GR_WEIGHT']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['LENGTH']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['ASSIGNED_QTY']; ?>
                                </td>
                                <?php
                                $arraySubcont = "";
                                $spvFab = "";
                                $qcInsp = "";
                                $fabfinishdate = "";
                                $selectSubcontSql = "SELECT SUBCONT_ID, ASSG_QTY, SPV_FAB, QC_INSP, FINISHING_FAB_DATE FROM COMP_VW_INFO WHERE HEAD_MARK = '$row[HEAD_MARK]'";
                                $selectSubcontParse = oci_parse($conn, $selectSubcontSql);
                                oci_execute($selectSubcontParse);
                                while ($row1 = oci_fetch_array($selectSubcontParse)) {
                                    $arraySubcont .= $row1['SUBCONT_ID'] . "($row1[ASSG_QTY]) <br>";
                                    $spvFab .= $row1['SPV_FAB'] . "<br>";
                                    $qcInsp .= $row1['QC_INSP'] . "<br>";
                                    $fabfinishdate .= $row1['FINISHING_FAB_DATE'] . "<br>";
                                }
                                ?>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    echo $arraySubcont;
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    echo $spvFab;
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    echo $qcInsp;
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['MARKING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['CUTTING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['ASSEMBLY']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['WELDING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['DRILLING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['FAB_FINISHING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    echo $fabfinishdate;
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['FAB_QC_PASS']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['BLASTING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['PRIMER']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['INTERMEDIATE']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['PNT_FINISHING']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['FINISHING_PAINT_DATE']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['PAINT_QC_PASS']; ?>
                                </td>
                                <?php
                                $coliArray = "";
                                $doArray = "";
                                $packDlvSql = "SELECT VPI.COLI_NUMBER, VPI.UNIT_PCK_QTY, VDI.DO_NO, VDI.DO_DATE "
                                        . "FROM VW_PCK_INFO VPI LEFT OUTER JOIN "
                                        . "VW_DELIV_INFO VDI "
                                        . "ON VDI.COLI_NUMBER = VPI.COLI_NUMBER "
                                        . "AND VDI.HEAD_MARK = VPI.HEAD_MARK "
                                        . "WHERE VPI.HEAD_MARK = '$row[HEAD_MARK]'";
                                $packDlvParse = oci_parse($conn, $packDlvSql);
                                oci_execute($packDlvParse);
                                while ($row2 = oci_fetch_array($packDlvParse)) {
                                    $coliArray .= $row2['COLI_NUMBER'] . " ($row2[UNIT_PCK_QTY]) <br>";
                                    $doArray .= $row2['DO_NO'] . " ($row2[DO_DATE]) <br>";
                                }
                                ?>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['PCK_QTY']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['DLV_QTY']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    echo $coliArray;
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    echo $doArray;
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    if ($row['DLV_QTY'] == $row['TOTAL_QTY']) {
                                        echo "DLV FINISH";
                                    } else {
                                        echo "DLV NOT FINISH";
                                    }
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['ERECT_UPD_QTY']; ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php
                                    if ($row['ERECT_UPD_QTY'] == $row['TOTAL_QTY']) {
                                        echo "ERECT FINISH ~($row[ERECT_UPD_QTY])";
                                    } else if ($row['ERECT_UPD_QTY'] == 0) {
                                        echo "ERECT NOT FINISH";
                                    } else {
                                        echo "ERECT PARTIAL ~($row[ERECT_UPD_QTY])";
                                    }
                                    ?>
                                </td>
                                <td style="vertical-align: middle; text-align:center">
                                    <?php echo $row['REMARK']; ?>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>