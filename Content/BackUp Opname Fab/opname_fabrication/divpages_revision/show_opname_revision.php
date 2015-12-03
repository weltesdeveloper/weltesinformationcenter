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
$opname_id = $_POST['opname_id'];
$job = $_POST['job'];
$subjob = $_POST['subjob'];
$OPNAME_STATUS = SingleQryFld("SELECT DISTINCT OPN_STATUS FROM VW_INFO_OPNAME_FAB WHERE OPNAME_ID = '$opname_id' AND PROJECT_NO = '$job' AND PROJECT_NAME = '$subjob'", $conn);
$sql = "WITH OPN
     AS (SELECT HEAD_MARK,
                SUBCONT_ID,
                TOTAL_QTY,
                OPN_PRICE,
                UNIT_WEIGHT
           FROM vw_info_opname_fab
          WHERE OPNAME_ID = '$opname_id' AND PROJECT_NO = '$job' AND PROJECT_NAME = '$subjob'),
     QCPASS
     AS (  SELECT HEAD_MARK,
                  SUBCONT_ID,
                  SUM (QCPASS) QCPASS,
                  SUM (QTY_OPN) QTY_OPN,
                  SUM (REMAINING_QCPASS) REMAINING_QCPASS
             FROM VW_SHOW_OPNAME_PRC
         GROUP BY HEAD_MARK, SUBCONT_ID)
SELECT OPN.HEAD_MARK,
       OPN.SUBCONT_ID,
       OPN.TOTAL_QTY,
       OPN.OPN_PRICE,
       OPN.UNIT_WEIGHT,
       QCPASS.REMAINING_QCPASS
  FROM OPN
       INNER JOIN
       QCPASS
          ON     OPN.HEAD_MARK = QCPASS.HEAD_MARK
             AND OPN.SUBCONT_ID = QCPASS.SUBCONT_ID";
$parse = oci_parse($conn, $sql);
oci_execute($parse);
?>
<marquee behavior="scroll" direction="left">
    <div class="col-sm-12 text-center" style="background-color: aquamarine;" id="div-textopname">
        <b>
            <?php
            if ($OPNAME_STATUS == "OPEN") {
                echo "OPNAME IS OPEN FOR EDIT <br>";
            } else {
                echo "OPNAME HAS BEEN CLOSED, PLEASE CONTACT ADMIN TO OPEN IT!! <br>";
            }
            ?>
        </b>
    </div>
</marquee>

<br><br>
<div class="col-sm-12 text-center" id="div-lockopname">
    <?php
    if ($OPNAME_STATUS == "OPEN") {
        echo "<button type='button' class='btn btn-danger col-sm-12' onclick='ClosedOpname();' id='btn-close' disabled=''>CLOSE OPNAME</button>";
    } else {
        echo "<button type='button' class='btn btn-success col-sm-12' onclick='OpenKey();' id='btn-open'>OPEN THE KEY</button>";
    }
    ?>
</div>

