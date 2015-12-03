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

$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);
$opname_id = $_POST['opname_id'];
$job = $_POST['job'];
$subjob = $_POST['subjob'];
$sql = "WITH OPN
     AS (SELECT HEAD_MARK,
                SUBCONT_ID,
                TOTAL_QTY,
                OPN_PRICE,
                UNIT_WEIGHT
           FROM vw_info_opname_fab
          WHERE OPNAME_ID = '$opname_id' AND PROJECT_NO = '$job' AND PROJECT_NAME = '$subjob'),
     QCPASS
     AS (  SELECT HEAD_MARK,
                  SUBCONT_ID,
                  SUM (QCPASS) QCPASS,
                  SUM (QTY_OPN) QTY_OPN,
                  SUM (REMAINING_QCPASS) REMAINING_QCPASS
             FROM VW_SHOW_OPNAME_PRC
         GROUP BY HEAD_MARK, SUBCONT_ID)
SELECT OPN.HEAD_MARK,
       OPN.SUBCONT_ID,
       OPN.TOTAL_QTY,
       OPN.OPN_PRICE,
       OPN.UNIT_WEIGHT,
       QCPASS.REMAINING_QCPASS
  FROM OPN
       INNER JOIN
       QCPASS
          ON     OPN.HEAD_MARK = QCPASS.HEAD_MARK
             AND OPN.SUBCONT_ID = QCPASS.SUBCONT_ID";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
?>
<table class="table table-striped table-bordered" id="table-revision-opn">
    <thead>
        <tr>
            <th class="text-center">HEAD MARK</th>
            <th class="text-center">WEIGHT</th>
            <th class="text-center">QTY OPNAME</th>
            <th class="text-center">REMAINING QC PASS</th>
            <th class="text-center">PRICE</th>
            <th class="text-center">TOTAL PRICE</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        while ($row = oci_fetch_array($parse)) {
            ?>
            <tr id="row<?php echo "$i"; ?>">
                <td class="text-center" id="hm<?php echo "$i"; ?>">
                    <?php echo $row['HEAD_MARK']; ?>
                </td>
                <td class="text-center" id="wt<?php echo "$i"; ?>">
                    <?php echo $row['UNIT_WEIGHT']; ?>
                </td>
                <td class="text-center" id="qty<?php echo "$i"; ?>">
                    <?php echo $row['TOTAL_QTY']; ?>
                </td>
                <td class="text-center" id="rem-qc<?php echo "$i"; ?>">
                    <?php echo $row['REMAINING_QCPASS']; ?>
                </td>
                <td class="text-center" id="price<?php echo "$i"; ?>">
                    <?php echo $row['OPN_PRICE']; ?>
                </td>
                <td class="text-center" id="total-price<?php echo "$i"; ?>">
                    <?php echo number_format($row['OPN_PRICE'] * $row['TOTAL_QTY'] * $row['UNIT_WEIGHT'], 2); ?>
                </td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="2" class="text-right"  style="background-color: #F2EDE4">Total Qty : </th>
            <th class="text-center"></th>
            <th colspan="2" class="text-right" style="background-color: #9CC1D9">Total Price : </th>
            <th class="text-center"></th>
        </tr>
    </tfoot>
</table>

<button type="button" class="btn btn-success col-sm-12" onclick="printOpname()">PRINT <?= $opname_id ?></button>

<script>
    $('#table-revision-opn').DataTable({
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            totalQty = api
                    .column(2)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    });
            totalPrice = api
                    .column(5)
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    });


            // Update footer
            $(api.column(2).footer()).html(addCommas(totalQty));
            $(api.column(5).footer()).html(addCommas(totalPrice));
        }
    });

    function printOpname(param) {
        var opnameId = "<?php echo "$opname_id"; ?>";
        var project_name = "<?php echo "$subjob"; ?>";
        var subcont = $('#subcont').val();
        window.open("../Tools/PHPToExcel/opnameDetailsXlsGen.php?opnameId=" + opnameId + "&pn=" + project_name + "&subcont=" + subcont);
    }
    
    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>

