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

$opnameDtlSql = "SELECT * FROM VW_REPORT_OPNAME_PRICE WHERE PROJECT_NAME = '$prjName' AND OPNAME_ID = '$opnameIdVal'";
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
            <th style="background-color:#EBC696;">QUANTITY OPNAME</th>
            <th style="background-color:#EBC696;">QCPASS QUANTITY (DATE)</th>
            <th style="background-color:#EBC696;">UNIT WEIGHT</th>
            <th style="background-color:#EBC696;">TOTAL WEIGHT</th>
            <th style="background-color:#EBC696;">GIVEN UNIT PRICE</th>
            <th style="background-color:#EBC696;">TOTAL PRICE</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while ($row = oci_fetch_array($opnameDtlParse)) {
                echo '<tr>';
                    echo '<td>'.$row['HEAD_MARK'].'</td>';
                    echo '<td>'.$row['PROFILE'].'</td>';
                    echo '<td>'.$row['LENGTH'].'</td>';
                    echo '<td>'.$row['QTY_OPNAME'].'</td>';
                    echo '<td><b>'.$row['QCPASS'].'</b>&nbsp;('.$row['QCPASSDATE'].')</td>';
                    echo '<td>'.$row['UNIT_WEIGHT'].' kg</td>';
                    echo '<td>'.number_format($row['TOTAL_WEIGHT'] ,2).' kg</td>';
                    echo '<td>IDR '.number_format($row['PRICE'],0).'</td>';
                    echo '<td>IDR '.number_format($row['TOTAL_PRICE'],2).'</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" style="color:black;text-align:center;background-color:#E39D90;">OPNAME SUMMARY</th>
            <th style="background-color:#E39D90;"></th>
            <th style="background-color:#E39D90;"></th>
            <th style="background-color:#E39D90;"></th>
        </tr>
    </tfoot>
</table>
    
<script>
    $(function () {
        $('#opnameTableDetail').DataTable({
//            "bFilter":false, //DISABLE SEARCH BOX
            "iDisplayLength": 10, //SHOW MAX 5 ITEMS IN DATATABLES
//            dom: 'C<"clear">lfrtip',
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$kgIDR,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // GRAND TOTAL NETT
                totalWeight = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    });
                    
                // GRAND TOTAL NETT
                totalPrice = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    });

                // UPDATE FOOTER
                    // GRAND TOTAL NETT
                $( api.column( 6 ).footer() ).html(
                    addCommas(totalWeight.toFixed(2)) + 'kg'
                );
        
                // GRAND TOTAL PRICE
                $( api.column( 8 ).footer() ).html(
                    'IDR ' + addCommas(totalPrice.toFixed(2))
                );
                
                // FUNCTION TO SEPARATE DIGITS WITH COMMAS
                function addCommas(nStr){
                        nStr += '';
                        var x = nStr.split('.');
                        var x1 = x[0];
                        var x2 = x.length > 1 ? '.' + x[1] : '';
                        var rgx = /(\d+)(\d{3})/;
                        while (rgx.test(x1)) {
                         x1 = x1.replace(rgx, '$1' + ',' + '$2');
                        }
                    return x1 + x2;
                }        
            }
        });
    });
</script>