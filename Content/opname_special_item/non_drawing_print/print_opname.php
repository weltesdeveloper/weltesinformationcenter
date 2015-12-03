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
                    font-size: 13px !important;
                    font-family: times new roman !important;
                }
                .table > tbody >tr >td{
                    font-size: 12px !important;
                    font-family: times new roman !important;
                }

                .table > tfoot >tr >th{
                    font-size: 13px !important;
                    font-family: times new roman !important;
                }

                .label-print{
                    font-size: 13px !important;
                    font-family: times new roman !important;
                }

                .form-group{
                    margin-bottom: 1px;
                }

                .judul-opname{
                    font-size: 18px !important;
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
                OPNAME HASIL PEKERJAAN
            </div>
            <div class="col-sm-2 text-right title">
                <i>Opname Special Drawing</i>                
                <br>
                <i>Printed By : <?= $username ?></i>
                <br>
                <i>on : <?= date("d-m-Y h:m:s") ?></i>
            </div>
        </form>
        <?php
        $querySql = "SELECT DISTINCT PROJECT_NO, PROJECT_NAME_NEW, SUBCONT_ID, OPN_PERIOD, "
                . "TO_CHAR(OPN_ACT_DATE, 'DD-MON-YYYY') OPN_ACT_DATE, OPN_TYPE "
                . "FROM VW_INFO_OPNAME_SI WHERE OPNAME_ID = '$opname_id'";
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
                            : <?php echo $row1['PROJECT_NAME_NEW']; ?>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label class="control-label col-sm-3 label-print" style="text-align:left;">
                            SUBJOB
                        </label>
                        <label class="control-label col-sm-9 label-print" style="text-align:left;">
                            : <?php echo $row1['PROJECT_NO']; ?>
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
                            : <?php echo "SPESIAL DRAWING"; ?>
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
                            <th class="text-center header-opn" style="vertical-align: middle;">NO</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">HEAD MARK</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">PROFILE</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">DWG<br>QTY</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">LENGTH</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">DWG<br>ASSG</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">QC PASS <br>QTY</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">QC PASS <br>DATE</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">QTY <br>OPNAME</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">UNIT <br>WEIGHT</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">UNIT <br>PRICE</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">PROCEN<br>WEIGHT</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">TOTAL <br>WEIGHT</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">TOTAL <br>PRICE</th>
                            <th class="text-center header-opn" style="vertical-align: middle;">REMARK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM VW_INFO_OPNAME_SI WHERE OPNAME_ID = '$opname_id'";
//                        echo "$sql";
                        $parse = oci_parse($conn, $sql);
                        oci_execute($parse);
                        $i = 1;
                        $total_qty = 0;
                        $total_unit_weight = 0;
                        $total_weight = 0;
                        $total_unit_price = 0;
                        $total_price = 0;
                        while ($row = oci_fetch_array($parse)) {
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo "$i"; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['HEAD_MARK']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['PROFILE']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['TOTAL_QTY']; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $row['LENGTH']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['OPN_ACT_DATE']; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['TOTAL_QTY']; ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    echo $row['OPN_ACT_DATE'];
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['TOTAL_QTY']; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['WEIGHT'], 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['OPN_PRICE'], 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['PROCEN_WEIGHT']) . "%"; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['WEIGHT'] * $row['TOTAL_QTY'] * $row['PROCEN_WEIGHT'], 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($row['OPN_PRICE'] * $row['WEIGHT'] * $row['TOTAL_QTY'] * $row['PROCEN_WEIGHT'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $row['REMARK']; ?>
                                </td>
                            </tr>
                            <?php
                            $total_qty += $row['TOTAL_QTY'];
                            $total_unit_weight += $row['WEIGHT'];
                            $total_weight += ($row['WEIGHT'] * $row['TOTAL_QTY']*$row['PROCEN_WEIGHT']/100);
                            $total_unit_price += $row['OPN_PRICE'];
                            $total_price += ($row['OPN_PRICE'] * $row['WEIGHT'] * $row['TOTAL_QTY']*$row['PROCEN_WEIGHT']/100);
                            $i++;
                        }
                        ?>
                        <tr>
                            <th class="text-center footer-table" style="vertical-align: middle;" colspan="9">
                                SUMMARY
                            </th>
                            <th class="text-center footer-table" style="vertical-align: middle;">
                                <?php echo number_format($total_qty, 0); ?>
                            </th>
                            <th class="text-center footer-table" style="vertical-align: middle;">
                                <?php // echo number_format($total_unit_weight, 2); ?>
                            </th>
                            <th class="text-center footer-table" style="vertical-align: middle;">
                                <?php // echo number_format($total_unit_price, 2); ?>
                            </th>
                            <th class="text-right footer-table" style="vertical-align: middle;">
                                <?php echo number_format($total_weight, 2); ?>
                            </th>
                            <th class="text-right footer-table" style="vertical-align: middle;">
                                <?php echo number_format($total_price, 2); ?>
                            </th>
                            <th class="text-center footer-table" style="vertical-align: middle;">
                                <?php echo "~~"; ?>
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
