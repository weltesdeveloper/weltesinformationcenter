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
if ($dlv_stat == 'FINISH') {
    $dlv_sql = " HAVING SUM (DLV_QTY) = TOTAL_QTY ";
} elseif ($dlv_stat == 'UNFINISH') {
    $dlv_sql = " HAVING SUM (DLV_QTY) <> TOTAL_QTY ";
}
$erc_sql = "";
if ($erc_stat == 'FINISH') {
    $erc_sql = "  AND SUM(ERC_QC) = TOTAL_QTY ";
} elseif ($erc_stat == 'UNFINISH') {
    $erc_sql = "  AND SUM(ERC_QC) <> TOTAL_QTY ";
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
                            <th>HEADMARK</th>
                            <th>COMP</th>
                            <th>PROFILE</th>
                            <th>TOT. QTY</th>
                            <th>NETT WEIGHT<br>(Pcs)</th>
                            <th>GROSS WEIGHT<br>(Pcs)</th>
                            <th>TOT. SURF</th>
                            <th>TOT. NETT WT</th>
                            <th>TOT. GROSS WT</th>
                            <th>UNIT. LENGTH</th>
                            <th>ASG. QTY</th>
                            <th>SUBCON (qty)</th>
                            <th>SPV FAB</th>
                            <th>QC INSP</th>
                            <th>MARK</th>
                            <th>CUTT</th>
                            <th>ASSY</th>
                            <th>WELD</th>
                            <th>DRIL</th>
                            <th>FAB FIN</th>
                            <th>FAB FIN DATE</th>
                            <th>FAB QC</th>
                            <th>BLA</th>
                            <th>PRI</th>
                            <th>INT</th>
                            <th>PNT FIN</th>
                            <th>PNT QC</th>
                            <th>PACK QTY</th>
                            <th>PACK INFO</th>
                            <th>DO NO</th>
                            <th>STATUS DELIVERY</th>
                            <th>STATUS ERECTION</th>
                            <th>OPNAME FABRICATION</th>
                            <th>OPNAME PAINTING</th>
                            <th>ASSIG DATE</th>
                            <th>ACTUAL FINS DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //query mysql, ganti baris ini sesuai dengan query kamu
//                        $generalInfoSql = "WITH gen AS (  SELECT DISTINCT (HEAD_MARK),"
//                                . " PROJECT_NAME,COMP_TYPE,PROFILE,TOTAL_QTY,WEIGHT,GR_WEIGHT,SURFACE,LENGTH,SUM (DLV_QTY) DLV_QTY,SUM (ERC_QC) ERC_QC,"
//                                . " MAX(ASG_DATE) ASG_DATE "
//                                . " FROM GEN_REPORT GR GROUP BY (HEAD_MARK),PROJECT_NAME,COMP_TYPE,PROFILE,TOTAL_QTY,WEIGHT,GR_WEIGHT,SURFACE,LENGTH) "
//                                . " SELECT * FROM gen WHERE PROJECT_NAME = :PROJNAME $dlv_sql $erc_sql"
//                                . " ORDER BY COMP_TYPE, TO_NUMBER (REGEXP_REPLACE (HEAD_MARK, '[^[:digit:]]', NULL))";

                        $generalInfoSql = "WITH aa
                                                AS (SELECT DISTINCT (HEAD_MARK),
                                                                    PROJECT_NAME,
                                                                    COMP_TYPE,
                                                                    PROFILE,
                                                                    TOTAL_QTY,
                                                                    WEIGHT,
                                                                    GR_WEIGHT,
                                                                    SURFACE,
                                                                    LENGTH,
                                                                    ASG_DATE,
                                                                    DLV_QTY,
                                                                    ERC_QC
                                                      FROM GEN_REPORT GR)
                                             SELECT head_mark,
                                                    PROJECT_NAME,
                                                    COMP_TYPE,
                                                    PROFILE,
                                                    TOTAL_QTY,
                                                    WEIGHT,
                                                    GR_WEIGHT,
                                                    SURFACE,
                                                    LENGTH,
                                                    SUM (DLV_QTY) DLV_QTY,
                                                    SUM (ERC_QC) ERC_QC,
                                                    MAX (ASG_DATE) ASG_DATE
                                               FROM aa
                                              WHERE PROJECT_NAME = :PROJNAME
                                           GROUP BY head_mark,
                                                    PROJECT_NAME,
                                                    COMP_TYPE,
                                                    PROFILE,
                                                    TOTAL_QTY,
                                                    WEIGHT,
                                                    GR_WEIGHT,
                                                    SURFACE,
                                                    LENGTH
                                             $dlv_sql $erc_sql
                                           ORDER BY COMP_TYPE,TO_NUMBER (REGEXP_REPLACE (HEAD_MARK, '[^[:digit:]]', NULL))";
//                        echo $generalInfoSql;
                        $generalInfoParse = oci_parse($conn, $generalInfoSql);
                        oci_bind_by_name($generalInfoParse, ":PROJNAME", $projectName);
                        oci_execute($generalInfoParse);
                        while ($row = oci_fetch_array($generalInfoParse)) {

                            $ASG_qty = 0;
                            $SUBCON_info = "";
                            $SPV_info = "";
                            $QC_INSP_info = "";
                            $MARK = 0;
                            $CUTT = 0;
                            $ASSY = 0;
                            $WELD = 0;
                            $DRIL = 0;
                            $FIN_FAB = 0;
                            $TOT_FABQC = 0;
                            $BLAST = 0;
                            $PRIMER = 0;
                            $INTERM = 0;
                            $FIN_PNT = 0;
                            $TOT_PNT_SURF = 0;
                            $TOT_PNTQC = 0;
                            $FAB_FINS_DATE = "";
                            $generalInfoSql_2 = "SELECT ID,SPV_FAB,QC_INSP,SUBCONT_ID,ASSG_DATE,ASSG_QTY,MARK,CUT,ASSY,WELD,DRILL,JML_FAB,FAB_QCPASS,"
                                    . " BLAST,PRIMER,INTMD,TOP_COAT,PNT_QCPASS, TO_CHAR(FINISHING_FAB_DATE, 'DD-MONTH-YYYY') FINISHING_FAB_DATE "
                                    . " FROM COMP_VW_INFO  WHERE HEAD_MARK = '$row[HEAD_MARK]' ORDER BY HEAD_MARK";
                            $generalInfoParse_2 = oci_parse($conn, $generalInfoSql_2);
                            oci_execute($generalInfoParse_2);
                            while ($row_2 = oci_fetch_array($generalInfoParse_2)) {
                                $ASG_qty += $row_2['ASSG_QTY'];
                                $SUBCON_info .= $row_2['SUBCONT_ID'] . " (" . $row_2['ASSG_QTY'] . "), <br>";
                                $SPV_info .= $row_2['SPV_FAB'] . ", <br>";
                                $QC_INSP_info .= $row_2['QC_INSP'] . ", <br>";
                                $MARK += $row_2['MARK'];
                                $CUTT += $row_2['CUT'];
                                $ASSY += $row_2['ASSY'];
                                $WELD += $row_2['WELD'];
                                $DRIL += $row_2['DRILL'];
                                $FIN_FAB += $row_2['JML_FAB'];
                                $TOT_FABQC += $row_2['FAB_QCPASS'];
                                $BLAST += $row_2['BLAST'];
                                $PRIMER += $row_2['PRIMER'];
                                $INTERM += $row_2['INTMD'];
                                $FIN_PNT += $row_2['TOP_COAT'];
                                $TOT_PNTQC += $row_2['PNT_QCPASS'];
                                $FAB_FINS_DATE .=$row_2['FINISHING_FAB_DATE'];
                            }

                            $PCKQTY = 0;
                            $COLI_info = "";
                            $DO_info = "";
                            $jmlDO = 0;
                            $generalInfoSql_3 = "SELECT COLI_NUMBER,UNIT_PCK_QTY FROM VW_PCK_INFO  WHERE HEAD_MARK = '$row[HEAD_MARK]' ORDER BY HEAD_MARK";
                            $generalInfoParse_3 = oci_parse($conn, $generalInfoSql_3);
                            oci_execute($generalInfoParse_3);
                            $jmlPCK = oci_fetch_all($generalInfoParse_3, $output);
                            oci_execute($generalInfoParse_3);
                            while ($row_3 = oci_fetch_array($generalInfoParse_3)) {
                                $PCKQTY += $row_3['UNIT_PCK_QTY'];
                                $COLI_info .= $row_3['COLI_NUMBER'] . " (" . $row_3['UNIT_PCK_QTY'] . "), <br>";

                                $sql_do = "SELECT DISTINCT(DO_NO),DO_DATE FROM VW_DELIV_INFO WHERE COLI_NUMBER = '$row_3[COLI_NUMBER]'";
                                $parse_do = oci_parse($conn, $sql_do);
                                oci_execute($parse_do);
                                $row_do = oci_fetch_array($parse_do);

                                $DO_NO = $row_do['DO_NO'];
                                $DO_DATE = $row_do['DO_DATE'];

                                if ($DO_NO == "") {
                                    $DO_info .= "- , <br>";
                                } else {
                                    $DO_info .= $DO_NO . " ($DO_DATE), <br>";
                                    $jmlDO++;
                                }
                            }

                            $status = "UnFinish";
                            $status_row = "UnFinish";
                            if ($jmlPCK == $jmlDO and $PCKQTY == $row['TOTAL_QTY']) {
                                $status = "Finished";
                            }
                            for ($j = 0; $j < $jmlPCK; $j++) {
                                if ($j == 0) {
                                    $status_row = $status . "<br>";
                                } else {
                                    $status_row .= $status . "<br>";
                                }
                            }

                            $status_erc = "UnFinish";
                            $status_row_erc = "UnFinish";
                            if ($row['ERC_QC'] == $row['TOTAL_QTY']) {
                                $status_erc = "Finished";
                            }
                            for ($j = 0; $j < $jmlPCK; $j++) {
                                if ($j == 0) {
                                    $status_row_erc = $status_erc . "<br>";
                                } else {
                                    $status_row_erc .= $status_erc . "<br>";
                                }
                            }

                            $v = str_replace(" ", "", $row['HEAD_MARK']);
                            $perHruf = "";
                            $str_HM = "";
                            $int_HM = "";
                            for ($i = 0; $i < strlen($v); $i++) {
                                $perHruf = substr($v, $i, 1);
                                if (is_numeric($perHruf)) {
                                    $int_HM .= $perHruf;
                                } else {
                                    $str_HM .= $perHruf;
                                }
                            }
                            $fnal_HM = $str_HM . sprintf("%'#4s", $int_HM);
                            $fnal_HM = str_replace("#", "&nbsp;", $fnal_HM);
                            $OPNAME_FAB = SingleQryFld("SELECT nvl(SUM(TOTAL_QTY),0) FROM DTL_OPNAME WHERE HEAD_MARK = '$row[HEAD_MARK]'", $conn);
                            $OPNAME_PNT = SingleQryFld("SELECT nvl(SUM(OPNAME_QTY),0) FROM DTL_OPNAME_PNT WHERE HEAD_MARK = '$row[HEAD_MARK]'", $conn);
                            echo '<tr>';
                            echo '<td>' . $row['HEAD_MARK'] . '</td>';
                            echo '<td>' . $row['COMP_TYPE'] . '</td>';
                            echo '<td>' . $row['PROFILE'] . '</td>';
                            echo '<td>' . $row['TOTAL_QTY'] . '</td>';
                            echo '<td>' . $row['WEIGHT'] . '</td>';
                            echo '<td>' . $row['GR_WEIGHT'] . '</td>';
                            echo '<td>' . round($row['SURFACE'] * $row['TOTAL_QTY'], 2) . '</td>';
                            echo '<td>' . round($row['WEIGHT'] * $row['TOTAL_QTY'], 2) . '</td>';
                            echo '<td>' . round($row['GR_WEIGHT'] * $row['TOTAL_QTY'], 2) . '</td>';
                            echo '<td>' . $row['LENGTH'] . '</td>';
                            echo '<td>' . $ASG_qty . '</td>';
                            echo '<td>' . $SUBCON_info . '</td>';
                            echo '<td>' . $SPV_info . '</td>';
                            echo '<td>' . $QC_INSP_info . '</td>';
                            echo '<td>' . $MARK . '</td>';
                            echo '<td>' . $CUTT . '</td>';
                            echo '<td>' . $ASSY . '</td>';
                            echo '<td>' . $WELD . '</td>';
                            echo '<td>' . $DRIL . '</td>';
                            echo '<td>' . round($FIN_FAB) . '</td>';
                            echo '<td>' . "$FAB_FINS_DATE" . '</td>';
                            echo '<td>' . round($TOT_FABQC) . '</td>';
                            echo '<td>' . $BLAST . '</td>';
                            echo '<td>' . $PRIMER . '</td>';
                            echo '<td>' . $INTERM . '</td>';
                            echo '<td>' . round($FIN_PNT) . '</td>';
                            echo '<td>' . round($TOT_PNTQC) . '</td>';
                            echo '<td>' . $PCKQTY . '</td>';
                            echo '<td>' . $COLI_info . '</td>';
                            echo '<td>' . $DO_info . '</td>';
                            echo '<td>' . $status_row . '</td>';
                            echo '<td>' . $row['ERC_QC'] . "( $status_erc )" . '</td>';
                            echo '<td>' . "$OPNAME_FAB" . '</td>';
                            echo '<td>' . "$OPNAME_PNT" . '</td>';
                            echo '<td>' . $row['ASG_DATE'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>