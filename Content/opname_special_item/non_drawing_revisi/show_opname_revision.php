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
$OPNAME_STATUS = SingleQryFld("SELECT OPN_STATUS FROM MST_OPNAME_SI WHERE OPNAME_ID = '$_POST[opname_id]'", $conn);
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
    <table class="table table-striped table-bordered" id="table-input">
        <thead>
            <tr>
                <th class="text-center">
                    HEAD MARK
                    <button id="add-item" type="button" class="btn btn-success" onclick="AddItem();"
                    <?php
                    if ($OPNAME_STATUS == "CLOSE") {
                        echo "disabled";
                    }
                    ?>>
                        <i class="fa fa-plus-square-o"></i>
                    </button>
                </th>
                <th class="text-center">
                    PROFILE
                </th>
                <th class="text-center">
                    LENGTH
                </th>
                <th class="text-center">
                    QTY
                </th>
                <th class="text-center">
                    WEIGHT
                </th>
                <th class="text-center">
                    PRICE
                </th>
                <th class="text-center">
                    REMARK
                </th>
                <th class="text-center">
                    PROCENTAGE
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
            $sql = "SELECT * FROM VW_INFO_OPNAME_SI "
                    . "WHERE OPNAME_ID = '$_POST[opname_id]' AND OPN_TYPE = 'NON DRAWING' ORDER BY HEAD_MARK ASC";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            $i = 0;
            while ($row = oci_fetch_array($parse)) {
                ?>
                <tr id="rowtarget<?php echo "$i"; ?>">
                    <td>
                        <input type="text" id="hm<?php echo "$i"; ?>" class="form-control" 
                               onkeyup="UpperCase('<?php echo $i; ?>', 'hm')" maxlength="50" 
                               value="<?php echo $row['HEAD_MARK']; ?>" 
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="text" id="profile<?php echo "$i"; ?>" class="form-control" 
                               onkeyup="UpperCase('<?php echo $i; ?>', 'profile')" maxlength="100" 
                               value="<?php echo $row['PROFILE']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="number" id="length<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'length')" 
                               value="<?php echo $row['LENGTH']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="number" id="qty<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'qty')" 
                               value="<?php echo $row['TOTAL_QTY']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="number" id="weight<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'weight')" 
                               value="<?php echo $row['WEIGHT']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="number" id="price<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'price')" 
                               value="<?php echo $row['OPN_PRICE']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="text" id="remark<?php echo "$i"; ?>" class="form-control" 
                               onchange="UpperCase('<?php echo $i; ?>', 'remark')" 
                               value="<?php echo $row['REMARK']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td>
                        <input type="number" id="procentage<?php echo "$i"; ?>" class="form-control" 
                               onchange="InputNumber('<?php echo $i; ?>', 'procentage')" 
                               value="<?php echo $row['PROCEN_WEIGHT']; ?>"
                               <?php
                               if ($OPNAME_STATUS == "CLOSE") {
                                   echo "disabled";
                               }
                               ?>>
                    </td>
                    <td class="text-center">
                        <div id="totalprice<?php echo "$i"; ?>">
                            <?php
                            echo $row['TOTAL_QTY'] * $row['WEIGHT'] * $row['OPN_PRICE'] * $row['PROCEN_WEIGHT'] / 100;
                            ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <i class="fa fa-lg fa-trash-o" style="color:red; cursor:pointer;" onclick="DeleteRow('<?php echo "$i"; ?>')"
                        <?php
                        if ($OPNAME_STATUS == "CLOSE") {
                            echo "disabled";
                        }
                        ?>>
                        </i>
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
    <small>*KLIK TOMBOL SUBMIT OPNAME UNTUK MELAKUKAN EDIT OPNAME</small>
    <button type="button" class="btn btn-success col-sm-12" id="button-submit" onclick="SubmitData();"
    <?php
    if ($OPNAME_STATUS == "CLOSE") {
        echo "disabled";
    }
    ?>>SUBMIT DATA</button>
</div>