<table class="table table-striped table-bordered" id="table-revision-opn">
    <thead>
        <tr>
            <th class="text-center">HEAD MARK</th>
            <th class="text-center">WEIGHT</th>
            <th class="text-center">QTY OPNAME</th>
            <th class="text-center">REMAINING QC PASS</th>
            <th class="text-center">PRICE</th>
            <th class="text-center">TOTAL PRICE</th>
            <th class="text-center">ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        while ($row = oci_fetch_array($parse)) {
            ?>
            <tr id="row<?php echo "$i"; ?>">
                <td class="text-center" id="hm<?php echo "$i"; ?>">
                    <?php echo $row['HEAD_MARK']; ?>
                </td>
                <td class="text-center" id="wt<?php echo "$i"; ?>">
                    <?php echo $row['UNIT_WEIGHT']; ?>
                </td>
                <td class="text-center" id="qty<?php echo "$i"; ?>">
                    <?php echo $row['TOTAL_QTY']; ?>
                </td>
                <td class="text-center" id="rem-qc<?php echo "$i"; ?>">
                    <?php echo $row['REMAINING_QCPASS']; ?>
                </td>
                <td class="text-center" id="price<?php echo "$i"; ?>">
                    <?php echo $row['OPN_PRICE']; ?>
                </td>
                <td class="text-center" id="total-price<?php echo "$i"; ?>">
                    <?php echo number_format($row['OPN_PRICE'] * $row['TOTAL_QTY'] * $row['UNIT_WEIGHT'], 2); ?>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-primary" onclick="EditItem('<?php echo"$i"; ?>')" id="edit<?php echo "$i"; ?>" 
                            <?php if ($OPNAME_STATUS != "OPEN") echo " disabled"; ?>>
                        EDIT
                    </button>
                    <button type="button" class="btn btn-warning" onclick="DeleteItem('<?php echo"$i"; ?>')" id="delete<?php echo "$i"; ?>" 
                            <?php if ($OPNAME_STATUS != "OPEN") echo " disabled"; ?>>
                        DELETE
                    </button>
                </td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-right"  style="background-color: #F2EDE4">Total Qty : </th>
            <th class="text-center"></th>
            <th colspan="2" class="text-right" style="background-color: #9CC1D9">Total Price : </th>
            <th class="text-center"></th>
            <th class="text-center"></th>
        </tr>
    </tfoot>
</table>
<div class="modal fade" id="modal-password" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Password Untuk Membuka Revisi Opname</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="SubmitEditData();">Submit Revision</button>
            </div>
        </div>
    </div>
</div>
<script>
    var username = "<?php echo "$username"; ?>";
//    alert(username);
    if (username == "edward" || username == "miko") {
        $('#btn-close').prop("disabled", false);
    }
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
    var counter = "<?php echo "$i"; ?>";
    //RUBAH ITEM
    function EditItem(param) {
        var head_mark = $('#hm' + param).text().trim();
        var weight = $('#wt' + param).text().trim();
        var qty = $('#qty' + param).text().trim();
        var remaining_qc = $('#rem-qc' + param).text().trim();
        var price = $('#price' + param).text().trim();

        var sentReq = {
            head_mark: head_mark,
            weight: weight,
            qty: qty,
            remaining_qc: remaining_qc,
            price: price,
            index: param
        };
        console.log(sentReq);
        $.ajax({
            type: 'POST',
            url: "divpages_revision/show_modal.php",
            data: sentReq,
            success: function (response, textStatus, jqXHR) {
                $('#myModal .modal-body').html(response);
                $('#myModal').modal('show');
            }
        });
    }

    function DeleteItem(param) {
        var cf = confirm("DELETE THIS ITEM?");
        if (cf == true) {
            var project_no = $('#job').val();
            var project_name = $('#subjob').val();
            var subcont = $('#subcont').val();
            var opname_id = $('#opname-id').val();
            var periode = $('#periode').val();
            var head_mark = $('#hm' + param).text().trim();

            var sentReq = {
                project_no: project_no,
                project_name: project_name,
                opname_id: opname_id,
                periode: periode,
                head_mark: head_mark,
                action: "delete_data"
            };
            $.ajax({
                type: 'POST',
                url: "divpages_revision/change_ELEMENT.php",
                data: sentReq,
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf('SUKSES') != -1) {
                        alert(response);
                        $('#table-revision-opn').DataTable().row('#row' + param).remove().draw(false);
                    }
                }
            });
        } else {
            return false;
        }
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

    function AddItem() {
        //        counter++;
        console.log(counter++);
        var project_no = $('#job').val();
        var project_name = $('#subjob').val();
        var subcont = $('#subcont').val();
    }

    function OpenKey() {
        var password = prompt("MASUKKAN PASSWORD!!");
        if (password == "miko") {
            alert("password sukses");
            $('button[id^=edit]').removeAttr("disabled");
            $('button[id^=delete]').removeAttr("disabled");
            $('#btn-password').addClass("hide");
            $('#div-lockopname').empty();
            $('#div-lockopname').html("<button type='button' class='btn btn-danger col-sm-12' onclick='ClosedOpname();' id='btn-close'>CLOSE OPNAME</button>");
            $('#div-textopname').html("OPNAME IS OPEN FOR EDIT <br>");
        } else {
            alert("password salah, coba lagi");
        }
    }

    function ClosedOpname() {
        var password = prompt("MASUKKAN PASSWORD!!");
        if (password == "miko") {
            var opname_id = $('#opname-id').val();
            var cf = confirm("LOCK THIS OPNAME?");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "divpages_revision/change_ELEMENT.php",
                    data: {opname_id: opname_id, action: "lock_opname"},
                    success: function (response, textStatus, jqXHR) {
                        if (response.indexOf('SUKSES') != -1) {
                            alert(response);
                            $('#div-lockopname').empty();
                            $('#div-lockopname').html("<button type='button' class='btn btn-success col-sm-12' onclick='OpenKey();' id='btn-open'>OPEN THE KEY</button>");
                            $('#div-textopname').html("OPNAME HAS BEEN CLOSED, PLEASE CONTACT ADMIN TO OPEN IT!! <br>");
                            $('button[id^=edit]').prop("disabled", true);
                            $('button[id^=delete]').prop("disabled", true);
                        }
                    }
                });
            } else {
                return false;
            }
        } else {
            alert("password salah, coba lagi");
        }
    }
</script>

