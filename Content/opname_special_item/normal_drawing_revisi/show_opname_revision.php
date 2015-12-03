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
$OPNAME_STATUS = SingleQryFld("SELECT OPN_STATUS FROM MST_OPNAME WHERE OPNAME_ID = '$_POST[opname_id]'", $conn);
//$OPNAME_STATUS = SingleQryFld("SELECT OPN_STATUS FROM MST_OPNAME WHERE OPNAME_ID = '$_POST[opname_id]'", $conn);
echo "$OPNAME_STATUS";
if ($username == "miko" || $username == "edward" || $username == "chrishutagalung") {
    $OPNAME_STATUS = "OPEN";
}
if ($OPNAME_STATUS == "CLOSE") {
    ?>
    <button type="button" class="btn btn-success col-sm-12" id="button-openkey" onclick="OpenKey();">OPEN KEY</button>
    <br><br>
    <?php
}
?>
<div class="row">
    <table class="table table-striped table-bordered table-condensed" id="table-input">
        <thead>
            <tr>
                <th class="text-center">
                    HEAD MARK
                </th>
                <th class="text-center">
                    PROFILE
                </th>
                <th class="text-center">
                    LENGTH
                </th>
                <th class="text-center">
                    UNIT WEIGHT
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
                <th class="text-center">
                    REMOVE
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM VW_INFO_OPNAME_FAB WHERE OPNAME_ID = '$_POST[opname_id]' ORDER BY HEAD_MARK ASC";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            $i = 0;
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
                    <td class="text-center" id="unit_weight<?php echo "$i"; ?>">
                        <?php echo $row['UNIT_WEIGHT']; ?>
                    </td>
                    <td class="text-center" style="width: 100px;" id="procen-weight<?php echo "$i"; ?>">
                        <?php echo $row['PROCEN_WEIGHT']; ?>
                    </td>
                    <td class="text-center" style="width: 100px;">
                        <input type="number" id="qty<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'qty')" value="<?php echo $row['TOTAL_QTY']; ?>" 
                               style="width: 100px;"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo " disabled";
                               }
                               ?>>
                    </td>
                    <td class="text-center" style="width: 100px;">
                        <input type="number" id="price<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'price')" value="<?php echo $row['OPN_PRICE']; ?>" 
                               style="width: 100px;"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo " disabled";
                               }
                               ?>>
                    </td>
                    <td class="text-center">
                        <div id="totalprice<?php echo "$i"; ?>">
                            <?php
                            echo $row['TOTAL_QTY'] * $row['UNIT_WEIGHT'] * $row['OPN_PRICE'] * $row['PROCEN_WEIGHT'] / 100;
                            ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <i class="fa fa-lg fa-trash-o" style="color:red; cursor:pointer;" 
                           onclick="DeleteRow('<?php echo "$i"; ?>')"></i>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<div class="row">
    <button type="button" class="btn btn-success col-sm-12" id="button-submit" onclick="SubmitData();"
    <?php
    if ($OPNAME_STATUS == "CLOSE") {
        echo "disabled";
    }
    ?>>
        SUBMIT DATA
    </button>
</div>


<script>
    var table = $('#table-input').dataTable({
        scrolY: "600px"
    });
    var counter = "<?php echo "$i"; ?>";

    function DeleteRow(param) {
        var cf = confirm("DELETE THIS ITEM?");
        if (cf == true) {
            var table_targetrem = $('#table-input').DataTable();
            table_targetrem.row('#rowtarget' + param).remove().draw(false);
//            if ($('#table-input').dataTable().fnSettings().aoData.length === 0) {
//                $('#button-submit').prop("disabled", true);
//            }
        }
    }

    function SubmitData() {
        var job = $('#job').val();
        var subjob = $('#subjob').val();
        var subcont = $('#subcont').val();
        var tanggal = $('#tgl-opname').val();
        var periode = $('#periode').val();
        var opname_id = $('#opname-id').val();
        var head_mark = [];
        var profile = [];
        var length = [];
        var qty = [];
        var unit_weight = [];
        var price = [];
        var procent = [];

        var rows = $('#table-input').dataTable().fnGetNodes();
        for (var x = 0; x < rows.length; x++) {
            head_mark.push($(rows[x]).find("td:eq(0)").text().trim());
            profile.push($(rows[x]).find("td:eq(1)").text().trim());
            length.push($(rows[x]).find("td:eq(2)").text().trim());
            unit_weight.push($(rows[x]).find("td:eq(3)").text().trim());
            procent.push($(rows[x]).find("td:eq(4)").text().trim());
            qty.push($(rows[x]).find("td:eq(5)").find("input").val());
            price.push($(rows[x]).find("td:eq(6)").find("input").val());
        }

        var sentReq = {
            job: job,
            subjob: subjob,
            subcont: subcont,
            tanggal: tanggal,
            periode: periode,
            opname_id: opname_id,
            head_mark: head_mark,
            profile: profile,
            length: length,
            qty: qty,
            unit_weight: unit_weight,
            price: price,
            procent:procent
        }
        console.log(sentReq);
//        if (sentReq.head_mark.length == 0) {
//            alert("ISI NAMA BARANG DULU!!!");
//        } else if (sentReq.periode == "") {
//            alert("MASUKKAN PERIODE!!!");
//        } else if (sentReq.job == "") {
//            alert("MASUKKAN JOB!!!");
//        } else if (sentReq.subjob == "") {
//            alert("MASUKKAN SUBJOB!!!");
//        } else if (sentReq.subcont == "") {
//            alert("MASUKKAN SUBCONT!!!");
//        } else {
        var cf = confirm("SUBMIT OPNAME");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "normal_drawing_revisi/submit_data.php",
                data: sentReq,
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("GAGAL") == -1) {
                        alert("SUKSES INSERT");
                        component('SPECIAL_DRAWING_REV');
                    } else {
                        alert("GAGAL INSERT");
                    }
                }
            });
        } else {
            return false;
        }
