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
?>
<div class="row">
    <table class="table table-striped table-bordered" id="table-input">
        <thead>
            <tr>
                <th class="text-center">
                    HEAD MARK&nbsp;&nbsp;&nbsp;&nbsp;
                </th>
                <th class="text-center">
                    PROFILE
                </th>
                <th class="text-center">
                    LENGTH
                </th>
                <th class="text-center">
                    WEIGHT
                </th>
                <th class="text-center">
                    % WEIGHT
                </th>
                <th class="text-center">
                    QTY
                </th>
                <th class="text-center">
                    PRICE
                </th>
                <th class="text-center">
                    TOTAL PRICE
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM VW_INFO_OPNAME_FAB WHERE OPNAME_ID = '$_POST[opname_id]' ORDER BY HEAD_MARK ASC";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            $i = 0;
            $total_price = 0;
            while ($row = oci_fetch_array($parse)) {
                ?>
                <tr id="rowtarget<?php echo "$i"; ?>">
                    <td class="text-center" id="head_mark<?php echo "$i"; ?>">
                        <?php echo $row['HEAD_MARK']; ?>
                    </td>
                    <td class="text-center" id="profile<?php echo "$i"; ?>">
                        <?php echo $row['PROFILE']; ?>
                    </td>
                    <td class="text-center" id="length<?php echo "$i"; ?>">
                        <?php echo $row['LENGTH']; ?>
                    </td>
                    <td class="text-center" id="weight<?php echo "$i"; ?>">
                        <?php echo $row['UNIT_WEIGHT']; ?>
                    </td>
                    <td class="text-center" id="weight<?php echo "$i"; ?>">
                        <?php echo $row['PROCEN_WEIGHT']; ?>
                    </td>
                    <td class="text-center" style="width: 100px;">
                        <?php echo $row['TOTAL_QTY']; ?>
                    </td>
                    <td class="text-center" style="width: 100px;">
                        <?php echo $row['OPN_PRICE']; ?>
                    </td>
                    <td class="text-center">
                        <div id="totalprice<?php echo "$i"; ?>">
                            <?php
                            echo number_format($row['TOTAL_QTY'] * $row['UNIT_WEIGHT'] * $row['OPN_PRICE'] * $row['PROCEN_WEIGHT'] / 100, 2);
                            ?>
                        </div>
                    </td>
                </tr>
                <?php
                $i++;
                $total_price += ($row['TOTAL_QTY'] * $row['UNIT_WEIGHT'] * $row['OPN_PRICE'] * $row['PROCEN_WEIGHT'] / 100);
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center" colspan="7">
                    SUMMARY
                </th>
                <th class="text-center">
                    <?php
                    echo number_format($total_price, 2);
                    ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
<br>
<div class="row">
    <button type="button" class="btn btn-success col-sm-12" id="button-submit" onclick="Print();">PRINT <?php echo "$_POST[opname_id]"; ?></button>
</div>


<script>
    var table = $('#table-input').DataTable({
        scrolY: "600px"
    });
    function Print() {
        var opname_id = $('#opname-id').val();
        var url = "normal_drawing_print/print_opname.php?opname_id=" + opname_id;
        PopupCenter(url, "print opname", 700, 700);
    }
    function PopupCenter(pageURL, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no,status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        targetWin.focus();
    }
</script>