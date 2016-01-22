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
$project_name = $_POST['project_name'];
$opname_type = $_POST['opname_type'];

$sql = "WITH OPN
     AS (  SELECT HEAD_MARK, OPNAME_TYPE, SUM (OPNAME_QTY) OPNAME_QTY
             FROM VW_OPNAME_PNT
            WHERE OPNAME_TYPE = '$opname_type'
         GROUP BY HEAD_MARK, OPNAME_TYPE)
  SELECT CVI.PROJECT_NAME,
  CVI.COMP_TYPE,
         CVI.HEAD_MARK,
         SUM (CVI.PNT_QCPASS) PNT_QCPASS,
         CVI.SURFACE,
         COALESCE (OPN.OPNAME_QTY, 0) OPNAME_QTY,
         OPN.OPNAME_TYPE,
         SUM (CVI.PNT_QCPASS) - COALESCE (OPNAME_QTY, 0) REMAINING_QCPASS
    FROM COMP_VW_INFO CVI LEFT OUTER JOIN OPN ON CVI.HEAD_MARK = OPN.HEAD_MARK
   WHERE PROJECT_NAME = '$project_name'
GROUP BY CVI.PROJECT_NAME,
         CVI.HEAD_MARK,
         OPN.OPNAME_QTY,
         OPN.OPNAME_TYPE,
         CVI.SURFACE,
         CVI.COMP_TYPE
  HAVING (SUM (CVI.PNT_QCPASS) - COALESCE (OPNAME_QTY, 0)) <> 0
ORDER BY CVI.COMP_TYPE, TO_NUMBER (REGEXP_REPLACE (CVI.HEAD_MARK, '[^[:digit:]]', NULL))";
//echo "$sql";
?>
<table class="table table-bordered table-striped" id="table-source">
    <thead>
        <tr>
            <th class="text-center" style="vertical-align: middle;">HEAD<br>MARK</th>
            <th class="text-center" style="vertical-align: middle;">COMP<br>TYPE</th>
            <th class="text-center" style="vertical-align: middle;">SURFACE(M<sup>2</sup>)</th>
            <th class="text-center" style="vertical-align: middle;">QC PASS<br>QTY</th>
            <th class="text-center" style="vertical-align: middle;">ACT</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $i = 1;
        while ($row = oci_fetch_array($parse)) {
            ?>
            <tr id="rowsource<?php echo "$i"; ?>">
                <td class="text-center" id="hm-source<?php echo "$i"; ?>">
                    <?php echo $row['HEAD_MARK']; ?>
                </td>
                <td class="text-center" id="comptype-source<?php echo "$i"; ?>">
                    <?php echo $row['COMP_TYPE']; ?>
                </td>
                <td class="text-center" id="surface-source<?php echo "$i"; ?>">
                    <?php echo $row['SURFACE']; ?>
                </td>
                <td class="text-center" id="qcpass-source<?php echo "$i"; ?>">
                    <?php echo $row['REMAINING_QCPASS']; ?>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-success" onclick="AddItem('<?php echo "$i"; ?>')">ADD</button>                
                </td>
                <?php
                $i++;
            }
            ?>
    </tbody>
</table>

