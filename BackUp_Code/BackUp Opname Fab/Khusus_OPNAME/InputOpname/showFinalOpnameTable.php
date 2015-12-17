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
$inisial_pn = SingleQryFld("SELECT DISTINCT PROJECT_CODE FROM VW_PROJ_INFO WHERE PROJECT_NAME_OLD = '$projectName'", $conn);
$subcont = strval($_POST['subcontValue']);
$jobVal = $_POST['jobValue'];
$periode = $_POST['periode'];
$date = new DateTime($_POST['date']);
$date = $date->format("mdY");
//echo "$date";
$idduplikat = 0;
//echo "$jobVal";
//$sqlCountID = "SELECT COUNT(*) JUMLAH FROM MST_OPNAME WHERE OPNAME_ID LIKE '$jobVal%'";
//$parseCountID = oci_parse($conn, $sqlCountID);
//oci_execute($parseCountID);
//$row1 = oci_fetch_array($parseCountID)['JUMLAH'];

$sqlSubcont = "SELECT SUBCONT_CODE FROM SUBCONTRACTOR WHERE SUBCONT_ID = '$subcont'";
$parseSubcont = oci_parse($conn, $sqlSubcont);
oci_execute($parseSubcont);
$subcont_code = oci_fetch_array($parseSubcont)['SUBCONT_CODE'];

//$date = date("mdY");
$opnameID = $jobVal . "-" . $inisial_pn . "-" . $subcont_code . "-" . str_pad($periode, 4, 0, STR_PAD_LEFT) . "-" . $date;
$sqlCountID = "SELECT COUNT(*) JUMLAH FROM MST_OPNAME WHERE OPNAME_ID = '$opnameID'";
$parseCountID = oci_parse($conn, $sqlCountID);
oci_execute($parseCountID);
$row1 = oci_fetch_array($parseCountID)['JUMLAH'];
if ($row1 > 0) {
    $idduplikat = 1;
}

$finalOpnameSql = "SELECT VSOR.* FROM VW_SHOW_OPNAME_PRC VSOR WHERE VSOR.PROJECT_NAME = :PROJNAME "
        . "AND VSOR.SUBCONT_ID = :SUBCONT AND VSOR.QCPASS <> 0 AND VSOR.QCPASS <> VSOR.QTY_OPN ORDER BY VSOR.HEAD_MARk";
//echo $finalOpnameSql;
$finalOpnameParse = oci_parse($conn, $finalOpnameSql);
oci_bind_by_name($finalOpnameParse, ":PROJNAME", $projectName);
oci_bind_by_name($finalOpnameParse, ":SUBCONT", $subcont);
oci_execute($finalOpnameParse);
?>
<input type="text" id="opnameid" class="form-control" value="<?php echo $opnameID; ?>" readonly="">

<table id="opnameSource" class="display compact" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>HEADMARK</th>
            <th>PROFILE</th>
            <th>LENGTH</th>
            <th>Σ QTY</th>
            <th>QC PASS</th>
            <th>ALREADY OPNAME</th>
            <th>QC DATE</th>
            <th>UNIT WEIGHT</th>
            <th>TOTAL WEIGHT</th>
            <th>ACTION</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $i = 0;
        while ($row = oci_fetch_array($finalOpnameParse)) {
            $remaining = $row['REMAINING_QCPASS'];
            ?>
            <tr id="rowsource<?php echo "$i"; ?>" <?php if ($remaining == 0) { ?> style="background-color: gray;" <?php } ?>>
                <td id="hmsource<?php echo "$i"; ?>"> <?php echo $row['HEAD_MARK']; ?></td>
                <td id="profilesource<?php echo "$i"; ?>"> <?php echo '#' . $row['DWG_TYP'] . '# ' . $row['PROFILE']; ?></td>
                <td id="lengthsource<?php echo "$i"; ?>"> <?php echo $row['LENGTH']; ?></td>
                <td id="qtysource<?php echo "$i"; ?>"> <?php echo $row['TOTAL_QTY']; ?></td>
                <td id="qcpasssource<?php echo "$i"; ?>"> <?php echo $row['QCPASS']; ?></td>
                <td id="opnamesource<?php echo "$i"; ?>"> <?php echo $row['QTY_OPN']; ?></td>
                <td id="qcdatesource<?php echo "$i"; ?>"> <?php echo $row['QCPASSDATE']; ?></td>
                <td id="weightsource<?php echo "$i"; ?>"> <?php echo $row['UNIT_WEIGHT']; ?></td>
                <td id="totalweightsource<?php echo "$i"; ?>"> <?php echo $row['TOTAL_WEIGHT']; ?></td>
                <td><button type='button' <?php if ($idduplikat == 1) { ?> class='btn btn-warning'<?php } else { ?> class='btn btn-success' <?php } ?>
                            onclick="AddItem('<?php echo "$i"; ?>')"
                            <?php if ($remaining == 0) { ?> disabled="" <?php } ?>><?php
                                if ($idduplikat == 1) {
                                    echo "OPNAME";
                                } else {
                                    echo "OPNAME";
                                }
                                ?></button></td>
            </tr>
            <?php
            $i++;
        }
        ?>
    </tbody>
