<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';

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
$projectName = strval($_GET['projName']);
?>
<style type="text/css">
    #HMSingle .pui-picklist-buttons {
        margin-top: 90px;
    }
    #HMSingle .pui-picklist-filter{
        /*padding-right:15px;*/
        width:100%;
    }
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
    table thead tr th {
        vertical-align: middle !important;
        text-align: center;
        font-size: 12px;
    }
    table tbody tr td {
        vertical-align: middle !important;
        font-size: 12px;
    }
</style>
<!-- <div class="form-group row">
  <label for="name" id="lblHM" class="col-sm-2 control-label">HEAD MARK</label>
</div> -->
<!-- <div class="form-group row col-sm-12"> -->
<!-- <div class="col-sm-1"></div>
<div class="row col-sm-11">
   <label for="name" class="col-sm-6 tSRC" >HEAD MARK || SOURCE ||</label>
  <label for="name" class="col-sm-6 tTRGT" >HEAD MARK || TARGET ||</label>
</div> -->

<div class="col-sm-1"></div>
<div class="row col-sm-11">
    <div class="col-sm-6" style="overflow-x:auto;">
        <label for="name" class="tSRC" >HEAD MARK || SOURCE ||</label>
        <table id="listHM" class="table table-condensed display">
            <thead>
                <tr>
                    <th class="text-center">Act.</th>
                    <th class="text-center">Head Mark</th>
                    <th class="text-center">Avl.<br>Qty</th>
                    <th class="text-center">Assign</th>
                    <th class="text-center">Comp<br>Type</th>
                    <th class="text-center">Profile</th>
                    <th class="text-center">Unit<br>WT/Surf</th>
                    <th class="text-center">Tgl Akhir<br>Kirim</th>
                </tr>
            </thead>         

            <tbody>
                <?php
                $query = "SELECT * FROM MASTER_DRAWING WHERE PROJECT_NAME = :projectName AND SUBCONT_STATUS = 'NOTASSIGNED' AND DWG_STATUS = 'ACTIVE' ORDER BY HEAD_MARK ASC";
                //echo $query;

                $result = oci_parse($conn, $query);

                oci_bind_by_name($result, ":projectName", $projectName);

                oci_execute($result);
                $j = 0;
                while ($row = oci_fetch_array($result)) {

                    $qtyAssgment = SingleQryFld("SELECT SUM(UNIT_QTY) AS ASSIGN_QTY FROM FABRICATION WHERE PROJECT_NAME = '$projectName' AND HEAD_MARK = '" . $row['HEAD_MARK'] . "'", $conn);
                    // echo "SELECT SUM(UNIT_PCK_QTY) FROM DTL_PACKING WHERE HEAD_MARK='".$row['HEAD_MARK']."'<br>";
                    $Qty = intval($row['TOTAL_QTY'] - $qtyAssgment);
                    ?>
                    <tr>
                        <td style="text-align: center;" class="text-center">
                            <?php if ($row['PPIC_CHECK'] == '0'): ?>
                                <label class="label label-danger">Not Approve</label>
                            <?php else: ?>
                                <a href="#lblHM" class="btn btn-success btn-sm" onclick="AddROW('<?php echo $j ?>')">Add</a>
                            <?php endif; ?>
                        </td>
                        <td id="<?php echo "HM" . $j ?>" class="text-center"><?php echo $row['HEAD_MARK'] ?></td>
                        <td class="text-center">
                            <div class="text-center" id="<?php echo "totQTY" . $j ?>"><?php echo intval($Qty) ?></div>
                        </td>
                        <td class="text-center" style="vertical-align: middle;width:90px;">
                            <input class="form-control" type="number" onchange="CEKLimit(<?php echo "'AsignQty" . $j . "'"; ?>)" id="<?php echo "AsignQty" . $j; ?>" value="<?php echo intval($Qty) ?>" max="<?php echo intval($Qty) ?>" min="1" />
                        </td>
                        <td class="text-center"><div id="<?php echo "compType" . $j ?>"><?php echo $row['COMP_TYPE'] ?></div></td>
                        <td class="text-center">
                            <div class="text-center" id="<?php echo "profil" . $j ?>"><?php echo '#' . $row['DWG_TYP'] . '# ' . $row['PROFILE'] ?></div>
                            <div class="text-center" id="<?php echo "len" . $j ?>">Len : <?php echo $row['LENGTH'] ?></div>
                        </td>
                        <td class="text-center">
                            <div class="text-center" id="<?php echo "UnitWT" . $j ?>">WT: <?php echo $row['WEIGHT'] ?></div>
                            <div class="text-center" id="<?php echo "UnitSurf" . $j ?>">Surf: <?php echo $row['SURFACE'] ?></div>
                        </td>
                        <td class="text-center">
                            <div class="text-center" id="tanggal-dlv">-</div>
                        </td>
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
        <label for="name" class="tTRGT" >HEAD MARK || TARGET ||</label>
        <!-- <button id="addRow">add</button> -->
        <input type="number" name="totTRGET" id="totTRGET" value="0" style="display:none;">
        <table id="listHM2" class="table table-condensed display">
            <thead>
                <tr>
                    <th class="text-center">Act.</th>
                    <th class="text-center">Head Mark</th>
                    <th class="text-center">Avl.<br>Qty</th>
                    <th class="text-center">Assign</th>
                    <th class="text-center">Comp<br>Type</th>
                    <th class="text-center">Profile</th>
                    <th class="text-center">Unit<br>WT/Surf</th>
                    <th class="text-center">Tgl Akhir<br>Kirim</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!-- </div> -->


<script type="text/javascript">

    $(document).ready(
            function () {
                $('#listHM').dataTable(
                        {
                            "pageLength": 10,
                            "lengthChange": false,
                            "columnDefs": [
                                {"orderable": false, "targets": 0},
                                {"orderable": false, "targets": 3},
                                {"orderable": false, "targets": 5},
                                {"orderable": false, "targets": 6}
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
                                {"orderable": false, "targets": 3},
                                {"orderable": false, "targets": 5},
                                {"orderable": false, "targets": 6}
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
    $('#listHM tbody').on('mouseover', 'tr', function () {
        if ($(this).hasClass('selected')) {
            // $(this).removeClass('selected');
        }
        else {
            tblSRC.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    var tblTRGET = $('#listHM2').DataTable();
    $('#listHM2 tbody').on('mouseover', 'tr', function () {
        if ($(this).hasClass('selected')) {
            // $(this).removeClass('selected');
        }
        else {
            tblTRGET.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
    var date = "<?php echo date('m/d/Y'); ?>";
    function AddROW(idSRC) {
        // body...
        var src = $('#listHM').DataTable();
        var trget = $('#listHM2').DataTable();
        var oTable = $('#listHM2').dataTable();
        var counter = oTable.fnSettings().fnRecordsTotal();//$('#listHM2 tbody tr').length + 1; // count row
        // alert(oTable.fnGetData().length);
        var HM = $('#HM' + idSRC).text();
        var totQTY = $('#totQTY' + idSRC).text();
        var AsignQty = $('#AsignQty' + idSRC);
        var compType = $('#compType' + idSRC).text();
        var profil = $('#profil' + idSRC).text();
        var len = $('#len' + idSRC).text();
        var UnitWT = $('#UnitWT' + idSRC).text();
        var UnitSurf = $('#UnitSurf' + idSRC).text();

        trget.row.add([
            '<div style="text-align: center;"><a href="#lblHM" class="btn btn-danger btn-sm" onclick="RemovROW(' + idSRC + ')">Del</a></div>',
            '<input type="hidden" name="HM' + counter + '" value="' + HM + '"/><div class="text-center" id="trgHM' + idSRC + '">' + HM + '</div>',
            '<input type="hidden" name="ActQty' + counter + '" value="' + totQTY + '"/><div class="text-center" id="trgTOTQTY' + idSRC + '">' + totQTY + '</div>',
            '<input class="form-control" type="number" id="trgAsignQty' + idSRC + '" onfocus="this.select();" onblur="CEKLimit(' + "'trgAsignQty" + idSRC + "'" + ')" name="AsignQty' + counter + '" value="' + AsignQty.val() + '" max="' + AsignQty.attr("max") + '" min="1" />',
            '<div class="text-center" id="trgcompType' + idSRC + '">' + compType + '</div>',
            '<div class="text-center" id="trgprofil' + idSRC + '">' + profil + '</div><div id="trglen' + idSRC + '">' + len + '</div>',
            '<div class="text-center" id="trgUnitWT' + idSRC + '">' + UnitWT + '</div><div id="trgUnitSurf' + idSRC + '">' + UnitSurf + '</div>',
            "<input type='text' name='enddlv" + idSRC + "' value='" + date + "' class='form-control' style='width:100px;' readonly>"
        ]).draw();

        // $("#rw"+idSRC).hide();
        src.row('.selected').remove().draw(false);

        $("#totTRGET").val(counter + 1);
        ValTextShow(counter + 1);
        $('input[name^=enddlv]').datetimepicker({
            pickTime: false
                    // pickDate: false
        });
    }

    function RemovROW(idTRGET) {
        var src = $('#listHM').DataTable();
        var trget = $('#listHM2').DataTable();
        var oTable = $('#listHM2').dataTable();

        var HM = $('#trgHM' + idTRGET).text();
        var totQTY = $('#trgTOTQTY' + idTRGET).text();
        var AsignQty = $('#trgAsignQty' + idTRGET);
        var compType = $('#trgcompType' + idTRGET).text();
        var profil = $('#trgprofil' + idTRGET).text();
        var len = $('#trglen' + idTRGET).text();
        var UnitWT = $('#trgUnitWT' + idTRGET).text();
        var UnitSurf = $('#trgUnitSurf' + idTRGET).text();

        src.row.add([
            '<div class="text-center" style="text-align: center;"><a href="#lblHM" class="btn btn-success btn-sm" onclick="AddROW(' + idTRGET + ')">Add</a></div>',
            '<div class="text-center" id="HM' + idTRGET + '">' + HM + '</div>',
            '<div class="text-center" id="totQTY' + idTRGET + '">' + totQTY + '</div>',
            '<input class="form-control" type="number" id="AsignQty' + idTRGET + '" onchange="CEKLimit(' + "'AsignQty" + idTRGET + "'" + ')" value="' + AsignQty.val() + '" max="' + AsignQty.attr("max") + '" min="1" />',
            '<div class="text-center" id="compType' + idTRGET + '">' + compType + '</div>',
            '<div class="text-center" id="profil' + idTRGET + '">' + profil + '</div><div class="text-center" id="len' + idTRGET + '">' + len + '</div>',
            '<div class="text-center" id="UnitWT' + idTRGET + '">' + UnitWT + '</div><div class="text-center" id="UnitSurf' + idTRGET + '">' + UnitSurf + '</div>',
            '<div class=text-center>-</div>'
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
                setTimeout(function () {
                    idVar.focus()
                }, 500);
            }
        } else {
            if (idVarBle.indexOf("trgAsignQty") != -1) {
                ValTextShow('1');
            }
        }
    }

</script>