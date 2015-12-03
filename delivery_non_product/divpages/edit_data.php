<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';
$conn = oci_pconnect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB);
session_start();
$username = $_SESSION['username'];
switch ($_POST['action']) {
    case "edit_table":
        $do_no = $_POST['do_no'];
        ?>
        <table class="table table-striped table-bordered" id="table-edit">
            <thead>
                <tr>
                    <th class="text-center">
                        ITEM&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-success btn-sm" onclick="AddItem();">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </th>
                    <th class="text-center" style="width: 80px;">
                        QTY
                    </th>
                    <th class="text-center" style="width: 80px;">
                        UNIT
                    </th>
                    <th class="text-center">
                        REMARK
                    </th>
                    <th class="text-center" style="width: 50px;">
                        ADD DTL/REMOVE
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM DTL_DO_NP WHERE DO_NO = '$do_no'";
                $parse = oci_parse($conn, $sql);
                oci_execute($parse);
                $parent = 0;
                while ($row3 = oci_fetch_array($parse)) {
                    ?>
                    <tr id="rowtarget<?php echo $parent; ?>">
                        <td>
                            <input type='text' value='<?php echo $row3['ITEM_NAME']; ?>' id='parent-name<?php echo $parent; ?>' class='form-control' maxlength='50'>
                        </td>
                        <td>
                            <input type='number' value='<?php echo $row3['ITEM_QTY']; ?>' id='parent-qty<?php echo $parent; ?>' class='form-control' placeholder='0'>
                        </td>
                        <td>
                            <input type='text' value='<?php echo $row3['ITEM_UNIT']; ?>' id='parent-unit<?php echo $parent; ?>' class='form-control' placeholder='Pcs, Botol')>
                        </td>
                        <td>
                            <input type='text' value='<?php echo $row3['ITEM_REMARK']; ?>' id='parent-remark<?php echo $parent; ?>'  class='form-control' placeholder='Remark Item'>
                        </td>
                        <td><span class='glyphicon glyphicon-plus' style='color:green; cursor:pointer;' onclick=AddRowDtl('<?php echo $parent; ?>')></span>
                            &nbsp;&nbsp;&nbsp;~~&nbsp;&nbsp;&nbsp;
                            <span class='glyphicon glyphicon-trash' style='color:red; cursor:pointer;' onclick=DeleteRow('<?php echo $parent; ?>')></span></td>
                    </tr>
                    <?php
                    $parent++;
                }
                ?>
            </tbody>
        </table>
        <script>
                                    var table = $('#table-edit').dataTable();
                                    var counter = $('#table-edit').dataTable().fnSettings().aoData.length;
                                    function AddItem() {
                                    var newTargetRow = table.fnAddData([
                                            "<input type='text' id='parent-name" + counter + "' class='form-control' onkeyup=UpperCase('" + counter + "','" + "parent-name" + "') maxlength='50'>",
                                            "<input type='number' value='' id='parent-qty" + counter + "' class='form-control' placeholder='0'>",
                                            "<input type='text' value='' id='parent-unit" + counter + "' class='form-control' placeholder='Pcs, Botol' onkeyup=UpperCase('" + counter + "','" + "parent-unit" + "')>",
                                            "<input type='text' value='' id='parent-remark" + counter + "' class='form-control' placeholder='Remark Item'>",
                                            "<span class='glyphicon glyphicon-plus' style='color:green; cursor:pointer;' onclick=AddRowDtl('" + counter + "')></span>"
                                            + "&nbsp;&nbsp;&nbsp;~~&nbsp;&nbsp;&nbsp;" +
                                            "<span class='glyphicon glyphicon-trash' style='color:red; cursor:pointer;' onclick=DeleteRow('" + counter + "')></span>"
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
                                            counter++;
                                    }
        </script>
        <?php
        break;
    default:
        break;
}