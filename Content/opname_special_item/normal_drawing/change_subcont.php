<?php
require_once '../../../dbinfo.inc.php';
require_once '../../../FunctionAct.php';
session_start();

$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
$username = htmlentities($_SESSION['username'], ENT_QUOTES);

$this_thurday = strtotime('thursday this week');
$last_thursday = strtotime("last Thursday", $this_thurday);
$last_thursday = date("d-m-Y", $last_thursday);
$job = $_POST['job'];
$subjob = $_POST['subjob'];
$subcont = rtrim($_POST['subcont']);
$tanggal = rtrim($_POST['tanggal']);
$query = "SELECT MAX(DISTINCT(OPN_PERIOD))+1 FROM MST_OPNAME WHERE PROJECT_NO = '$job' AND SUBCONT_ID = '$subcont' AND to_date(TO_CHAR(OPN_ACT_DATE, 'DD-MM-YYYY'), 'dd-mm-yyyy') <= to_date('$last_thursday', 'dd-mm-yyyy')";
//echo "$query";
$newPeriode = SingleQryFld("$query", $conn);
if ($newPeriode == "") {
    $newPeriode = 1;
}

$subjobInit = SingleQryFld("SELECT PROJECT_CODE FROM VW_PROJ_INFO WHERE PROJECT_NAME_OLD = '$subjob'", $conn);
$subcontInit = SingleQryFld("SELECT SUBCONT_CODE FROM SUBCONTRACTOR WHERE SUBCONT_ID = '$subcont'", $conn);
$OPNAME_IDX = "$job-$subjobInit-$subcontInit-$newPeriode-$tanggal";
$OPNAME_ID = str_replace(" ", "", $OPNAME_IDX);
$finalOpnameSql = "SELECT VSOR.* FROM VW_SHOW_OPNAME_PRC VSOR WHERE VSOR.PROJECT_NAME = :PROJNAME "
        . "AND VSOR.SUBCONT_ID = :SUBCONT AND VSOR.QCPASS <> 0 AND VSOR.QCPASS <> VSOR.QTY_OPN ORDER BY VSOR.HEAD_MARK";
