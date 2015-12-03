<?php
require_once '../../../../../dbinfo.inc.php';
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
setlocale(LC_MONETARY, "en_US");
// 1. SET THE CLIENT IDENTIFIER AFTER EVERY CALL
// 2. USING UNIQUE VALUE FOR BACK END USER
oci_set_client_identifier($conn, $_SESSION['username']);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$opnameIdVal = $_GET['opnameId'];
$prjName = $_GET['prjName'];
$subcont = $_GET['subcont'];
$opnameDtlSql = "SELECT * FROM VW_REPORT_OPNAME_PRICE WHERE PROJECT_NAME = '$prjName' AND OPNAME_ID = '$opnameIdVal' AND SUBCONT_ID='$subcont'";
$opnameDtlParse = oci_parse($conn, $opnameDtlSql);
//echo $opnameDtlSql;
//oci_bind_by_name($opnameDtlParse, ":OPNID", $opnameIdVal);
oci_execute($opnameDtlParse);
//echo "$opnameDtlSql";
?>

<table class="display compact" id="opnameTableDetail" width="100%">
    <thead>
        <tr>
            <th style="background-color:#EBC696;">HEADMARK</th>
            <th style="background-color:#EBC696;">PROFILE</th>
            <th style="background-color:#EBC696;">LENGTH</th>
            <th style="background-color:#EBC696;">QCPASS QUANTITY</th>
            <th style="background-color:#EBC696;">QUANTITY OPNAME</th>
            <th style="background-color:#EBC696;">OPNAME DATE</th>
            <th style="background-color:#EBC696;">UNIT WEIGHT</th>
            <th style="background-color:#EBC696;">TOTAL WEIGHT</th>
            <th style="background-color:#EBC696;">GIVEN UNIT PRICE</th>
            <th style="background-color:#EBC696;">TOTAL PRICE</th>
            <th style="background-color:#EBC696;">ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        while ($row = oci_fetch_array($opnameDtlParse)) {
            echo "<tr id=rowdtl$i>";
            echo "<td id=hm$i>" . $row['HEAD_MARK'] . '</td>';
            echo "<td id=profile$i>" . $row['PROFILE'] . '</td>';
            echo "<td id=length$i>" . $row['LENGTH'] . '</td>';
            echo "<td id=qtyqcpass$i>" . $row['QCPASS'] . '</td>';
            echo "<td id=qtyopname$i>" . $row['QTY_OPNAME'] . '</td>';
            echo "<td id=opnamedate$i>" . $row['DATE_OPNAME'] . "</td>";
            echo "<td id=unitweight$i>" . $row['UNIT_WEIGHT'] . ' kg</td>';
            echo "<td id=totalweight$i>" . number_format($row['TOTAL_WEIGHT'], 2) . ' kg</td>';
            echo "<td id=price$i>IDR " . number_format($row['PRICE'], 0) . '</td>';
            echo "<td id=totalprice$i>IDR " . number_format($row['TOTAL_PRICE'], 2) . '</td>';
            echo "<td>" .
            "<button type=button class='btn btn-warning' id=editdtl$i onclick=editdtl('$i')>EDIT</button>" .
            "&nbsp;&nbsp;" .
            "<button type=button class='btn btn-danger' id=deletedtl$i onclick=deletedtl('$i')>DELETE</button>" .
            '</td>';

            echo '</tr>';
            $i++;
        }
        ?>
    </tbody>
</table>
<!-- Modal -->


