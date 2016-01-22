<?php
require_once '../dbinfo.inc.php';
require_once '../FunctionAct.php';
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
?>

<?php
$action = $_GET['action'];
switch ($action) {
    case 'show_do_number':
        $projNo = strval($_GET['projNo']);
        ?>
        <input type="hidden" name="projNo" value="<?php echo $projNo; ?>">
        <label for="orderNumber" class="col-sm-2 control-label"><font color="black">DELIVERY ORDER NUMBER</font></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="orderNumber" name="orderNumber" maxlength="20" placeholder="" value="<?php echo DONumberGenerate($projNo, $conn); ?>"></input>
        </div>

        <script type="text/javascript">
            $(function () {
                $("#orderNumber").on('blur', function () {
                    // console.log($(this).val());
                    $("#contenCOLI").empty();
                    $.get("showUpdateableElements.php",
                            {
                                action: "show_do_list",
                                projNo: "<?php echo $projNo ?>",
                                DO_no: $(this).val().replace(" ", "")
                            },
                    function (res) {
                        $("#contenCOLI").html(res);
                    }
                    );
                });

                // show colli
                $("#contenCOLI").empty();
                $.get("showUpdateableElements.php",
                        {
                            action: "show_do_list",
                            projNo: "<?php echo $projNo ?>",
                            DO_no: $('#orderNumber').val().replace(" ", "")
                        },
                function (res) {
                    $("#contenCOLI").html(res);
                }
                );
            });

        </script>
        <?php
        break;

    case 'show_do_list':
        $projNo = strval($_GET['projNo']);
        $DO_no = $_GET['DO_no'];
        $jmlDO_NO = SingleQryFld("SELECT COUNT(*) FROM MST_DELIV WHERE DO_NO = '$DO_no' ", $conn);

        $i = 0;
        ?>
        <table class="compact display" cellpadding="0" cellspacing="0" style="background-color:#F2F2F2;" id="tbl_packing">
            <thead>
                <tr>
                <th><input class="checkbox-inline" type="checkbox" id="chkAll" name="chkAll" style="display:none;" onchange="checkAll('chkAll', 'chkCN[]');"/></th>
                <th>Coli No.</th>
                <th>Pack Type</th>
                <th>Volume ( M<sup>3</sup>)</th>
                <th>Project Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SHOW COLI EXIST ON DO NO 
                // STRUCTURE
                $coliSql = "SELECT DISTINCT (MST.COLI_NUMBER),PACK_VOL,PACK_TYP,PROJECT_TYP "
                        . " FROM VW_PCK_INFO MST INNER JOIN DTL_DELIV DTL "
                        . " ON MST.COLI_NUMBER=DTL.COLI_NUMBER "
                        . " WHERE DO_NO = '$DO_no' ORDER BY COLI_NUMBER";
                $coliParse = oci_parse($conn, $coliSql);
                oci_execute($coliParse);
                while ($row = oci_fetch_array($coliParse)) {
                    $COLI_NUMBER = $row['COLI_NUMBER'];
                    $PACK_TYP = $row['PACK_TYP'];
                    $PACK_VOL = $row['PACK_VOL'];
                    $coliVolumeTotal = round(($PACK_VOL / 1000000000), 2);
                    ?>
                    <tr class="jcorgFilterTextParent">
                    <td style="width:30px;"><input class="style2" type="checkbox" name="chkCN[]" id="chkBOX_<?php echo $i ?>" checked="" value="<?php echo $row['COLI_NUMBER']; ?>"/></td>
                    <td class="jcorgFilterTextChild" style="width:250px;"><?php echo $COLI_NUMBER; ?></td>
                    <td style="width:100px;"><?php echo $PACK_TYP; ?></td>
                    <td style="width:auto;"><?php echo $coliVolumeTotal; ?></td>
                    <td><?php echo $row['PROJECT_TYP']; ?></td>
                    </tr>
                    <?php
                    $i++;
                }

                // TANKAGE
//              $coliSql = "SELECT DISTINCT (MST.COLI_NUMBER),PACK_VOL,PACK_TYP FROM VW_PCK_INFO@WELTES_TANKAGE_LINK MST INNER JOIN DTL_DELIV DTL ON MST.COLI_NUMBER=DTL.COLI_NUMBER WHERE DO_NO = '$DO_no' ORDER BY COLI_NUMBER"; 
//              $coliParse = oci_parse($conn, $coliSql);
//              oci_execute($coliParse);      
//              while($row = oci_fetch_array($coliParse))
//              {
//                  $COLI_NUMBER    = $row['COLI_NUMBER'];
//                  $PACK_TYP       = $row['PACK_TYP'];
//                  $PACK_VOL       = $row['PACK_VOL'];
//                  $coliVolumeTotal = round(($PACK_VOL / 1000000000),2);
//                
                ?>
        <!--                <tr class="jcorgFilterTextParent">
          <td style="width:30px;"><input class="style2" type="checkbox" name="chkCN[]" id="chkBOX_//<?php // echo $i    ?>" checked="" value="<?php // echo $row['COLI_NUMBER'];    ?>"/></td>
          <td class="jcorgFilterTextChild" style="width:250px;">//<?php // echo $COLI_NUMBER;    ?></td>
          <td style="width:100px;">//<?php // echo $PACK_TYP;    ?></td>
          <td style="width:auto;">//<?php // echo $coliVolumeTotal;    ?></td>
          <td>TANKAGE</td>
        </tr>-->
                <?php
//                $i++;
//              }
              
              
                // SHOW COLLI NOT DELIV FROM THIS PROJECT
                // STRUCTURE
                $coliSql = "SELECT DISTINCT(COLI_NUMBER),PACK_VOL,PACK_TYP,PROJECT_TYP FROM VW_PCK_INFO WHERE DLV_STAT = 'ND' AND PROJECT_NO = '$projNo' ORDER BY COLI_NUMBER";
                $coliParse = oci_parse($conn, $coliSql);
                oci_execute($coliParse);
                while ($row = oci_fetch_array($coliParse, OCI_ASSOC)) {
                    $COLI_NUMBER = $row['COLI_NUMBER'];
                    $PACK_TYP = $row['PACK_TYP'];
                    $PACK_VOL = $row['PACK_VOL'];
                    $coliVolumeTotal = round(($PACK_VOL / 1000000000), 2);
                    ?>
                    <tr class="jcorgFilterTextParent">
                    <td style="width:30px;"><input class="style2" type="checkbox" name="chkCN[]" id="chkBOX_<?php echo $i ?>" value="<?php echo $row['COLI_NUMBER']; ?>" onchange="checkSttus('chkAll', 'chkCN[]');" /></td>
                    <td class="jcorgFilterTextChild" style="width:250px;"><?php echo $COLI_NUMBER; ?></td>
                    <td style="width:100px;"><?php echo $PACK_TYP; ?></td>
                    <td style="width:auto;"><?php echo $coliVolumeTotal; ?></td>
                    <td><?php echo $row['PROJECT_TYP']; ?></td>
                    </tr>                         
                    <?php
                    $i++;
                }

                // TANKAGE
//              $coliSql = "SELECT DISTINCT(COLI_NUMBER),PACK_VOL,PACK_TYP FROM VW_PCK_INFO@WELTES_TANKAGE_LINK WHERE DLV_STAT = 'ND' AND PROJECT_NO = '$projNo' ORDER BY COLI_NUMBER"; 
//              $coliParse = oci_parse($conn, $coliSql);
//              oci_execute($coliParse);
//              while($row = oci_fetch_array($coliParse, OCI_ASSOC))
//              {
//                $COLI_NUMBER    = $row['COLI_NUMBER'];
//                $PACK_TYP       = $row['PACK_TYP'];
//                $PACK_VOL       = $row['PACK_VOL'];
//                $coliVolumeTotal = round(($PACK_VOL / 1000000000),2);
                ?>
        <!--                <tr class="jcorgFilterTextParent">
          <td style="width:30px;"><input class="style2" type="checkbox" name="chkCN[]" id="chkBOX_<?php // echo $i   ?>" value="<?php // echo $row['COLI_NUMBER'];   ?>" onchange="checkSttus('chkAll','chkCN[]');" /></td>
          <td class="jcorgFilterTextChild" style="width:250px;"><?php // echo $COLI_NUMBER;    ?></td>
          <td style="width:100px;"><?php // echo $PACK_TYP;    ?></td>
          <td style="width:auto;"><?php // echo $coliVolumeTotal;    ?></td>
          <td>TANKAGE</td>
        </tr>                         -->
                <?php
//                $i++;
//              }
                ?>
            </tbody>
        </table>
        <script type="text/javascript">
            $('#tbl_packing').DataTable({
                // "ordering": false,
                "paging": false,
                "searching": false,
                "scrollY": 350,
                "info": false,
                "orderFixed": [4, "asc"],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"visible": false, "targets": 4},
                    {"orderable": false, "targets": 0}
                ],
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    api.column(4, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="5" style="background-color:#F2DEDE;"><b><i>' + group + '</i></b></td></tr>'
                                    );

                            last = group;
                        }
                    });
                }
            })

            $('input[type="checkbox"].style2').checkbox({
                buttonStyle: 'btn-base',
                buttonStyleChecked: 'btn-success',
                checkedClass: 'icon-check',
                uncheckedClass: 'icon-check-empty'
            });

            if (parseInt("<?php echo $i ?>") > 0) {
                $("#SerchBox").show();
            }

            var jmlDO_NO = "<?php echo $jmlDO_NO ?>";
            if (jmlDO_NO > 0) {
                $.get("showUpdateableElements.php",
                        {
                            action: "show_do_list_element",
                            DO_no: "<?php echo $DO_no ?>"
                        },
                function (res) {
                    $("#txtarea_rems").html(res);
                }
                );
            } else {
                $("input[name=deliveryDate]").val("<?php echo date('m/d/Y') ?>");
                $("#spkNo").val("");
                $("#subject").val("");
                $("#PONo").val("");
                $("#vehicleno").val("");
                $("#transporterName").val("");
                $("#driverName").val("");
                $("#do_addr").val("");
                $("#attn").val("");
                $("#rems").val("");
                $("#do_city").val("");
                $("#do_spv").val("");

                $("input[type=submit][name=btnSubmit]").val("Submit Query");
            }

            // tagsinput
            var ts2 = $('#PickList');
            ts2.tagsinput('removeAll');
            ts2.tagsinput('refresh');

            $('input[type="checkbox"][id^="chkBOX_"]').on('change', function () {
                // console.log($(this).val());
                var idCHK = $(this).attr('id');
                if ($(this).is(':checked')) {
                    ts2.tagsinput('add', {id: idCHK, text: $(this).val()});
                    $('input[type="submit"]').removeAttr('disabled');
                } else {
                    ts2.tagsinput('remove', {id: idCHK, text: $(this).val()});
                }
            });
        </script>
        <?php
        break;

    case 'show_do_list_element':
        $DO_no = $_GET['DO_no'];

        $sqlPck = oci_parse($conn, "SELECT * FROM MST_DELIV WHERE DO_NO = '$DO_no' ");
        oci_execute($sqlPck);
        $rowPck = oci_fetch_array($sqlPck);

        $DO_DATE = new dateTime($rowPck['DO_DATE']);
        $DO_DATE = $DO_DATE->format("m/d/Y");

        // for show remarks
        if ($rowPck['DO_REMS'] != '') {
            echo '<textarea id="rems" name="rems" class="form-control bfh-phone">' . $rowPck['DO_REMS']->load() . '</textarea>';
        } else {
            echo '<textarea id="rems" name="rems" class="form-control bfh-phone"></textarea>';
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("input[name=deliveryDate]").val("<?php echo $DO_DATE ?>");
                $("#spkNo").val("<?php echo $rowPck['SPK_NO'] ?>");
                $("#subject").val("<?php echo $rowPck['SBJ'] ?>");
                $("#PONo").val("<?php echo $rowPck['PO_NO'] ?>");
                $("#vehicleno").val("<?php echo $rowPck['VHC_NO'] ?>");
                $("#transporterName").val("<?php echo $rowPck['T_PORTER'] ?>");
                $("#driverName").val("<?php echo $rowPck['DVR'] ?>");
                $("#do_addr").val("<?php echo $rowPck['DO_ADDR'] ?>");
                $("#attn").val("<?php echo $rowPck['ATTN'] ?>");

                // rems ditampilkan di atas
                $("#do_city").val("<?php echo $rowPck['DO_CITY'] ?>");
                $("#do_spv").val("<?php echo $rowPck['DO_SPV'] ?>");

                // tagsinput
                $('input[type="checkbox"][id^="chkBOX_"]:checkbox:checked').each(function () {
                    // console.log($(this).val());
                    var idCHK = $(this).attr('id');
                    $('#PickList').tagsinput('add', {id: idCHK, text: $(this).val()});
                });

                $("input[type=submit][name=btnSubmit]").val("Update DO NO");
                $('input[type="submit"][name="btnSubmit"]').removeAttr('disabled');

            });
        </script>
        <?php
        break;

    default:
        # code...
        break;
}
?>
<?php
?>
  