</table>
<br><br>
<table id="opnameTarget" class="display compact" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>HEADMARK</th>
            <th>PROFILE</th>
            <th>LENGTH</th>
            <th>Σ QTY</th>
            <th>QC PASS</th>
            <th>ALREADY OPNAME</th>
            <th>QC DATE</th>
            <th>UNIT WEIGHT</th>
            <th>TOTAL WEIGHT</th>
            <th>OPNAME QTY</th>
            <th>OPNAME PRICE</th>
            <th>ACTION</th>
        </tr>
    </thead>

    <tbody>

    </tbody>
</table>
<br><br>
<button type="button" class="btn btn-danger" onclick="SubmitOpname();" style="float: right;" id="submit-opname">Submit Data</button>

<script>
    var counteradd = 0;
    var counterrem = -1;


    function AddItem(index) {
        $('#submit-opname').prop('disabled', false);
        var hmsource = $('#hmsource' + index).text().trim();
        var profilesource = $('#profilesource' + index).text().trim();
        var lengthsource = $('#lengthsource' + index).text().trim();
        var qtysource = $('#qtysource' + index).text().trim();
        var qcpasssource = $('#qcpasssource' + index).text().trim();
        var opnamesource = $('#opnamesource' + index).text().trim();
        var qcdatesource = $('#qcdatesource' + index).text().trim();
        var weightsource = $('#weightsource' + index).text().trim();
        var totalweightsource = $('#totalweightsource' + index).text();

        var remaining = parseInt(qcpasssource) - parseInt(opnamesource);

        var sentReq = {
            hmsouurce: hmsource,
            profilesource: profilesource,
            lengthsource: lengthsource,
            qtysorce: qtysource,
            qcpasssource: qcpasssource,
            opnamesource: opnamesource,
            qcdatesource: qcdatesource,
            weightsource: weightsource,
            totalweightsource: totalweightsource
        };
        var table_target = $('#opnameTarget').dataTable();
        var table_source = $('#opnameSource').DataTable();
        var newTargetRow = table_target.fnAddData([
            hmsource,
            profilesource,
            lengthsource,
            qtysource,
            qcpasssource,
            opnamesource,
            qcdatesource,
            weightsource,
            totalweightsource,
            "<input type=number id='opnameqty" + counteradd + "' min=0 max=" + remaining + " value=" + remaining + " onchange=CekValidQty('" + counteradd + "')>",
            "<input type=text id=opnameprice" + counteradd + " value=0 onchange=CekValidPrice('" + counteradd + "')>",
            "<button type=button class='btn btn-warning' onclick=RemoveItem('" + counteradd + "')>REMOVE</button>"
        ]);
        var oSettings = table_target.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var rowtarget = 'rowtarget' + counteradd;
        var hmtarget = 'hmtarget' + counteradd;
        var profiletarget = 'profiletarget' + counteradd;
        var lengthtarget = 'lengthtarget' + counteradd;
        var qtytarget = 'qtytarget' + counteradd;
        var qcpasstarget = 'qcpasstarget' + counteradd;
        var opnametarget = 'opnametarget' + counteradd;
        var qcdatetarget = 'qcdatetarget' + counteradd;
        var weighttarget = 'weighttarget' + counteradd;
        var totalweighttarget = 'totalweighttarget' + counteradd;

        nTr.setAttribute('id', rowtarget);
        $('td', nTr)[0].setAttribute('id', hmtarget);
        $('td', nTr)[1].setAttribute('id', profiletarget);
        $('td', nTr)[2].setAttribute('id', lengthtarget);
        $('td', nTr)[3].setAttribute('id', qtytarget);
        $('td', nTr)[4].setAttribute('id', qcpasstarget);
        $('td', nTr)[5].setAttribute('id', opnametarget);
        $('td', nTr)[6].setAttribute('id', qcdatetarget);
        $('td', nTr)[7].setAttribute('id', weighttarget);
        $('td', nTr)[8].setAttribute('id', totalweighttarget);

        table_source.row('#rowsource' + index).remove().draw(false);

//        $('#opnameprice' + counteradd).maskMoney({
//            precision:0
//        });
        counteradd++;
    }

    function RemoveItem(index) {

        var hmtarget = $('#hmtarget' + index).text();
        var profiletarget = $('#profiletarget' + index).text().trim();
        var lengthtarget = $('#lengthtarget' + index).text().trim();
        var qtytarget = $('#qtytarget' + index).text().trim();
        var qcpasstarget = $('#qcpasstarget' + index).text().trim();
        var opnametarget = $('#opnametarget' + index).text().trim();
        var qcdatetarget = $('#qcdatetarget' + index).text().trim();
        var weighttarget = $('#weighttarget' + index).text().trim();
        var totalweighttarget = $('#totalweighttarget' + index).text().trim();

        var sentReq = {
            hmtarget: hmtarget,
            profiletarget: profiletarget,
            lengthtarget: lengthtarget,
            qtytarget: qtytarget,
            qcpasstarget: qcpasstarget,
            opnametarget: opnametarget,
            qcdatetarget: qcdatetarget,
            weighttarget: weighttarget,
            totalweighttarget: totalweighttarget
        };
        var table_target = $('#opnameSource').dataTable();
        var table_source = $('#opnameTarget').DataTable();

        var newTargetRow = table_target.fnAddData([
            hmtarget,
            profiletarget,
            lengthtarget,
            qtytarget,
            qcpasstarget,
            opnametarget,
            qcdatetarget,
            weighttarget,
            totalweighttarget,
            "<button type='button' class='btn btn-success' onclick=AddItem('" + counterrem + "')>OPNAME</button>"
        ]);
        var oSettings = table_target.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var rowsource = 'rowsource' + counterrem;
        var hmsource = 'hmsource' + counterrem;
        var profilesource = 'profilesource' + counterrem;
        var lengthsource = 'lengthsource' + counterrem;
        var qtysource = 'qtysource' + counterrem;
        var qcpasssource = 'qcpasssource' + counterrem;
        var opnamesource = 'opnamesource' + counterrem;
        var qcdatesource = 'qcdatesource' + counterrem;
        var weightsource = 'weightsource' + counterrem;
        var totalweightsource = 'totalweightsource' + counterrem;

        nTr.setAttribute('id', rowsource);
        $('td', nTr)[0].setAttribute('id', hmsource);
        $('td', nTr)[1].setAttribute('id', profilesource);
        $('td', nTr)[2].setAttribute('id', lengthsource);
        $('td', nTr)[3].setAttribute('id', qtysource);
        $('td', nTr)[4].setAttribute('id', qcpasssource);
        $('td', nTr)[5].setAttribute('id', opnamesource);
        $('td', nTr)[6].setAttribute('id', qcdatesource);
        $('td', nTr)[7].setAttribute('id', weightsource);
        $('td', nTr)[8].setAttribute('id', totalweightsource);
        table_source.row('#rowtarget' + index).remove().draw(false);
        counterrem--;
    }

    function SubmitOpname() {
        var headmark = [];
        var opnameqty = [];
        var opnameprice = [];

        //OPNAME QTY
        $('input[id^=opnameqty]').each(function () {
            var baris = $(this).attr('id').replace("opnameqty", "");
            if ($(this).val() == 0) {
                alert('qty is null on ');
            }

            else {
                opnameqty.push($(this).val());
                headmark.push($('#hmtarget' + baris).text().trim());
            }
        });

        //OPNAME PRICE
        $('input[id^=opnameprice]').each(function () {
            var baris = $(this).attr('id').replace("opnameprice", "");
            if ($(this).val() == 0) {
                alert("price is nulll");
            }

            else {
                opnameprice.push($(this).val());
            }
        });

        var DataPost = {
            headmark: headmark,
            opnameqty: opnameqty,
            opnameprice: opnameprice,
            date: $('#date').val(),
            opnameid: $('#opnameid').val(),
            subcontid: "<?php echo $subcont; ?>",
            projectno: $('#jobDropdown').val(),
            projectname: "<?php echo "$projectName"; ?>",
            idduplikat: "<?php echo "$idduplikat"; ?>",
            periode : "<?php echo "$periode";?>"
        };
        console.log(DataPost)
        var cf = confirm("DO YOU WANT SUBMIT?");
        if (cf == false) {
            return false;
        }
        else {
            console.log(DataPost);
            $.ajax({
                type: 'POST',
                url: "InputOpname/SubmitOpname.php",
                data: DataPost,
                success: function (response, textStatus, jqXHR) {
                    alert(response);
                    window.location.reload();
                }
            });
        }
    }

    function CekValidQty(index) {
        var maxqc = parseInt($('#qcpasstarget' + index).text().trim());
        var dt = parseInt($('#opnameqty' + index).val());
        if (dt < 1 || isNaN(dt) || dt > maxqc) {
            $('#opnameqty' + index).val(1);
        }
    }

    function CekValidPrice(index) {
        //var maxqc = parseInt($('#qcpasstarget' + index).text().trim());
        var dt = $('#opnameprice' + index).val();
        if (dt < 1 || isNaN(dt) || dt.contains(",")) {
            $('#opnameprice' + index).val(0);
        }
    }

    $(document).ready(function () {
        $('#opnameSource').DataTable({
            "iDisplayLength": 5
        });

        $('#opnameTarget').dataTable({
            "scrollY": "300px",
            "scrollCollapse": true,
            "paging": false
        });



        $('#submit-opname').prop('disabled', true);

    });
</script>