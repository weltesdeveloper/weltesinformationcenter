<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$opname_id = $_GET['opname_id']
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PRINT OPNAME</title>
        <link rel="stylesheet" href="../../../css/bootstrap.css">
        <style>
            @media print, screen{
                .table > thead > tr > th, 
                .table > tbody > tr > th, 
                .table > tfoot > tr > th, 
                .table > thead > tr > td, 
                .table > tbody > tr > td, 
                .table > tfoot > tr > td{
                    padding: 6px;
                }

                .table-bordered > thead > tr > th, 
                .table-bordered > tbody > tr > th, 
                .table-bordered > tfoot > tr > th, 
                .table-bordered > thead > tr > td, 
                .table-bordered > tbody > tr > td, 
                .table-bordered > tfoot > tr > td{
                    border: 1px solid black !important;
                }

                .table > thead >tr >th{
                    font-size: 12px !important;
                    font-family: times new roman !important;
                }
                .table > tbody >tr >td{
                    font-size: 10px !important;
                    font-family: times new roman !important;
                }

                .table > tfoot >tr >th{
                    font-size: 12px !important;
                    font-family: times new roman !important;
                }

                .label-print{
                    font-size: 12px !important;
                    font-family: times new roman !important;
                }

                .form-group{
                    margin-bottom: 1px;
                }

                .judul-opname{
                    font-size: 16px !important;
                    font-family: times new roman !important;
                    font-weight: bolder;
                }

                .footer-table{
                    font-weight: bolder;
                    font-size: 13px !important;
                    font-family: times new roman !important;
                }

                .title{
                    font-size: 8px;
                    font-family: comic sans ms;
                }
            }
        </style>
    </head>
    <body onload="window.print(); window.close()">
        <form class="form-horizontal" style="margin-left: 10px;">
            <div class="col-sm-10 text-center judul-opname">
                OPNAME HASIL PEKERJAAN PT WELTES ENERGI NUSANTARA
            </div>
            <div class="col-sm-2 text-right title">
                <i>Opname Normal Drawing</i>                
                <br>
                <i>Printed By : <?= $username ?></i>
                <br>
                <i>on : <?= date("d-m-Y h:m:s") ?></i>
            </div>
        </form>
        <?php
        $querySql = "SELECT DISTINCT PROJECT_NO, PROJECT_NAME_NEW, SUBCONT_ID, OPN_PERIOD, "
                . "TO_CHAR(OPN_ACT_DATE, 'DD-MON-YYYY') OPN_ACT_DATE "
                . "FROM VW_INFO_OPNAME_FAB WHERE OPNAME_ID = '$opname_id'";
        $queryParse = oci_parse($conn, $querySql);
        oci_execute($queryParse);
        while ($row1 = oci_fetch_array($queryParse)) {
            ?>
            <form class="form-horizontal" style="margin-left: 10px;">
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 label-print" style="text-align:left;">
                            OPNAME NO
                        </label>
                        <label class="control-label col-sm-9 label-print" style="text-align:left;">
                            : <?php echo $opname_id; ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 label-print" style="text-align:left;">
                            JOB
                        </label>
                        <label class="control-label col-sm-9 label-print" style="text-align:left;">
                            : <?php echo $row1['PROJECT_NO']; ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 label-print" style="text-align:left;">
                            SUBJOB
                        </label>
                        <label class="control-label col-sm-9 label-print" style="text-align:left;">
                            : <?php echo $row1['PROJECT_NAME_NEW']; ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 label-print" style="text-align:left;">
                            SUBCONT
                        </label>
                        <label class="control-label col-sm-9 label-print" style="text-align:left;">
                            : <?php echo $row1['SUBCONT_ID']; ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 label-print" style="text-align:left;">
                            KETERANGAN 
                        </label>
                        <label class="control-label col-sm-9 label-print" style="text-align:left;">
                            : <?php echo "OPNAME DRAWING"; ?>
                        </label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <label class="control-label col-sm-12 label-print" style="text-align:right;">
                            PERIODE : <?php echo $row1['OPN_PERIOD'] . "/" . $row1['OPN_ACT_DATE']; ?>
                        </label>
                    </div>
                </div>

            </form>
            <?php
        }
        ?>
        <form class="form-horizontal" style="margin-left: 10px;">
            <div class="form-group" style="margin-left: 10px; margin-right: 10px; padding-top: 10px;">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">NO</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">HEAD MARK</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">PROFILE</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">LENGTH</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">UNIT <br>WEIGHT</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">DWG<br>QTY</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">DWG<br>ASSG</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">QC PASS <br>QTY</th>
                        <th rowspan="2" class="text-center header-opn" style="vertical-align: middle;">QC PASS <br>DATE</th>
                        <th colspan="2" class="text-center header-opn" style="vertical-align: middle;">LAST PERIOD</th>
                        <th colspan="6" class="text-center header-opn" style="vertical-align: middle;">THIS PERIOD</th>
                        </tr>
                        <tr>
                        <th class="text-center header-opn" style="vertical-align: middle;">% <br>WEIGHT</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">TOTAL <br>PRICE</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">QTY <br>OPNAME</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">UNIT <br>PRICE</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">% <br>WEIGHT</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">TOTAL <br>WEIGHT</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">TOTAL <br>PRICE</th>
                        <th class="text-center header-opn" style="vertical-align: middle;">REMARK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "WITH DWG
                                    AS (  SELECT MDA.HEAD_MARK,
                                                 MDA.SUBCONT_ID,
                                                 SUM (MDA.ASSIGNED_QTY) ASSIGNED_QTY,
                                                 MAX (MDA.ASSIGNMENT_DATE) ASSIGNMENT_DATE
                                            FROM MASTER_DRAWING_ASSIGNED MDA
                                                 INNER JOIN MASTER_DRAWING MD ON MD.HEAD_MARK = MDA.HEAD_MARK
                                        GROUP BY MDA.HEAD_MARK, MDA.SUBCONT_ID)
                               SELECT VROP.*, DWG.ASSIGNED_QTY, DWG.ASSIGNMENT_DATE
                                 FROM VW_REPORT_OPNAME_PRICE VROP
                                      INNER JOIN
                                      DWG
                                         ON     DWG.SUBCONT_ID = VROP.SUBCONT_ID
                                            AND DWG.HEAD_MARK = VROP.HEAD_MARK
                                WHERE OPNAME_ID = '$opname_id'";
