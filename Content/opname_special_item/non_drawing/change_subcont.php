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
                    <button type="button" class="btn btn-success" onclick="AddItem();"><i class="fa fa-plus-square-o"></i></button>
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

        </tbody>
    </table>
</div>
<br>
<div class="row">
    <button type="button" class="btn btn-success col-sm-12" id="button-submit" disabled onclick="SubmitData();">SUBMIT DATA</button>
</div>


<script>
    var table = $('#table-input').dataTable({
        scrolY: "600px"
    });
    var counter = 0;
    function AddItem() {
        var newTargetRow = table.fnAddData([
            "<input type='text' id='hm" + counter + "' class='form-control' onkeyup=UpperCase('" + counter + "','" + "hm" + "') maxlength='50'>",
            "<input type='text' id='profile" + counter + "' class='form-control' onkeyup=UpperCase('" + counter + "','" + "profile" + "') maxlength='100'>",
            "<input type='number' value='0' id='length" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "length" + "')>",
            "<input type='number' value='0' id='qty" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "qty" + "')>",
            "<input type='number' value='0' id='weight" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "weight" + "')>",
            "<input type='number' value='0' id='price" + counter + "' class='form-control' onchange=InputNumber('" + counter + "','" + "price" + "')>",
            "<input type='text' value='' id='remark" + counter + "' class='form-control' placeholder='Remark Item' onkeyup=UpperCase('" + counter + "','" + "remark" + "')>",
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
        if ($('#table-input').dataTable().fnSettings().aoData.length === 0) {
            $('#button-submit').prop("disabled", true);
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
            procentage: procentage
        }
        console.log(sentReq);
        if (sentReq.head_mark.length == 0) {
            alert("ISI NAMA BARANG DULU!!!");
        } else if (sentReq.periode == "") {
            alert("MASUKKAN PERIODE!!!");
        } else if (sentReq.job == "") {
            alert("MASUKKAN JOB!!!");
        } else if (sentReq.subjob == "") {
            alert("MASUKKAN SUBJOB!!!");
        } else if (sentReq.subcont == "") {
            alert("MASUKKAN SUBCONT!!!");
        } else {
            var cf = confirm("SUBMIT OPNAME");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "non_drawing/submit_data.php",
                    data: sentReq,
                    success: function (response, textStatus, jqXHR) {
                        if (response.indexOf("GAGAL") == -1) {
                            alert("SUKSES INSERT");
                            component('NON_DRAWING');
                        } else {
                            alert("GAGAL INSERT");
                        }
                    }
                });
            } else {
                return false;
            }
        }
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
                var totalprice = qty * weight * price * procentage/100;
                if (qty < 0 || isNaN(qty) || qty == "") {
                    $('#qty' + index).val(0);
                }
                $('#totalprice' + index).text(totalprice);
                break;
            case "weight":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage/100;
                if (weight < 0 || isNaN(weight) || weight == "") {
                    $('#weight' + index).val(0);
                }
                $('#totalprice' + index).text(totalprice);
                break;
            case "price":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage/100;
                if (price < 0 || isNaN(price) || price == "") {
                    $('#price' + index).val(0);
                }
                $('#totalprice' + index).text(totalprice);
                break;
            case "procentage":
                var qty = parseFloat($('#qty' + index).val());
                var weight = parseFloat($('#weight' + index).val());
                var price = parseFloat($('#price' + index).val());
                var procentage = parseFloat($('#procentage' + index).val());
                var totalprice = qty * weight * price * procentage/100;
                if (procentage < 0 || isNaN(procentage) || procentage == "") {
                    $('#procentage' + index).val(100);
                }
                $('#totalprice' + index).text(totalprice);
                break;
        }
    }
</script>