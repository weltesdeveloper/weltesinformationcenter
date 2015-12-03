<?php
require_once './SpreadsheetReader.php';
?>

<div class="row">
    <div class="col-xs-12">
        <a href="../../../C:/xampp/htdocs/csv/test.php"></a>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Import Drawing Spreadsheet Here </h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <label class="control-label">Select Project &amp; File</label>
                <!--<input id="input-1a" type="file" class="file" data-show-preview="false" name="file_data">-->
                <div class="row">
                    <div class="col-md-4">
                        <select class="selectpicker" data-live-search="true" data-width="100%" id="SelectProjectName" data-live-search="true">
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="file" id="fileUpload" />
                    </div>
                    <div class="col-md-4">
<!--                        <input type="button" id="upload" value="Upload" />-->
                        <button type="button" id="upload" disabled class="btn btn-block btn-primary">Parse This File</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Import Job-SubJob</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="col-xs-3" style="display:none;" id="wait"><img src="../AdminLTE/img/loading_dark.gif" alt="" style="width: 400px; height: 32px; position: relative;"/></div>
                <div class="tab-pane active" id="dvCSV">
                </div>

                <table id="comp-monitor" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <!--<th>Count</th>-->
                            <th>Component Name</th>
                            <th class="hide">Drawing No.</th>
                            <th>QTY</th>
                            <th>Component Profile</th>
                            <th>Component Lenght</th>
                            <th>Component Weight</th>
                            <!--<th>Exclude</th>-->
                            <th>Dwg Status</th>
                        </tr>
                    </thead>
                </table>                
                <button type="button" id="btnImport" disabled class="btn btn-block btn-primary">Import This File</button>
                <span id="checkID"></span>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    var tble_comp_elment = $('#comp-monitor');
    var tble_comp_str = {
        "columnDefs": [
            //{"visible": true, "targets": 1}
        ],
        "orderFixed": [1, 'asc'],
        "displayLength": 50,
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

            api.column(1, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                            '<tr class="group"><td colspan="8"><b>' + group + '</b></td></tr>'
                            );
                    last = group;
                }
            });
        }
    };
//    var tble_comp = tble_comp_elment.DataTable(tble_comp_str);

    selectProject();
    $("select#SelectProjectName").on('change', function () {
        $('#upload').prop('disabled', false);
    });

    $("#upload").bind("click", function () {
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.xlsx|.xls)$/;
        if (regex.test($("#fileUpload").val().toLowerCase())) {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                    tble_comp_elment.DataTable().destroy();
                    tble_comp_elment.find('tbody').empty();

                    var tble_comp = tble_comp_elment.DataTable(tble_comp_str);
                    var rows = e.target.result.split("\n");
                    var no = 0;
                    for (var i = 0; i < rows.length; i++) {
                        no++;
                        var cells = rows[i].split(",");
                        if (cells[0] != '') {
                            var rowNode = tble_comp.row.add([
                                cells[0],
                                cells[1],
                                cells[2], //HEADMARK
                                cells[3],
                                cells[4],
                                cells[5],
                                '<small class="label label-warning" id="infoComp' + no + '"><i class="fa fa-clock-o"></i> NA</small>'
                            ]).draw().nodes();

                            $(rowNode).attr('id', 'row_' + no);
                            $(rowNode).find("td:eq(1)").addClass('hide');
                        }
                    }
                }
                ;
                reader.readAsText($("#fileUpload")[0].files[0]);

                $('#btnImport').prop('disabled', false);
            } else {
                alert("This browser does not support HTML5.");
            }
        }
        else {
            alert("Please upload a valid CSV file.");
        }
    });

    $('#btnImport').click(function () {
        if (confirm('Are You Sure to Import This Item`s ?')) {
            var comp_name = [];
            var drawing = [];
            var qty = [];
            var comp = [];
            var comp_lenght = [];
            var comp_weight = [];
            var row_id = [];
            var job = $('#SelectProjectName option:selected').data('id');
            var jobname = $('#SelectProjectName').val();

            var rows = $('#comp-monitor').dataTable().fnGetNodes();
            for (var x = 0; x < rows.length; x++) {
                row_id.push($(rows[x]).attr('id'));

                comp_name.push($(rows[x]).find("td:eq(0)").text());
                drawing.push($(rows[x]).find("td:eq(1)").text());
                qty.push($(rows[x]).find("td:eq(2)").text());
                comp.push($(rows[x]).find("td:eq(3)").text().trim());
                comp_lenght.push($(rows[x]).find("td:eq(4)").text().trim());
                comp_weight.push($(rows[x]).find("td:eq(5)").text().trim());
            }

            var sentReq = {
                action: 'inputComp',
                id1: comp_name,
                id2: drawing,
                id3: qty,
                id4: comp,
                id5: comp_lenght,
                id6: comp_weight,
                id7: job,
                id8: jobname,
                row_id: row_id
            };
            inputComp(sentReq);
        } else {
            return false;
        }
    });


