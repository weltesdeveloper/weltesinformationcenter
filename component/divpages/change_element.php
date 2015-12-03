<?php
require_once '../../dbinfo.inc.php';
require_once '../../FunctionAct.php';

$conn = oci_connect(ORA_CON_UN, ORA_CON_PW, ORA_CON_DB) or die;
switch ($_POST['action']) {
    case "subjob":
        $job = $_POST['job'];
        ?>
        <select class="selectpicker" data-live-search="true" id="subjob" onchange="ChangeSubjob();">
            <option value="" disabled selected>Select SUBJOB</option>
            <?php
            $sql = "SELECT PROJECT_NAME_OLD, PROJECT_NAME_NEW FROM VW_PROJ_INFO WHERE PROJECT_NO = '$job' AND PROJECT_TYP = 'STRUCTURE' ORDER BY PROJECT_NAME_NEW ASC";
            echo "$sql";
            $parse = oci_parse($conn, $sql);
            oci_execute($parse);
            while ($row = oci_fetch_array($parse)) {
                ?>
                <option value="<?php echo $row['PROJECT_NAME_OLD']; ?>"><?php echo $row['PROJECT_NAME_NEW']; ?></option>
                <?php
            }
            ?>
        </select>

        <script>
            $('#subjob').selectpicker();

            function ChangeSubjob() {
                var job = "<?php echo "$job"; ?>";
                var subjob = $('#subjob').val();
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: "divpages/change_element.php",
                    data: {job: job, subjob: subjob, action: "head_mark"},
                    beforeSend: function (xhr) {
                        $('#drawingTable').DataTable().destroy();
                        //                        $('#ProjectName_Comp').text(subjob);
                    },
                    success: function (response, textStatus, jqXHR) {
                        $("#drawingTable tbody").empty();
                        $.each(response, function (key, value) {
                            var label = 'danger';
                            var textSmall = 'NA';
                            if (value.MST == value.ASG) {
                                label = 'success';
                                textSmall = 'OK';
                            }
                            $("#drawingTable tbody").append("<tr>\n\
                                    <td style='cursor:pointer' id='hm" + key + "' onclick='DetailHM(" + key + "," + value.WEIGHT_COMP + ")'>" + value.HEAD_MARK + "</td>\n\
                                    <td><small class='label label-" + label + "'>" + textSmall + "</small></td>\n\
                                </tr>");
                        });
                        $("#drawingTable").dataTable({
                            "scrollY": "400px",
                            "scrollCollapse": true,
                            "paging": false,
                            "bInfo": false,
                            "oLanguage": {
                                "sSearch": ""
                            }
                        });
                    }
                });
            }

            function DetailHM(param, weight) {
                var head_mark = $('#hm' + param).text().trim();
                var job = "<?php echo "$job"; ?>";
                var subjob = $('#subjob').val();
                var weight = weight;
                //                alert(head_mark);
                $.ajax({
                    type: 'POST',
                    data: {headmark: head_mark, job: job, subjob: subjob, id1:weight},
                    url: "divpages/assigncomponent.php",
                    beforeSend: function (xhr) {
                        $('#maincontent').html();
                    },
                    success: function (response, textStatus, jqXHR) {
                        $('#maincontent').html(response);
                    }
                });
            }
        </script>
        <?php
        break;
    case "head_mark":
        $job = $_POST['job'];
        $subjob = $_POST['subjob'];
//        $sql = "SELECT HEAD_MARK, SUM (COMP_MST_QTY)MST, SUM (COMP_ASG_QTY)ASG
//                FROM VW_MD_INFO_COMP
//               WHERE PROJECT_NAME_OLD = '$subjob' 
//               GROUP BY HEAD_MARK";
        $sql = "SELECT HEAD_MARK, WEIGHT,total_qty,  SUM (COMP_MST_QTY) MST, SUM (COMP_ASG_QTY) ASG, SUM (COMP_WEIGHT) WEIGHT_COMP 
                    FROM VW_MD_INFO_COMP
                   WHERE PROJECT_NAME_OLD = '$subjob'
                GROUP BY HEAD_MARK, weight,total_qty";
        $parse = oci_parse($conn, $sql);
        oci_execute($parse);
        $array = array();
        while ($row1 = oci_fetch_array($parse)) {
            array_push($array, $row1);
        }
        echo json_encode($array);
        break;

    default:
        break;
}
