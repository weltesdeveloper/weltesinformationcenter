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
}$head_mark = $_POST['head_mark'];
$weight = $_POST['weight'];
$qty = $_POST['qty'];
$remaining_qc = $_POST['remaining_qc'];
$price = $_POST['price'];
$index = $_POST['index'];
?>

<form class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-3">HEAD MARK</label>
        <label class="control-label col-sm-1">:</label>
        <label class="control-label col-sm-8" id="modal-headmark" style="text-align: left;"><?php echo $head_mark; ?></label>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">WEIGHT</label>
        <label class="control-label col-sm-1">:</label>
        <label class="control-label col-sm-8" id="modal-weight" style="text-align: left;"><?php echo $weight; ?></label>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">REMAINING QC</label>
        <label class="control-label col-sm-1">:</label>
        <label class="control-label col-sm-8" id="modal-remqc" style="text-align: left;"><?php echo $remaining_qc; ?></label>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">QTY OPNAME</label>
        <label class="control-label col-sm-1">:</label>
        <div class="col-sm-4"> 
            <input type="number" class="form-control" id="modal-qtyopname" onchange="ChangeQty();" value="<?php echo $qty; ?>" max="<?php echo ($qty + $remaining_qc); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">PRICE</label>
        <label class="control-label col-sm-1">:</label>
        <div class="col-sm-4"> 
            <input type="number" class="form-control" id="modal-price" onchange="ChangePrice();" value="<?php echo $price; ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3">TOTAL PRICE</label>
        <label class="control-label col-sm-1">:</label>
        <label class="control-label col-sm-8" id="modal-totalprice" style="text-align: left;"><?php echo number_format($qty * $weight * $price, 2); ?></label>
    </div>
    <input type="hidden" id="index" value="<?php echo "$index"; ?>">
</form>

<script>
//RUBAH QTY
    function ChangeQty() {
        var qty = $('#modal-qtyopname').val().trim();
        var max = $('#modal-qtyopname').attr("max");
        if (qty > max || isNaN(qty) || qty < 1) {
            $('#modal-qtyopname').val(1);
        }
        var qty = $('#modal-qtyopname').val().trim();
        var price = $('#modal-price').val().trim();
        var weight = $('#modal-weight').text().trim();
        var total_price = addCommas(parseFloat(qty) * parseFloat(price) * parseFloat(weight));
        console.log(total_price);
        $('#modal-totalprice').text(total_price);
    }

    function ChangePrice() {
        var qty = $('#modal-qtyopname').val().trim();
        var price = $('#modal-price').val().trim();
        var weight = $('#modal-weight').text().trim();
        var total_price = addCommas(parseFloat(qty) * parseFloat(price) * parseFloat(weight));
        console.log(total_price);
        $('#modal-totalprice').text(total_price);
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
//SUBMIT MODAL
    function SubmitEditData() {
        var project_no = $('#job').val();
        var project_name = $('#subjob').val();
        var subcont = $('#subcont').val();
        var opname_id = $('#opname-id').val();
        var periode = $('#periode').val();
        var head_mark = $('#modal-headmark').text().trim();
        var weight = $('#modal-weight').text().trim();
        var qty = $('#modal-qtyopname').val().trim();
        var price = $('#modal-price').val().trim();
        var total = $('#modal-totalprice').text().trim();
        var index = $("#index").val();

        var sentReq = {
            project_no: project_no,
            project_name: project_name,
            opname_id: opname_id,
            periode: periode,
            head_mark: head_mark,
            weight: weight,
            qty: qty,
            price: price,
            total: total,
            action: "edit_data"
        };
        console.log(sentReq);
        $.ajax({
            type: 'POST',
            url: "divpages_revision/change_ELEMENT.php",
            data: sentReq,
            success: function (response, textStatus, jqXHR) {
                if (response.indexOf('SUKSES') != -1) {
                    alert("SUKSES");
                    $('#qty' + index).text(qty);
                    $('#price' + index).text(price);
                    $('#total-price' + index).text(total);
                    $('#myModal').modal('hide');
                    var table = $('#table-revision-opn').DataTable();
                    table.destroy();
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
                } else {
                    alert(response);
                }
            }
        });
    }
</script>