// KUMPULAN FUNGSI
//    function check_comp(sentReq) {
//        console.log(sentReq);
//        $.ajax({
//            type: "POST",
//            dataType: 'json',
//            url: "divpages/process/processdata.php",
//            data: sentReq,
//            beforeSend: function (xhr) {
//            },
//            success: function (json) {
//                for (var x = 0; x < json.length; x++) {
//                    $('span#checkID').text(json[x]);
//                    var rows = $('#comp-monitor').dataTable().fnGetNodes();
//                    for (var y = 0; y < rows.length; y++) {
//                        if (json[x] == '1') {
//                            var z = x + 1;
//                            $(rows[y]).find("td:eq(6)").find('small#infoComp' + z).text('exist').addClass('label-danger');
//                        }
//                    }
//                }
//            },
//            complete: function () {
//                inputComp();
//            }
//        });
//    }

    function inputComp(sentReq) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processdata.php",
            data: sentReq,
            beforeSend: function (xhr) {
                $('#wait').show();
                tble_comp_elment.DataTable().destroy();
            },
            success: function (json) {
//                var rows = tble_comp_elment.dataTable().fnGetNodes();

                $.each(json, function (i, row_resp) {
                    if (row_resp.respons == 'SUCCESS') {
                        $('#' + row_resp.row_id).find('small').addClass('label-primary').text(row_resp.respons);
                    } else {
                        $('#' + row_resp.row_id).find('small').addClass('label-danger').text(row_resp.respons);
                    }
//                    console.log(row_resp.row_id + ' -- ' + row_resp.respons);
                });
            },
            complete: function () {
                tble_comp_elment.DataTable(tble_comp_str);
                $('#wait').hide();
            }
        });
    }

    function selectpicker() {
        $('#SelectProjectName').selectpicker();
    }

    function selectProject() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listcomponent_JSON.php',
            data: {action: 'selectProject'},
            beforeSend: function (xhr) {
                $("select#SelectProjectName").empty();
            },
            success: function (json) {
                var isiOption = '<option value="">-[select project]-</option>';
                $.each(json, function (index, row) {
                    isiOption += '<optgroup label="' + row.PROJECT_NO + '">';
                    selectSubJob(row.PROJECT_NO, function (data) {
                        $.each(data, function (index, rows) {
                            isiOption += '<option data-id=' + row.PROJECT_NO + ' value="' + rows.PROJECT_NAME + '">' + rows.PROJECT_NAME_NEW + '</option>';
                        });
                    });
                    isiOption += '</optgroup>';
                });
                $("select#SelectProjectName").append(isiOption);
            },
            complete: function () {
                selectpicker();
            }
        });
    }

    function selectSubJob(project_no, callback) {
        $.ajax({
            async: false,
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listcomponent_JSON.php',
            data: {action: 'selectSubJob', id: project_no},
            success: callback
        });
    }
</script>

<style>
    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
        padding: 1px 10px;
    }

    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        border-top: 1px solid #ddd;
        line-height: 1.42857;
        vertical-align: middle;
    }
</style>