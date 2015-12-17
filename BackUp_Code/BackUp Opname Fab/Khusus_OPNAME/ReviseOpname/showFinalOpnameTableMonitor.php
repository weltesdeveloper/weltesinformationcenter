<?php
require_once '../../../../../dbinfo.inc.php';
require_once '../../../../../FunctionAct.php';
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

// HAK AKSES
$PAINT_ACCS = HakAksesUser($username, 'PAINT_ACCS', $conn);
if ($PAINT_ACCS <> 1) {
    # code...
    echo <<< EOD
       <h1>You Can't ACCESS PAINTING PAGE !</h1>
       <p>Contact Your Admin Web to Allow Access<p>
       <p><a href="/weltesinformationcenter/login_fabrication.php">LOGIN PAGE</a><p>
EOD;
    exit;
}

$projectName = strval($_POST['projectNameValue']);
$subcont = strval($_POST['subcontValue']);
$jobVal = strval($_POST['jobValue']);
$periode = intval($_POST['periode']);
if ($periode == 0) {
    $periode == "";
} else {
    $periode = str_pad($periode, 4, 0, STR_PAD_LEFT);
}
//echo "$periode";

$finalOpnameSql = "  SELECT DISTINCT MO.OPNAME_ID,
                  MO.PROJECT_NO,
                  MO.SUBCONT_ID,
                  MO.OPN_ACT_DATE,
                  DO.PROJECT_NAME
    FROM MST_OPNAME MO INNER JOIN DTL_OPNAME DO ON DO.OPNAME_ID = MO.OPNAME_ID
   WHERE     MO.PROJECT_NO = '$jobVal'
         AND DO.PROJECT_NAME LIKE'%$projectName%'
         AND MO.SUBCONT_ID LIKE '%$subcont%'
         AND MO.OPNAME_ID LIKE '%$jobVal%'
         AND MO.OPNAME_ID LIKE '%$periode%'
ORDER BY MO.OPNAME_ID ASC";
//echo $finalOpnameSql;
$finalOpnameParse = oci_parse($conn, $finalOpnameSql);
oci_execute($finalOpnameParse);
?>
<table id="opnameMonitor" class="display compact" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>OPNAME ID</th>
            <th>OPNAME DATE</th>
            <th>PROJECT_NAME</th>
            <th>SUBCONT_ID</th>
            <th>ACTION</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $i = 0;
        while ($row = oci_fetch_array($finalOpnameParse)) {
            ?>
            <tr id="rowsource<?php echo "$i"; ?>">
                <td class="text-center details-control" onclick="detailRow('<?php echo "$i"; ?>');" id="details<?php echo "$i"; ?>"></td>
                <td id="opnId<?php echo "$i"; ?>"> <?php echo $row['OPNAME_ID']; ?></td>
                <td id="opnDate<?php echo "$i"; ?>"> <?php echo $row['OPN_ACT_DATE']; ?></td>
                <td id="prjName<?php echo "$i"; ?>"> <?php echo $row['PROJECT_NAME']; ?></td>
                <td id="sbctId<?php echo "$i"; ?>"> <?php echo $row['SUBCONT_ID']; ?></td>
                <td>
                    <button type='button' class='btn btn-danger' onclick="deleteMaster('<?php echo "$i"; ?>');" id="deleteOpname<?php echo "$i"; ?>">DELETE</button>
                </td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>
</table>
<script>
    var table = $('#opnameMonitor').DataTable({
        "iDisplayLength": 10
    });

    function detailRow(index) {
        var tr = $('#details' + index).closest('tr');
        var row = table.row(tr);
        var opnameId = $('#opnId' + index).text().trim();
        var prjName = $('#prjName' + index).text().trim();
        var subcont = $('#sbctId'+ index).text().trim();
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {

            // Open this row
            $.ajax({
                type: 'GET',
                url: "ReviseOpname/opnameTableDetails.php",
                data: {opnameId: opnameId, prjName: prjName, subcont:subcont},
                beforeSend: function ()
                {
                    $("#wait").css("display", "block");
                },
                success: function (response, textStatus, jqXHR) {
                    $("#wait").css("display", "none");
                    row.child(response).show();
                    tr.addClass('shown');
                }
            });
        }
    }

    function deleteMaster(index) {
        var cf = confirm("ARE YOU SURE WANT TO DELETE?");
        if (cf == true) {
            var opnameID = $('#opnId' + index).text().trim();
            $.get("ReviseOpname/DeleteMasterOpname.php", {opnameID: opnameID}, function success(response) {
                alert(response);
            });
           table.row('#rowsource' + index).remove().draw(false);
        }
        else {
            return false;
        }
    }
</script>

<style>
    td.details-control {
        background: url('../../../../AdminLTE/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('../../../../AdminLTE/img/details_close.png') no-repeat center center;
    }
</style>