//        }
    }

    function UpperCase(index, type) {
        switch (type) {
            case "hm":
                var str = $('#hm' + index).val();
                var res = str.toUpperCase();
                $('#hm' + index).val(res);
                break;
            case "profile":
                var str = $('#profile' + index).val();
                var res = str.toUpperCase();
                $('#profile' + index).val(res);
                break;
        }
    }
    function InputNumber(index, type) {
        switch (type) {
            case "qty":
                var qty = parseFloat($('#qty' + index).val());
                if (qty < 0 || isNaN(qty) || qty == "") {
                    $('#qty' + index).val(0)
                }
                var qty = parseFloat($('#qty' + index).val());
                var unit_weight = parseFloat($('#unit_weight' + index).text().trim());
                var price = parseFloat($('#price' + index).val());
                var total_procen = parseFloat($('#procen-weight' + index).text().trim());
                var totalprice = qty * unit_weight * price * total_procen / 100;
                console.log(totalprice);
                $('#totalprice' + index).text(totalprice);
                break;
            case "price":
                var price = parseFloat($('#price' + index).val());
                if (price < 0 || isNaN(price) || price == "") {
                    $('#price' + index).val(0)
                }
                var qty = parseFloat($('#qty' + index).val());
                var unit_weight = parseFloat($('#unit_weight' + index).text().trim());
                var price = parseFloat($('#price' + index).val());
                var total_procen = parseFloat($('#procen-weight' + index).text().trim());
                var totalprice = qty * unit_weight * price * total_procen / 100;
                console.log(totalprice);
                $('#totalprice' + index).text(totalprice);
                break;
        }
    }

    function OpenKey() {
        $('#password').val("");
        $('#modal-password').modal('show');
    }

    function OpenPassword() {
        var password = $('#password').val();
        if (password == "miko") {
            alert("PASSWORD BENAR");
            $('#modal-password').modal('hide');
            var rows = $('#table-input').dataTable().fnGetNodes();
            for (var x = 0; x < rows.length; x++) {
//                $(rows[x]).find("td:eq(0)").find("input").removeAttr("disabled");
//                $(rows[x]).find("td:eq(1)").find("input").removeAttr("disabled");
//                $(rows[x]).find("td:eq(2)").find("input").removeAttr("disabled");
//                $(rows[x]).find("td:eq(3)").find("input").removeAttr("disabled");
                $(rows[x]).find("td:eq(4)").find("input").removeAttr("disabled");
                $(rows[x]).find("td:eq(5)").find("input").removeAttr("disabled");
            }
            $('#button-submit').removeAttr("disabled");
            $('#add-item').removeAttr("disabled");
            $('#button-openkey').addClass("hide");
        } else {
            alert("PASSWORD SALAH BRO!!");
            $('#modal-password').modal('hide');
        }
    }
</script>

<div class="modal fade" id="modal-password" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">MASUKKAN PASSWORD UNTUK UNLOCK</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <input type="password" id="password" maxlength="10" class="form-control" style="text-align: center;">
                        </div>
                    </div>
                </form>
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12 text-left">
                            *JIKA KESULITAN HUBUNGI ADMINISTRATOR
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="OpenPassword();">Open Password</button>
            </div>
        </div>
    </div>
</div>