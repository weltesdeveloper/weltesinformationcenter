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
$opname_id = "OPN-$type-$periode";
$job = $_POST['job'];
$subjob = $_POST['subjob'];
?>
<div class="col-sm-12 text-center" style="background-color: #AABBCB; font-family: monospace; font-size: 20px; font-style: italic;font-weight: bold">
    <?php echo "REVISION OPNAME $type PERIODE $periode"; ?>
</div>
<br><br>
<table class="table table-bordered table-condensed" id="table-revision">
    <thead>
        <tr>
            <th class="text-center" style="vertical-align: middle;">HEAD_MARK</th>
            <th class="text-center" style="vertical-align: middle;">COMP TYPE</th>
            <th class="text-center" style="vertical-align: middle;">PROFILE</th>
            <th class="text-center" style="vertical-align: middle;">SURFACE</th>
            <th class="text-center" style="vertical-align: middle;">QC PASS</th>
            <th class="text-center" style="vertical-align: middle;">REMAINING OPNMAE</th>
            <th class="text-center" style="vertical-align: middle;">OPNAME QTY</th>
            <th class="text-center" style="vertical-align: middle;">OPNAME_PRICE</th>
            <th class="text-center" style="vertical-align: middle;">ACTION</th>
            <th class="text-center hide" style="vertical-align: middle;">PROJECT NAME</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "WITH TOT_OPNAME
                AS (SELECT  opname_id, HEAD_MARK,
                             OPNAME_TYPE,
                             OPNAME_PERIOD,
                             SUM (OPNAME_QTY) OPNAME_QTY,
                             SUM (OPNAME_PRICE) OPNAME_PRICE
                        FROM VW_OPNAME_PNT
                       WHERE OPNAME_TYPE = '$type'
                             AND OPNAME_PERIOD = '$periode'
                             AND PROJECT_NO = '$job' AND PROJECT_NAME_NEW = '$subjob' 
                    GROUP BY OPNAME_ID, HEAD_MARK, OPNAME_TYPE, OPNAME_PERIOD)
                SELECT TOP.OPNAME_ID, CVI.PROJECT_NAME, CVI.HEAD_MARK,
                    MD.SURFACE,
                    MD.PROFILE,
                    MD.COMP_TYPE,
                    OPNAME_PRICE,
                    TOP.OPNAME_QTY,
                    SUM (CVI.PNT_QCPASS) PNT_QCPASS,
                    SUM (CVI.PNT_QCPASS) - TOP.OPNAME_QTY REMAINING_OPNAME
               FROM COMP_VW_INFO CVI
                    INNER JOIN TOT_OPNAME TOP ON TOP.HEAD_MARK = CVI.HEAD_MARK
                    INNER JOIN MASTER_DRAWING MD ON CVI.HEAD_MARK = MD.HEAD_MARK
                GROUP BY TOP.OPNAME_ID, CVI.PROJECT_NAME, CVI.HEAD_MARK,
                    MD.SURFACE,
                    MD.PROFILE,
                    MD.COMP_TYPE,
                    OPNAME_PRICE,
                    TOP.OPNAME_QTY ORDER BY CVI.PROJECT_NAME, MD.COMP_TYPE, CVI.HEAD_MARK";
//        echo $sql;
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $i = 0;
        while ($row = oci_fetch_array($parse)) {
            ?>
            <tr id="row<?php echo "$i"; ?>">
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo $row['HEAD_MARK']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo $row['COMP_TYPE']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo $row['PROFILE']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo $row['SURFACE']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo $row['PNT_QCPASS']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <?php echo $row['REMAINING_OPNAME']; ?>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <input class="form-control" type="number" id="opname-qty<?php echo "$i"; ?>" value="<?php echo $row['OPNAME_QTY']; ?>" 
                           min="0" max="<?php echo $row['PNT_QCPASS'] - $row['REMAINING_OPNAME']; ?>" onchange="EditQty('<?php echo "$i"; ?>')">
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <input class="form-control" type="number" id="opname-price<?php echo "$i"; ?>" value="<?php echo $row['OPNAME_PRICE']; ?>" onchange="EditPrice('<?php echo "$i"; ?>')">
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    <button class="btn btn-sm btn-danger" type="button" onclick="DeleteHM('<?php echo "$i"; ?>')">DELETE</button>
                </td>
                <td class="hide" id="opname-id<?php echo "$i"; ?>">
                    <?php echo $row['OPNAME_ID']; ?>
                </td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>
</table>
<br>
<div class="col-sm-12 text-center">
    <button type="button" class="btn btn-primary col-sm-12" onclick="SubmitRevision();">SUBMIT REVISION</button>
</div>
<script>
    var table = $('#table-revision').dataTable({
        "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;
            api.column(9, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                            '<tr class="group"><td colspan="9" style="background-color:#FF7C63">' + group + '</td></tr>'
                            );

                    last = group;
                }
            });
        }
    });

    function EditQty(param) {
        var qty = $('#opname-qty' + param);
        var max = parseInt(qty.attr("max"));
        var val = parseInt(qty.val());
        if (val > max || val < 1) {
            qty.val(1)
        }
    }

    function EditPrice(param) {
        var price = $('#opname-price' + param);
        var max = parseInt(price.attr("max"));
        var val = parseInt(price.val());
        if (val < 1) {
            price.val(1)
        }
    }

    function SubmitRevision() {
        var periode = "<?php echo "$periode"; ?>";
        var type = "<?php echo "$type"; ?>";
        var opname_id = $('#opname-id0').text().trim();
        var rows = $('#table-revision').dataTable().fnGetNodes();
        var head_mark = [];
        var opname_qty = [];
        var price = [];

        for (var x = 0; x < rows.length; x++)
        {
            head_mark.push($(rows[x]).find("td:eq(0)").text().trim());
            opname_qty.push($(rows[x]).find("td:eq(6)").find('input').val());
            price.push($(rows[x]).find("td:eq(7)").find('input').val());
        }

        var sentReq = {
            periode: periode,
            type: type,
            head_mark: head_mark,
            opname_qty: opname_qty,
            price: price,
            opname_id: opname_id
        };
        console.log(sentReq);
        var cf = confirm("DO YOU WANT TO UPDATE THIS OPNAME?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "divpages_revise/submit_revise.php",
                data: sentReq,
                success: function (response, textStatus, jqXHR) {
                    alert(response);
                }
            })
        } else {
            return false;
        }
    }

    function DeleteHM(param) {
        var cf = confirm("DO YOU WANT TO DELETE THIS HEAD_MARK?");
        if (cf == false) {
            return false
        } else {
            table.row('#row' + param).remove().draw(false);
        }
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
</style>