<script>
//    $(document).ready(function(){
//        $('#edit-date').datepicker();
//    });
    var tableDtl = $('#opnameTableDetail').DataTable();

    function deletedtl(index) {
        var headmark = $('#hm' + index).text().trim();
        var idopname = "<?php echo "$opnameIdVal"; ?>";
        var projectname = "<?php echo "$prjName"; ?>";
        var cf = confirm("ARE YOU SURE WANT TO DELETE?");
        if (cf == true) {
            $.get("ReviseOpname/DeleteDtlOpname.php", {headmark: headmark, idopname: idopname, projectname: projectname}, function success(response) {
                if (response == "success") {
                    alert("OPNAME " + idopname + "WITH HEAD MARK " + headmark + " AND PROJECT NAME " + projectname + " HAS BEEN DELETED");
                    tableDtl.row('#rowdtl' + index).remove().draw(false);
                    if ($('#opnameTableDetail').dataTable().fnGetData().length == 0) {
//                        alert($('#opnameTableDetail').dataTable().fnGetData().length==0);
                         table.row('#rowsource' + index).remove().draw(false);   
                    }
                }
                else {
                    alert(response);
                }
            });
        }
        else {
            return false;
        }

    }

    function editdtl(index) {

        var idopname = "<?php echo "$opnameIdVal"; ?>";
        var projectname = "<?php echo "$prjName"; ?>";
        var subcont = "<?php echo "$subcont"; ?>";
        var headmark = $('#hm' + index).text().trim();
        var profile = $('#profile' + index).text().trim();
        var length = $('#length' + index).text().trim();
        var opnameqty = $('#qtyopname' + index).text().trim();
        var qcpassqty = $('#qtyqcpass' + index).text().trim();
        var unitweight = $('#unitweight' + index).text().trim();
        var priceunit = $('#price' + index).text().trim().replace(",", "");
        var opnamedate = $('#opnamedate' + index).text().trim();
        var sentData = {
            idopname: idopname,
            projectname: projectname,
            subcont: subcont,
            headmark: headmark,
            profile: profile,
            length: length,
            opnameqty: opnameqty,
            qcpassqty: qcpassqty,
            unitweight: unitweight,
            priceunit: priceunit,
            index: index,
            opnamedate: opnamedate

        };
        console.log(sentData);
        $.ajax({
            type: 'POST',
            data: sentData,
            url: "ReviseOpname/EditDtlOpname.php",
            success: function (response, textStatus, jqXHR) {
                $('.modal-body').html(response);
                $('.modal').modal('show');
            }
        });
    }

    function submitEdit() {

        var projectNo = $('#jobDropdownMonitor').val();
        var projectName = $('#edit-projectname').text().trim();
        var subcont = $('#edit-subcont').text().trim();
        var sama = false;
        var index = $('#edit-index').val();
        var opnameidasli = "<?php echo "$opnameIdVal"; ?>";
        var newidopname = $('#edit-opnameid').text().trim();
        var qtyOpname = $('#edit-qtyopname').val();
        var opnameprice = $('#edit-priceopname').val();
        var headmark = $('#edit-headmark').text().trim();
        var opnamedate = $('#edit-date').val();
        var opnameweight = $('#edit-weight').text().replace("kg", "").trim();
        if (opnameidasli == newidopname) {
            sama = true;
        }

        var data = {
            sama: sama,
            index: index,
            newidopname: newidopname,
            qtyOpname: qtyOpname,
            opnameprice: opnameprice,
            headmark: headmark,
            projectNo: projectNo,
            projectName: projectName,
            subcont: subcont,
            oldOpnameid: opnameidasli,
            opnamedate: opnamedate,
            opnameweight: opnameweight
        };
        console.log(data);

        var cf = confirm("DO YOU WANT TO EDIT THIS OPNAME ?");
        if (cf == false) {
            return false;
        }
        else {
            $.ajax({
                type: 'POST',
                url: "ReviseOpname/SubmitEditOpname.php",
                data: data,
                success: function (response, textStatus, jqXHR) {
                    alert(response);
                    $('.modal').modal('hide');
                    var totalweight = parseFloat(opnameweight) * parseFloat(qtyOpname);
                    var totalprice = totalweight * parseFloat(opnameprice);
                    $('#qtyopname' + index).html(qtyOpname);
                    $('#price' + index).html("IDR " + opnameprice);
                    $('#totalweight' + index).html(totalweight + " kg");
                    $('#totalprice' + index).html("IDR " + totalprice);
                }
            });
        }


    }
</script>