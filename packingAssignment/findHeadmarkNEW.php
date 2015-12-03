<?php
require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';
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
?>

<?php
?>
<style type="text/css">
    .tSRC {
        background-color: green;
        text-align: center;
        color: white;
        width: 100%;
    }
    .tTRGT {
        background-color: blue;
        text-align: center;
        color: white;
        width: 100%;
    }
    table tbody tr td {
        vertical-align: middle !important;
    }
</style>
<label for="name" id="lblHM" class="col-sm-2 control-label">HEAD MARK</label>
<div class="col-sm-10">
    <div class="row">
        <div class="col-sm-6" style="overflow-x:auto;">
            <label for="name" class="tSRC" >|| SOURCE ||</label>
            <table id="listHM" class="table table-condensed display">
                <thead>
                    <tr>
                      <th><!-- <input class="checkbox-inline" type="checkbox" id="chkAll" name="chkAll" onchange="checkAll('chkAll','chkHM[]');getCheck('chkHM[]');ValTextShow('chkHM[]');"/> -->
                            Action
                        </th>
                        <th>Head Mark</th>
                        <th>Remain<br>Qty</th>
                        <th>Assign</th>
                        <th>Entry Date</th>
                        <!-- <th>Entry Sign</th> -->
                    </tr>
                </thead>         

                <tbody>
                  <!-- <tr style="display:none">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr> -->
                    <?php
                    $projectName = strval($_GET['project']);

                    $query = "SELECT * FROM PREPACKING_LIST WHERE PROJECT_NAME = :projectName "
                            . "AND (PACKING_STATUS = 'NP' OR PACKING_STATUS = 'PP') ORDER BY HEAD_MARK ASC";

                    $result = oci_parse($conn, $query);

                    oci_bind_by_name($result, ":projectName", $projectName);

                    oci_execute($result);
                    $j = 0;
                    while ($row = oci_fetch_array($result)) {

                        $qtyAssgment = SingleQryFld("SELECT SUM(DTL_PACKING.UNIT_PCK_QTY) FROM DTL_PACKING, MST_PACKING WHERE MST_PACKING.COLI_NUMBER=DTL_PACKING.COLI_NUMBER AND MST_PACKING.PCK_STAT = 'ACTIVE' AND DTL_PACKING.HEAD_MARK='" . $row['HEAD_MARK'] . "'", $conn);
                        // echo "SELECT SUM(UNIT_PCK_QTY) FROM DTL_PACKING WHERE HEAD_MARK='".$row['HEAD_MARK']."'<br>";
                        $Qty = intval($row['UNIT_QTY'] - $qtyAssgment);
                        ?>
                        <tr>
                            <td style="text-align: center;">
                                <a href="#lblHM" class="btn btn-success" id="<?php echo "btnSRC" . $j ?>" onclick="AddROW('<?php echo $j ?>')">add</a>
                            </td>
                            <td id="<?php echo "HM" . $j ?>"><?php echo $row['HEAD_MARK'] ?></td>
                            <td>
                                <div id="<?php echo "totQTY" . $j ?>"><?php echo intval($Qty) ?></div>
                            </td>
                            <td style="vertical-align: middle;width:90px;">
                                <input class="form-control" type="number" onchange="CEKLimit(<?php echo "'AsignQty" . $j . "'"; ?>)" id="<?php echo "AsignQty" . $j; ?>" value="<?php echo intval($Qty) ?>" max="<?php echo intval($Qty) ?>" min="1" />
                            </td>
                            <td><div id="<?php echo "entryDT" . $j ?>"><?php echo $row['ENTRY_DATE'] ?></div></td>
                        </tr>
                        <?php
                        // echo "$j<br>";
                        $j++;
                    }
                    ?>
                </tbody> 
            </table>
        </div>

        <div class="col-sm-6" style="overflow-x:auto;">
            <label for="name" class="tTRGT" >|| TARGET ||</label>
            <!-- <button id="addRow">add</button> -->
            <input type="number" name="totTRGET" id="totTRGET" value="0" style="display:none;">
            <table id="listHM2" class="table table-condensed display">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Head Mark</th>
                        <th>Remain<br>Qty</th>
                        <th>Assign</th>
                        <th>Entry Date</th>
                    </tr>
                </thead>
                <tbody>
                  <!-- <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(
            function() {
                $('#listHM').dataTable(
                        {
                            "pageLength": 10,
                            "lengthChange": false,
                            "columnDefs": [
                                {"orderable": false, "targets": 0},
                                {"orderable": false, "targets": 3}
                            ],
                            "order": [
                                [1, "asc"]
                            ]
                                    // ,
                                    // "scrollY": "350px",
                                    // "scrollCollapse": true,
                                    // "paging": false
                        }
                );

                $('#listHM2').dataTable(
                        {
                            // "pageLength": 10,
                            // "lengthChange": false,
                            "columnDefs": [
                                {"orderable": false, "targets": 0},
                                {"orderable": false, "targets": 3}
                            ],
                            "order": [
                                [1, "asc"]
                            ]
                            ,
                            "scrollY": "515px",
                            "scrollCollapse": true,
                            "paging": false
                        }
                );

                var projectName = $('#projName').val();
                // alert(projectName);
                getElements(projectName); // show ColiNumber

                $("input[type='search'][aria-controls='listHM2']").on('keyup',
                        function() {
                            // alert($(this).val());
                            if ($(this).val() != "") {
                                $('input[type="submit"]').attr('disabled', 'disabled');
                            } else {
                                $('input[type="submit"]').removeAttr('disabled');
                            }
                        }
                );
            }
    );

    // count row data table var oTable;
    // $(document).ready(function() {
    //     oTable = $('#example').dataTable(); // INGAT CASE SENSITIVE 'dataTable' bukan 'DataTable'
    //     var oSettings = oTable.fnSettings();
    //     alert( oSettings.fnRecordsTotal() );
    //     alert( oSettings.fnRecordsDisplay() );
    //     alert( oSettings.fnDisplayEnd() );

    // } );

    var tblSRC = $('#listHM').DataTable();
    $('#listHM tbody').on('mouseover', 'tr', function() {
        if ($(this).hasClass('selected')) {
            // $(this).removeClass('selected');
        }
        else {
            tblSRC.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    var tblTRGET = $('#listHM2').DataTable();
    $('#listHM2 tbody').on('mouseover', 'tr', function() {
        if ($(this).hasClass('selected')) {
            // $(this).removeClass('selected');
        }
        else {
            tblTRGET.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    function AddROW(idSRC) {
        // body...
        $('#btnSRC' + idSRC).attr("class", "btn btn-success hide");

        var src = $('#listHM').DataTable();
        var trget = $('#listHM2').DataTable();
        var oTable = $('#listHM2').dataTable();
        var counter = oTable.fnSettings().fnRecordsTotal();//$('#listHM2 tbody tr').length + 1; // count row
        // alert(oTable.fnGetData().length);
        var HM = $('#HM' + idSRC).text();
        var totQTY = $('#totQTY' + idSRC).text();
        var AsignQty = $('#AsignQty' + idSRC);
        var entryDT = $('#entryDT' + idSRC).text();

        trget.row.add([
            '<div style="text-align: center;"><a href="#lblHM" class="btn btn-danger" onclick="RemovROW(' + idSRC + ')">remove</a></div>',
            '<input type="hidden" name="HM' + counter + '" value="' + HM + '"/><div id="trgHM' + idSRC + '">' + HM + '</div>',
            '<input type="hidden" name="ActQty' + counter + '" value="' + totQTY + '"/><div id="trgTOTQTY' + idSRC + '">' + totQTY + '</div>',
            '<input class="form-control" type="number" id="trgAsignQty' + idSRC + '" onfocus="this.select();" onblur="CEKLimit(' + "'trgAsignQty" + idSRC + "'" + ')" name="AsignQty' + counter + '" value="' + AsignQty.val() + '" max="' + AsignQty.attr("max") + '" min="1" />',
            '<div id="trgENTRYDT' + idSRC + '">' + entryDT + '</div>'
        ]).draw();

        // $("#rw"+idSRC).hide();
        src.row('.selected').remove().draw(false);

        $("#totTRGET").val(counter + 1);
        ValTextShow(counter + 1);
    }

    function RemovROW(idTRGET) {
        var src = $('#listHM').DataTable();
        var trget = $('#listHM2').DataTable();
        var oTable = $('#listHM2').dataTable();

        var HM = $('#trgHM' + idTRGET).text();
        var totQTY = $('#trgTOTQTY' + idTRGET).text();
        var AsignQty = $('#trgAsignQty' + idTRGET);
        var entryDT = $('#trgENTRYDT' + idTRGET).text();

        src.row.add([
            '<div style="text-align: center;"><a href="#lblHM" class="btn btn-success" id="btnSRC' + idTRGET + '" onclick="AddROW(' + idTRGET + ')">add</a></div>',
            '<div id="HM' + idTRGET + '">' + HM + '</div>',
            '<div id="totQTY' + idTRGET + '">' + totQTY + '</div>',
            '<input class="form-control" type="number" id="AsignQty' + idTRGET + '" onchange="CEKLimit(' + "'AsignQty" + idTRGET + "'" + ')" value="' + AsignQty.val() + '" max="' + AsignQty.attr("max") + '" min="1" />',
            '<div id="entryDT' + idTRGET + '">' + entryDT + '</div>'
        ]).draw();

        // $("#rw"+idSRC).hide();
        trget.row('.selected').remove().draw(false);

        var counter = oTable.fnGetData().length;//$('#listHM2 tbody tr').length; // count row
        $("#totTRGET").val(counter);

        ValTextShow(counter);
    }

    function CEKLimit(idVarBle) {
        // body...
        var idVar = $('#' + idVarBle);
        var Vlue = parseInt(idVar.val());
        // alert(markVlue);

        if (isNaN(Vlue) || Vlue > idVar.attr("max") || Vlue == "0") {
            // alert("Bukan NO");



            // var ts = document.getElementById(idVarBle).value;

            idVar.val('0');

            // idVar.focus();

            //   function() {
            //      $(this).select();
            //   }
            // );

            if (idVarBle.indexOf("trgAsignQty") != -1) {
                ValTextShow('0');
                alert("Value Not Define");
                setTimeout(function() {
                    idVar.focus()
                }, 500);
            }
        } else {
            if (idVarBle.indexOf("trgAsignQty") != -1) {
                ValTextShow('1');
            }
        }
    }

    function ValTextShow(jmlTrget) {
        // var oTable      = $('#listHM2').dataTable();
        // var counter     = oTable.fnGetData().length;
        if (jmlTrget == 0) {
            $("#ValInput").hide();
            $('input[type="submit"]').attr('disabled', 'disabled');
        } else {
            $("#ValInput").show();
            // $('input[type="submit"]').removeAttr('disabled');
        }
    }

</script>
