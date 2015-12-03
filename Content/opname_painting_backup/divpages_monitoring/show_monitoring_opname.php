<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();
if (!isset($_SESSION['username'])) {
    echo <<< EOD
       <h1>You are UNAUTHORIZED !</h1>
       <p>INVALID usernames/passwords<p>
       <p><a href="/WeltesinformationCenter/index.php">LOGIN PAGE</a><p>
EOD;
    exit;
}
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
oci_set_client_identifier($conn, $_SESSION['username']);
//$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$periode = $_POST['periode'];
$type = $_POST['type'];
$job = $_POST['job'];
$subjob = $_POST['subjob'];
if ($type == "%") {
    $xxx = "PAINT & BLAST";
} ELSE {
    $xxx = $type;
}
if ($periode == "%") {
    $yyy = "ALL";
} else {
    $yyy = $periode;
}
?>
<div class="col-sm-12 text-center" style="background-color: #AABBCB; font-family: monospace; font-size: 20px; font-style: italic;font-weight: bold">
    <?php echo "REPORT OPNAME $xxx PERIODE $yyy"; ?>
</div>
<br><br>
<table class="table table-bordered table-condensed" id="table-monitoring">
    <thead>
        <tr>
            <!--<th class="text-center" style="vertical-align: middle;">PROJECT NO</th>-->
            <!--<th class="text-center" style="vertical-align: middle;">PROJECT NAME</th>-->
            <th class="text-center" style="vertical-align: middle;">OPNAME ID</th>
            <th class="text-center" style="vertical-align: middle;">OPNAME TYPE</th>
            <th class="text-center" style="vertical-align: middle;">HEAD<br>MARK</th>
            <th class="text-center" style="vertical-align: middle;">COMP<br>TYPE</th>
            <th class="text-center" style="vertical-align: middle;">PROFILE</th>            
            <th class="text-center" style="vertical-align: middle;">LENGTH<br>(m)</th>
            <th class="text-center" style="vertical-align: middle;">SURFACE<br>(m<sup>2</sup>)</th>
            <th class="text-center" style="vertical-align: middle;">OPNAME<br>QTY</th>
            <th class="text-center" style="vertical-align: middle;">OPNAME<br>PRICE</th>
            <th class="text-center" style="vertical-align: middle;">TOTAL<br>PRICE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM VW_REPORT_OPNAME_PNT "
                . "WHERE OPNAME_TYPE LIKE '$type' "
                . "AND OPNAME_PERIOD LIKE '%$periode' "
                . "AND PROJECT_NO LIKE '%$job' "
                . "AND PROJECT_NAME_NEW LIKE '%$subjob' "
                . "ORDER BY COMP_TYPE";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $total_area = 0;
        $total_opname = 0;
        $total_price = 0;
        $total_bayar = 0;
        while ($row = oci_fetch_array($parse)) {
            ?>
            <tr>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['OPNAME_ID']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['OPNAME_TYPE']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['HEAD_MARK']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['COMP_TYPE']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['PROFILE']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['LENGTH']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo number_format($row['SURFACE'], 2); ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo $row['OPNAME_QTY']; ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo "Rp." . number_format($row['OPNAME_PRICE'], 2); ?></td>
                <td class="text-center" style="vertical-align: middle;"><?php echo "Rp." . number_format($row['OPNAME_PRICE'] * $row['OPNAME_QTY'] * $row['SURFACE'], 2); ?></td>
            </tr>
            <?php
            $total_area += $row['SURFACE'];
            $total_opname += $row['OPNAME_QTY'];
            $total_price += $row['OPNAME_PRICE'];
            $total_bayar += $row['OPNAME_PRICE'] * $row['OPNAME_QTY'] * $row['SURFACE'];
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center" style="vertical-align: middle;" colspan="6">SUMMARY</th>
            <th class="text-center" style="vertical-align: middle;"><?php echo number_format($total_area, 2); ?> m<sup>2</sup></th>
            <th class="text-center" style="vertical-align: middle;"><?php echo number_format($total_opname, 2); ?> Pcs</th>
            <th class="text-center" style="vertical-align: middle;"><?php echo "Rp." . number_format($total_price, 2); ?></th>
            <th class="text-center" style="vertical-align: middle;"><?php echo "Rp." . number_format($total_bayar, 2); ?></th>
        </tr>
    </tfoot>
</table>
<br><br>
<button type="button" class="col-sm-12 btn btn-success btn-lg" onclick="exportXls();">EXPORT TO .XLS</button>
<script>
    $(document).ready(function () {
        $('#table-monitoring').dataTable({
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "columnDefs": [
                {"visible": false, "targets": 3}
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(3, {page: 'current'}).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                                '<tr class="group"><td colspan="9" id=xxx>' + group + '</td></tr>'
                                );

                        last = group;
                    }
                });
            }
        });
    });
    function exportXls() {
        var periode = "<?php echo "$periode"; ?>";
        var type = "<?php echo "$type"; ?>";
        var job = "<?php echo "$job"; ?>";
        var subjob = "<?php echo "$subjob"; ?>";
        window.open("/WeltesInformationCenter/Content/Tools/PHPToExcel/opname_pnt_print.php?periode=" + periode + "&type=" + type + "&job=" + job + "&subjob=" + subjob);
    }
</script>
<style>
    .group{
        background-color: #F2E96D;
        /*color: #D9AE5F;*/
        font-weight: bold;
        font-size: 15px;
        font-style: italic;
    }

    .subgroup{
        background-color: #D9AE5F;
        /*color: #D9A7B0;*/
        font-weight: bold;
    }

    #xxx{
        background-color: #23BAFF;
        font-size: 14px;
        font-weight: bolder;
    }
</style>