//                        echo "$sql"; 
                        $parse = oci_parse($conn, $sql);
                        oci_execute($parse);
                        $i = 1;
                        $total_weight = 0;
                        $total_price = 0;
                        while ($row = oci_fetch_array($parse)) {
                            $weight = $row['UNIT_WEIGHT'] * $row['QTY_OPNAME'] * $row['PROCEN_WEIGHT'] / 100;
                            $price = $row['UNIT_WEIGHT'] * $row['QTY_OPNAME'] * $row['PROCEN_WEIGHT'] / 100 * $row['PRICE'];
                            ?>
                            <tr>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo "$i"; ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['HEAD_MARK']; ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['PROFILE'] . " (" . $row['DWG_TYP'] . ")"; ?>
                            </td>
                            <td class="text-right" style="vertical-align:middle;">
                                <?php echo $row['LENGTH']; ?>
                            </td>
                            <td class="text-right" style="vertical-align:middle;">
                                <?php echo number_format($row['UNIT_WEIGHT'], 2); ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['ASSIGNED_QTY']; ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['ASSIGNMENT_DATE']; ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['QCPASS']; ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php
                                if ($row['QCPASSDATE'] != "")
                                    echo $row['QCPASSDATE'];
                                else
                                    echo "-";
                                ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php
                                if ($row['PROCEN_WEIGHT'] == "100") {
                                    echo "-";
                                } else {
                                    $prosentaseSebelumnya = "SELECT SUM(PROCEN_WEIGHT) FROM VW_INFO_OPNAME_SI WHERE HEAD_MARK = '$row[HEAD_MARK]' AND SUBCONT_ID = '$row[SUBCONT_ID]'";
                                    $xx = SingleQryFld($prosentaseSebelumnya, $conn);
                                    echo "$xx%";
                                }
                                ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php
                                if ($row['PROCEN_WEIGHT'] == "100") {
                                    echo "-";
                                } else {
                                    $hargaSebelumnya = "SELECT SUM(PROCEN_WEIGHT*TOTAL_QTY*WEIGHT/100*OPN_PRICE) FROM VW_INFO_OPNAME_SI WHERE HEAD_MARK = '$row[HEAD_MARK]' AND SUBCONT_ID = '$row[SUBCONT_ID]'";
                                    $yy = SingleQryFld($hargaSebelumnya, $conn);
                                    echo number_format($yy, 2);
                                }
                                ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['QTY_OPNAME']; ?>
                            </td>
                            <td class="text-right" style="vertical-align:middle;">
                                <?php echo number_format($row['PRICE'], 2); ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php echo $row['PROCEN_WEIGHT'] . "%"; ?>
                            </td>
                            <td class="text-right" style="vertical-align:middle;">
                                <?php echo number_format($weight, 2); ?>
                            </td>
                            <td class="text-right" style="vertical-align:middle;">
                                <?php echo number_format($price, 2); ?>
                            </td>
                            <td class="text-center" style="vertical-align:middle;">
                                <?php
                                if ($row['PROCEN_WEIGHT'] == '100') {
                                    echo 'Progress 100%';
                                } else {
                                    echo "Tambahan Progress  $row[PROCEN_WEIGHT]%";
                                }
                                ?>
                            </td>
                            </tr>
                            <?php
                            $total_weight += $weight;
                            $total_price += $price;
                            $i++;
                        }
                        ?>
                        <tr>
                        <th class="text-center footer-table" style="vertical-align: middle;" colspan="14">
                            SUMMARY
                        </th>
                        <th class="text-right footer-table" style="vertical-align: middle;">
                            <?php echo number_format($total_weight, 2); ?>
                        </th>
                        <th class="text-right footer-table" style="vertical-align: middle;">
                            <?php echo number_format($total_price, 2); ?>
                        </th>
                        <th class="text-center footer-table" style="vertical-align: middle;">
                            <?php echo "~"; ?>
                        </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-12 footer-table">
                <div class="col-sm-4 text-center footer-table">
                    DIAJUKAN OLEH
                </div>
                <div class="col-sm-4 text-center footer-table">
                    DIPERIKSA
                </div>
                <div class="col-sm-4 text-center footer-table">
                    DISETUJUI
                </div>
            </div>
            <br><br><br>
            <div class="col-sm-12">
                <div class="col-sm-4 text-center footer-table">
                    SUBCONT:
                </div>
                <div class="col-sm-4 text-center footer-table">
                    PPIC:
                </div>
                <div class="col-sm-4 text-center footer-table">
                    TGL:
                </div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-4 text-center footer-table">
                    TGL:
                </div>
                <div class="col-sm-4 text-center footer-table">
                    TGL:
                </div>
                <div class="col-sm-4 text-center footer-table">

                </div>
            </div>
        </form>
    </body>
</html>