<script>
    var table_source = $('#table-source').DataTable();
    function AddItem(param) {
        $('#btn-submit').removeAttr("disabled");
        var hm_source = $('#hm-source' + param).text().trim();
        var comptype_source = $('#comptype-source' + param).text().trim();
        var surface_source = $('#surface-source' + param).text().trim();
        var qcpass_source = $('#qcpass-source' + param).text().trim();
        var table_target = $('#table-target').dataTable();
        var table_source = $('#table-source').DataTable();
        var newTargetRow = table_target.fnAddData([
            hm_source,
            comptype_source,
            surface_source,
            qcpass_source,
            "<input type=number id='opnameqty" + counteradd + "' min=0 max=" + qcpass_source + " value=" + qcpass_source + " onchange=CekValidQty('" + counteradd + "') class=form-control>",
//            "<input type=text class=form-control id='price" + counteradd + "' min=0 value=0 style='width:150px;' onchange=EditPrice('" + counteradd + "')>",
            "<button type=button class='btn btn-sm btn-warning' onclick=RemoveItem('" + counteradd + "')>REMOVE</button>"
        ]);
        var oSettings = table_target.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var row_target = 'rowtarget' + counteradd;
        var hm_target = 'hm-target' + counteradd;
        var comptype_target = 'comptype-target' + counteradd;
        var surface_target = 'surface-target' + counteradd;
        var qcpass_target = 'qcpass-target' + counteradd;

        nTr.setAttribute('id', row_target);
        $('td', nTr)[0].setAttribute('id', hm_target);
        $('td', nTr)[1].setAttribute('id', comptype_target);
        $('td', nTr)[2].setAttribute('id', surface_target);
        $('td', nTr)[3].setAttribute('id', qcpass_target);

        $('td', nTr)[0].setAttribute('class', "text-center");
        $('td', nTr)[1].setAttribute('class', "text-center");
        $('td', nTr)[2].setAttribute('class', "text-center");
        $('td', nTr)[3].setAttribute('class', "text-center");
        $('td', nTr)[4].setAttribute('class', "text-center");
        $('td', nTr)[5].setAttribute('class', "text-center");
//        $('td', nTr)[6].setAttribute('class', "text-center");

        table_source.row('#rowsource' + param).remove().draw(false);
        counteradd++;

    }

    function RemoveItem(index) {
        var hm_target = $('#hm-target' + index).text().trim();
        var comptype_target = $('#comptype-target' + index).text().trim();
        var surface_target = $('#surface-target' + index).text().trim();
        var qcpass_target = $('#qcpass-target' + index).text().trim();
        var table_source = $('#table-target').DataTable();
        var table_target = $('#table-source').dataTable();
        var newTargetRow = table_target.fnAddData([
            hm_target,
            comptype_target,
            surface_target,
            qcpass_target,
            '<button type="button" class="btn btn-sm btn-success" onclick="AddItem(' + counterremove + ')">ADD</button>'
        ]);
        var oSettings = table_target.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var rowsource = 'rowsource' + counterremove;
        var hm_source = 'hm-source' + counterremove;
        var comptype_source = 'comptype-source' + counterremove;
        var surface_source = 'surface-source' + counterremove;
        var qcpass_source = 'qcpass-source' + counterremove;

        nTr.setAttribute('id', rowsource);
        $('td', nTr)[0].setAttribute('id', hm_source);
        $('td', nTr)[1].setAttribute('id', comptype_source);
        $('td', nTr)[2].setAttribute('id', surface_source);
        $('td', nTr)[3].setAttribute('id', qcpass_source);

        $('td', nTr)[0].setAttribute('class', "text-center");
        $('td', nTr)[1].setAttribute('class', "text-center");
        $('td', nTr)[2].setAttribute('class', "text-center");
        $('td', nTr)[3].setAttribute('class', "text-center");
        $('td', nTr)[4].setAttribute('class', "text-center");

        table_source.row('#rowtarget' + index).remove().draw(false);
        counterremove--;
        if ($('#table-target').dataTable().fnSettings().aoData.length === 0) {
            $('#btn-submit').prop("disabled", true);
        }
    }

    function CekValidQty(index) {
        var maxqc = parseInt($('#opnameqty' + index).attr("max"));
        var dt = parseInt($('#opnameqty' + index).val());
        if (dt < 1 || isNaN(dt) || dt > maxqc) {
            $('#opnameqty' + index).val(1);
        }
    }

    function SubmitData() {
        var rows = $('#table-target').dataTable().fnGetNodes();
        var head_mark = [];
        var opname_qty = [];
        var price = $('#price-opname').val();
        for (var x = 0; x < rows.length; x++)
        {
//            if ($(rows[x]).find("td:eq(5)").find('input').val() != 0) {
            head_mark.push($(rows[x]).find("td:eq(0)").text());
            opname_qty.push($(rows[x]).find("td:eq(4)").find('input').val());
//            price.push($(rows[x]).find("td:eq(5)").find('input').val());
//            }
        }
        var sentReq = {
            head_mark: head_mark,
            opname_qty: opname_qty,
            price: price,
            type: $('#opname-type').val(),
            periode: $('#periode').val(),
            tgl_opname: $('#tgl-opname').val(),
            subcont: $('#subcont').val(),
            project_name: "<?php echo "$project_name"; ?>"
        };
        console.log(sentReq);
        if (head_mark.length == 0) {
            alert("PLEASE SELECT ONE HEAD MARK TO OPNAM!!!!");
        } else if (price == "") {
            alert("INSERT PRICE OPNAME!!!!")
            $('#opname-price').focus();
        }
        else {
            var cf = confirm("DO YOU WANT TO SUBMIT OPNAME " + sentReq.type + " ON PERIODE " + sentReq.periode + " ?");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "divpages/submit_opname_pnt.php",
                    data: sentReq,
                    success: function (response, textStatus, jqXHR) {
                        alert(response);
                        window.location.reload();
                    }
                });
            }
            else {
                return false;
            }
        }
    }

    function EditPrice(param) {
        var value = $('#price' + param).val();
//        alert(value);
        var rows = $('#table-target').dataTable().fnGetNodes();
        $(rows).find('input[id^=price]').val(value);
    }
</script>

<style>
    #div-submit{
        padding-top: 20px;
    }
</style>