//echo "$finalOpnameSql";
$finalOpnameParse = oci_parse($conn, $finalOpnameSql);
oci_bind_by_name($finalOpnameParse, ":PROJNAME", $subjob);
oci_bind_by_name($finalOpnameParse, ":SUBCONT", $subcont);
oci_execute($finalOpnameParse);
?>
<div class="col-sm-12">
    <table id="opnameSource" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center" style="vertical-align: middle;">HEADMARK</th>
                <th class="text-center" style="vertical-align: middle;">PROFILE</th>
                <th class="text-center" style="vertical-align: middle;">WEIGHT</th>
                <th class="text-center" style="vertical-align: middle;">QC <br> QTY</th>
                <th class="text-center" style="vertical-align: middle;">QC DATE</th>
                <th class="text-center" style="vertical-align: middle;">ALREADY<br>OPNAME</th>
                <th class="text-center" style="vertical-align: middle;">% SELESAI</th>
                <th class="text-center" style="vertical-align: middle;">% SISA</th>
                <th class="text-center" style="vertical-align: middle;">ACTION</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $i = 0;
            while ($row = oci_fetch_array($finalOpnameParse)) {
                ?>
                <tr id="rowsource<?php echo "$i"; ?>">
                    <td id="hmsource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo $row['HEAD_MARK']; ?>
                    </td>
                    <td id="profilesource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo $row['PROFILE'] . " ($row[DWG_TYP])"; ?>
                    </td>
                    <td id="weightsource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo $row['UNIT_WEIGHT']; ?>
                    </td>
                    <td id="qcpasssource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo $row['QCPASS']; ?>
                    </td>
                    <td id="qcdatesource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo $row['QCPASSDATE']; ?>
                    </td>
                    <td id="opnamesource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo ($row['QTY_OPN']); ?>
                    </td>
                    <td id="selesaisource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo ($row['ALREQ_PROCENTAGE']); ?>
                    </td>
                    <td id="sisasource<?php echo "$i"; ?>" class="text-center" style="vertical-align: middle;">
                        <?php echo ($row['REMAINING_PROGRESS']); ?>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <button type='button' onclick="AddItem('<?php echo "$i"; ?>')" class="btn btn-primary">ADD</button>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <table id="opnameTarget" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center" style="vertical-align: middle;">HEADMARK</th>
                <th class="text-center" style="vertical-align: middle;">PROFILE</th>
                <th class="text-center" style="vertical-align: middle;">WEIGHT</th>
                <th class="text-center" style="vertical-align: middle;">QC <br> QTY</th>
                <th class="text-center" style="vertical-align: middle;">QC DATE</th>
                <th class="text-center" style="vertical-align: middle;">ALREADY<br>OPNAME</th>
                <th class="text-center" style="vertical-align: middle;">% SELESAI</th>
                <th class="text-center" style="vertical-align: middle;">% SISA</th>
                <th class="text-center" style="vertical-align: middle;">OPNAME<br>QTY</th>
                <th class="text-center" style="vertical-align: middle;">OPNAME<br>PRICE</th>
                <th class="text-center" style="vertical-align: middle;">ACTION</th>
            </tr>
        </thead>

        <tbody>

        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <!--<div class="panel-footer" style="background-color: gray; padding-bottom: 15px;">-->
    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <button type="button" class="btn btn-success col-sm-12" onclick="SubmitOpname();" disabled id="submit-btn">SUBMIT OPNAME</button>
        </div>
    </div>
    <!--</div>-->
</div>


<script>
    var _periode = "<?php echo "$newPeriode"; ?>";
    var _opnameid = "<?php echo "$OPNAME_ID"; ?>";
    $('#periode').val(_periode);
    $('#opname-id').val(_opnameid);
    var counteradd = 0;
    var counterrem = -1;

    function AddItem(index) {
        var hmsource = $('#hmsource' + index).text().trim();
        var profilesource = $('#profilesource' + index).text().trim();
        var weightsource = $('#weightsource' + index).text().trim();
        var qcpasssource = $('#qcpasssource' + index).text().trim();
        var qcdatesource = $('#qcdatesource' + index).text().trim();
        var opnamesource = $('#opnamesource' + index).text().trim();
        var selesai = $('#selesaisource' + index).text().trim();
        var sisa = $('#sisasource' + index).text().trim();

        var remaining = parseInt(qcpasssource) - parseInt(opnamesource);

        var sentReq = {
            hmsouurce: hmsource,
            qcpasssource: qcpasssource,
            opnamesource: opnamesource,
            qcdatesource: qcdatesource,
            weightsource: weightsource
        };
        var table_target = $('#opnameTarget').dataTable();
        var table_source = $('#opnameSource').DataTable();
        var newTargetRow = table_target.fnAddData([
            hmsource,
            profilesource,
            weightsource,
            qcpasssource,
            qcdatesource,
            opnamesource,
            selesai,
            sisa,
            "<input type=number id='opnameqty" + counteradd + "' min=0 max=" + remaining + " value=" + remaining + " onchange=CekValidQty('" + counteradd + "') style='width:100px;'>",
            "<input type=text id=opnameprice" + counteradd + " value=0 onchange=CekValidPrice('" + counteradd + "') style='width:80px;'>",
            "<button type=button class='btn btn-warning' onclick=RemoveItem('" + counteradd + "')>REM</button>"
        ]);
        var oSettings = table_target.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var rowtarget = 'rowtarget' + counteradd;
        var hmtarget = 'hmtarget' + counteradd;
        var profiletarget = 'profiletarget' + counteradd;
        var weighttarget = 'weighttarget' + counteradd;
        var qcpasstarget = 'qcpasstarget' + counteradd;
        var qcdatetarget = 'qcdatetarget' + counteradd;
        var opnametarget = 'opnametarget' + counteradd;
        var selesaitarget = 'selesaitarget' + counteradd;
        var sisatarget = 'sisatarget' + counteradd;

        nTr.setAttribute('id', rowtarget);
        $('td', nTr)[0].setAttribute('id', hmtarget);
        $('td', nTr)[1].setAttribute('id', profiletarget);
        $('td', nTr)[2].setAttribute('id', weighttarget);
        $('td', nTr)[3].setAttribute('id', qcpasstarget);
        $('td', nTr)[4].setAttribute('id', qcdatetarget);
        $('td', nTr)[5].setAttribute('id', opnametarget);
        $('td', nTr)[6].setAttribute('id', selesaitarget);
        $('td', nTr)[7].setAttribute('id', sisatarget);

        $('td', nTr)[0].setAttribute('class', "text-center");
        $('td', nTr)[1].setAttribute('class', "text-center");
        $('td', nTr)[2].setAttribute('class', "text-center");
        $('td', nTr)[3].setAttribute('class', "text-center");
        $('td', nTr)[4].setAttribute('class', "text-center");
        $('td', nTr)[5].setAttribute('class', "text-center");
        $('td', nTr)[6].setAttribute('class', "text-center");
        $('td', nTr)[7].setAttribute('class', "text-center");
        $('td', nTr)[8].setAttribute('class', "text-center");
        $('td', nTr)[9].setAttribute('class', "text-center");
        $('td', nTr)[10].setAttribute('class', "text-center");

        table_source.row('#rowsource' + index).remove().draw(false);
        counteradd++;
        $('#submit-btn').removeAttr("disabled");
    }

    function RemoveItem(index) {
        var hmtarget = $('#hmtarget' + index).text();
        var profiletarget = $('#profiletarget' + index).text();
        var weighttarget = $('#weighttarget' + index).text().trim();
        var qcpasstarget = $('#qcpasstarget' + index).text().trim();
        var qcdatetarget = $('#qcdatetarget' + index).text().trim();
        var opnametarget = $('#opnametarget' + index).text().trim();
        var selesaitarget = $('#selesaitarget' + index).text().trim();
        var sisatarget = $('#sisatarget' + index).text().trim();

        var sentReq = {
            hmtarget: hmtarget,
            weighttarget: weighttarget,
            qcpasstarget: qcpasstarget,
            qcdatetarget: qcdatetarget,
            opnametarget: opnametarget

        };
        var table_target = $('#opnameSource').dataTable();
        var table_source = $('#opnameTarget').DataTable();

        var newTargetRow = table_target.fnAddData([
            hmtarget,
            profiletarget,
            weighttarget,
            qcpasstarget,
            qcdatetarget,
            opnametarget,
            selesaitarget,
            sisatarget,
            "<button type='button' class='btn btn-primary' onclick=AddItem('" + counterrem + "')>ADD</button>"
        ]);
        var oSettings = table_target.fnSettings();
        var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
        var rowsource = 'rowsource' + counterrem;
        var hmsource = 'hmsource' + counterrem;
        var profilesource = 'profilesource' + counterrem;
        var weightsource = 'weightsource' + counterrem;
        var qcpasssource = 'qcpasssource' + counterrem;
        var qcdatesource = 'qcdatesource' + counterrem;
        var opnamesource = 'opnamesource' + counterrem;
        var selesaisource = 'selesaisource' + counterrem;
        var sisasource = 'sisasource' + counterrem;

        nTr.setAttribute('id', rowsource);
        $('td', nTr)[0].setAttribute('id', hmsource);
        $('td', nTr)[1].setAttribute('id', profilesource);
        $('td', nTr)[2].setAttribute('id', weightsource);
        $('td', nTr)[3].setAttribute('id', qcpasssource);
        $('td', nTr)[4].setAttribute('id', qcdatesource);
        $('td', nTr)[5].setAttribute('id', opnamesource);
        $('td', nTr)[6].setAttribute('id', selesaisource);
        $('td', nTr)[7].setAttribute('id', sisasource);

        //add class
        $('td', nTr)[0].setAttribute('class', "text-center");
        $('td', nTr)[1].setAttribute('class', "text-center");
        $('td', nTr)[2].setAttribute('class', "text-center");
        $('td', nTr)[3].setAttribute('class', "text-center");
        $('td', nTr)[4].setAttribute('class', "text-center");
        $('td', nTr)[5].setAttribute('class', "text-center");
        $('td', nTr)[6].setAttribute('class', "text-center");
        $('td', nTr)[7].setAttribute('class', "text-center");
        $('td', nTr)[8].setAttribute('class', "text-center");
        table_source.row('#rowtarget' + index).remove().draw(false);
        counterrem--;
        var len = $('#opnameTarget').dataTable().fnGetData().length;
        if (len == 0) {
            $('#submit-btn').prop("disabled", true);
        }
    }

    function SubmitOpname() {
        var job = "<?php echo "$job"; ?>";
        var subjob = "<?php echo "$subjob"; ?>";
        var subcont = "<?php echo "$subcont"; ?>";
        var tanggal_opname = $('#tgl-opname').val();
        var periode = $('#periode').val();
        var opname_id = $('#opname-id').val();
        if (periode == "") {
            alert("ISI PERIODE !!!");
        }
        else {
            var headmark = [];
            var opnameqty = [];
            var opnameprice = [];
            var opnamepersen = [];
            var rows = $('#opnameTarget').dataTable().fnGetNodes();
            for (var x = 0; x < rows.length; x++)
            {
                headmark.push($(rows[x]).find("td:eq(0)").text());
                opnamepersen.push($(rows[x]).find("td:eq(7)").text().trim());
                opnameqty.push($(rows[x]).find("td:eq(8)").find('input').val());
                opnameprice.push($(rows[x]).find("td:eq(9)").find('input').val());
            }
            var sentReq = {
                job: job,
                subjob: subjob,
                subcont: subcont,
                tanggal_opname: tanggal_opname,
                periode: periode,
                headmark: headmark,
                opnameqty: opnameqty,
                opnameprice: opnameprice,
                opname_id: opname_id,
                opnamepersen: opnamepersen
            };
            console.log(sentReq);
            var cf = confirm("APA ANDA INGIN SUBMIT OPNAME " + sentReq.job + " SUBCONT " + sentReq.subcont + " PERIODE " + sentReq.periode + " ?");
            if (cf == true) {
                $.ajax({
                    type: 'POST',
                    url: "normal_drawing/submit_opname.php",
                    data: sentReq,
                    success: function (response, textStatus, jqXHR) {
                        if (response.indexOf("GAGAL") == -1) {
                            alert("SUKSES INSERT");
                            window.location.reload();
                        } else {
                            alert(response);
                        }
                    }
                });
            } else {
                return false;
            }
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

    $(function () {
        $('#opnameSource').dataTable({
            "lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "ALL"]]
        });
        $('#opnameTarget').dataTable({
            "scrollY": "500px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": false
        });
    });

</script>