<script>
    var table = $('#table-input').dataTable({
        scrolY: "600px"
    });
    var counter = "<?php echo "$i"; ?>";
    function AddItem() {
        var newTargetRow = table.fnAddData([
            "<input type='text' id='hm" + counter + "' class='form-control' onkeyup=UpperCase('" + counter + "','" + "hm" + "') maxlength='50'>",
            "<input type='text' id='profile" + counter + "' class='form-control' onkeyup=UpperCase('" + counter + "','" + "profile" + "') maxlength='100'>",
            "<input type='number' value='0' id='length" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "length" + "')>",
            "<input type='number' value='0' id='qty" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "qty" + "')>",
            "<input type='number' value='0' id='weight" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "weight" + "')>",
            "<input type='number' value='0' id='price" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "price" + "')>",
            "<input type='text' id='remark" + counter + "' class='form-control' onchange=UpperCase('" + counter + "','" + "remark" + "')>",
            "<input type='number' value='100' id='procentage" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "procentage" + "')>",
            "<div id=totalprice" + counter + ">0</div>",
            "<i class='fa fa-lg fa-trash-o' style='color:red; cursor:pointer;' onclick=DeleteRow('" + counter + "')></i>"
        ]);
        var oSettings = table.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        //menambahkan id
        var row = 'rowtarget' + counter;
        nTr.setAttribute('id', row);

        //menambahkan class
        $('td', nTr)[0].setAttribute('class', 'text-center');
        $('td', nTr)[1].setAttribute('class', 'text-center');
        $('td', nTr)[2].setAttribute('class', 'text-center');
        $('td', nTr)[3].setAttribute('class', 'text-center');
        $('td', nTr)[4].setAttribute('class', 'text-center');
        $('td', nTr)[5].setAttribute('class', 'text-center');
        $('td', nTr)[6].setAttribute('class', 'text-center');
        $('td', nTr)[7].setAttribute('class', 'text-center');
        $('td', nTr)[8].setAttribute('class', 'text-center');
        $('td', nTr)[9].setAttribute('class', 'text-center');
        counter++;
        $('#button-submit').prop("disabled", false);
    }

    function DeleteRow(param) {
        var table_targetrem = $('#table-input').DataTable();
        table_targetrem.row('#rowtarget' + param).remove().draw(false);
//        if ($('#table-input').dataTable().fnSettings().aoData.length === 0) {
//            $('#button-submit').prop("disabled", true);
//        }
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
        var weight = [];
        var price = [];
        var remark_item = [];
        var procentage = [];

        var rows = $('#table-input').dataTable().fnGetNodes();
        for (var x = 0; x < rows.length; x++) {
            head_mark.push($(rows[x]).find("td:eq(0)").find("input").val());
            profile.push($(rows[x]).find("td:eq(1)").find("input").val());
            length.push($(rows[x]).find("td:eq(2)").find("input").val());
            qty.push($(rows[x]).find("td:eq(3)").find("input").val());
            weight.push($(rows[x]).find("td:eq(4)").find("input").val());
            price.push($(rows[x]).find("td:eq(5)").find("input").val());
            remark_item.push($(rows[x]).find("td:eq(6)").find("input").val());
            procentage.push($(rows[x]).find("td:eq(7)").find("input").val());
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
            weight: weight,
            price: price,
            remark_item: remark_item,
            procentage:procentage
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
        var cf = confirm("APA ANDA YAKIN INGIN MELAKUKAN REVISI?");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "non_drawing_revisi/submit_data.php",
                data: sentReq,
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("GAGAL") == -1) {
                        alert("SUKSES INSERT");
                        component('NON_DRAWING_REV');
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
            case "remark":
                var str = $('#remark' + index).val();
                var res = str.toUpperCase();
                $('#remark' + index).val(res);
                break;
        }
    }
    function InputNumber(index, type) {
        switch (type) {
            case "length":
                var length = parseFloat($('#length' + index).val());
                if (length < 0 || isNaN(length) || length == "") {
                    $('#length' + index).val(0)
                }
                break;
            case "qty":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage / 100;
                if (qty < 0 || isNaN(qty) || qty == "") {
                    $('#qty' + index).val(0)
                }
                console.log(totalprice);
                $('#totalprice' + index).text(totalprice);
                break;
            case "weight":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage / 100;
                if (weight < 0 || isNaN(weight) || weight == "") {
                    $('#weight' + index).val(0)
                }
                console.log(totalprice);
                $('#totalprice' + index).text(totalprice);
                break;
            case "price":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage / 100;
                if (price < 0 || isNaN(price) || price == "") {
                    $('#price' + index).val(0)
                }
                console.log(totalprice);
                $('#totalprice' + index).text(totalprice);
                break;
            case "procentage":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage / 100;
                if (procentage < 0 || isNaN(procentage) || procentage == "") {
                    $('#procentage' + index).val(100);
                }
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
                $(rows[x]).find("td:eq(0)").find("input").removeAttr("disabled");
                $(rows[x]).find("td:eq(1)").find("input").removeAttr("disabled");
                $(rows[x]).find("td:eq(2)").find("input").removeAttr("disabled");
                $(rows[x]).find("td:eq(3)").find("input").removeAttr("disabled");
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
                <h4 class="modal-title text-center">MASUKKAN PASSWORD</h4>
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