<div class="box box-danger">
    <div class="box-header with-border">
        <i class="fa fa-trash"></i>
        <h3 class="box-title"> Component <b><span id="txt_job_input_stock"></span> ~ Manual Stock Input</b>
            <small>Collect Waste Data & Insert It Here For Waste Management</small>
        </h3>
    </div> 

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Job</label>
                    <select class="selectpicker" data-live-search="true" data-width="100%" id="jobname_input_stock" data-live-search="true">

                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div id="inv-spec-type">
                    <label>Cutting</label>
                    <input type="text" id="dateInputStockCutt" data-datepick1="datepick1Value" class="form-control" placeholder="Tanggal Cutting">
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nesting File No</label>
                    <input type="text" id="nestingFile" class="form-control" placeholder="Input Nesting File No"/>
                </div>
            </div>
            <div class="col-md-6">
                <div id="inv-spec-type">
                    <label>Machine</label>
                    <input type="text" id="mesinCutt" class="form-control" placeholder="Input Mesin"/>
                    <input type="hidden" id="dateInputStockFinsh" data-datepick1="datepick1Value" class="form-control" placeholder="Tanggal Finishing">
                    <input type="hidden" id="mesinFinsh" class="form-control" placeholder="Input Mesin"/>
                </div>
            </div>
        </div>
    </div>

</div> 


<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <i class="fa fa-cubes"></i><h3 class="box-title">Input Stock Component <b><span id="txt_job_input_stock"></span> ~ <span id="txt_jobname_input_stock"></span></b></h3>
            </div><!-- /.box-header -->

            <div class="tab-pane active"  class="box-body">


                <div class="row">
                    <div class="col-md-12">
                        <table id="list_input_stock" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Part Name</th>
                                    <th>Profile Dimensi</th>
                                    <th class="hidden">Comp Lenght</th>
                                    <th>Qty</th>
                                    <th>Weight</th>
                                    <th>Cutt</th>
                                    <th class="hidden">Finsh</th>
                                    <th>Action</th>
                                    <th class="hidden">Nesting File</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>                                
                        </table>                        
                        <button type="button" id="btnInputStockAll" disabled class="btn btn-block btn-primary">Input Stock ALL</button>
                        <span id="checkID"></span>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col -->
</div><!-- /.row -->
<script>

    $(document).ready(function () {
        selectProject_input_stock();

        $('#jobname_input_stock').change(function () {
            var job = $('#jobname_input_stock option:selected').data('id');
            var jobname = $('#jobname_input_stock').val();

            $('#txt_job_input_stock').text(job);
            $('#txt_jobname_input_stock').text(jobname);
            $('#btnInputStockAll').prop('disabled', false);
            listTabel();
        });

        $('#btnInputStockAll').click(function () {
            if (confirm('Are You Sure to Input Stock This ALL Component`s ?')) {
                var compName = [];
                var cutt = [];
                var finsh = [];
                var job = $('#jobname_input_stock option:selected').data('id');
                var jobName = $('#jobname_input_stock').val();
                var dateCutt = $('#dateInputStockCutt').val();
                var dateFinsh = $('#dateInputStockFinsh').val();
                var mesinCutt = $('#mesinCutt').val();
                var mesinFinsh = $('#mesinFinsh').val();
                var nestingFile = $('#nestingFile').val();

                var rows = $('#list_input_stock').dataTable().fnGetNodes();

                for (var x = 0; x < rows.length; x++) {
                    var check = $(rows[x]).find("td:eq(8)").find('#checkInputStock').is(':checked');
                    if (check) {
                        compName.push($(rows[x]).find("td:eq(1)").text());
                        cutt.push($(rows[x]).find("td:eq(6)").find('input').val());
                        finsh.push($(rows[x]).find("td:eq(7)").find('input').val());
                    }
                }

                var sentReq = {
                    action: 'inputStockCompAll',
                    id1: compName,
                    id2: cutt,
                    id3: job,
                    id4: jobName,
                    id5: finsh,
                    id6: dateCutt,
                    id7: dateFinsh,
                    id8: mesinCutt,
                    id9: mesinFinsh,
                    id10: nestingFile
                };
                console.log(sentReq);
//                alert(compName);
                inputStockComp(sentReq);
            } else {
                return false;
            }
        });

    });


    function selectpicker_input_stock() {
        $('#jobname_input_stock').selectpicker();
        $('#dateInputStockCutt').datepicker();
        $('#dateInputStockFinsh').datepicker();
    }

    function selectProject_input_stock() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: {action: 'selectProject_input_stock'},
            success: function (json) {
                $("select#job_input_stock").empty();
                $("select#jobname_input_stock").empty();

                var isiOption = '<option value="">-[select project]-</option>';
                $.each(json, function (index, row) {

                    isiOption += '<optgroup label="' + row.PROJECT_NO + '">';

                    selectSubJob_input_stock(row.PROJECT_NO, function (data) {

                        $.each(data, function (index, rows) {
                            isiOption += '<option data-id=' + row.PROJECT_NO + ' value="' + rows.PROJECT_NAME + '">' + rows.PROJECT_NAME_NEW + '</option>';
                        });

                    });

                    isiOption += '</optgroup>';

                });
                $("select#job_input_stock").append(isiOption);
                $("select#jobname_input_stock").append(isiOption);

            },
            complete: function () {
                selectpicker_input_stock();
            }
        });
    }


    function selectSubJob_input_stock(project_no, callback) {
        $.ajax({
            async: false,
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: {action: 'selectSubJob_input_stock', id1: project_no},
            success: callback
        });

    }



    function listCompt(handleData) {
        var job = $('#jobname_input_stock option:selected').data('id');
        var jobname = $('#jobname_input_stock').val();

        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: {action: 'ViewList_input', id1: job, id2: jobname},
            success: function (json) {
                handleData(json);
//                console.log(json);
            }
        });
    }

    function listTabel() {

        listCompt(function (response) {

            var no = 0;

            var table = $('#list_input_stock').DataTable({
                destroy: true,
                processing: true,
                data: response,
                "columns": [
                    {
                        "className": 'details-control-stock',
                        "orderable": false,
                        "defaultContent": ''
                    },
                    {"data": 'COMP_NAME'},
                    {"data": "COMP_PROFILE"},
                    {
                        "data": "COMP_LENGTH",
                        "class": "hidden",
                    },
                    {"data": "COMP_MST_QTY"},
                    {"data": "COMP_WEIGHT"},
                ],
                "columnDefs": [
                    {
                        "visible": true,
                        "targets": [0],
                        "render": function (data, type, row, meta) {
                            no++;
                            return no;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [2],
                        "render": function (data, type, row, meta) {
                            var isi = row.COMP_PROFILE + " x " + row.COMP_LENGTH;
                            return isi;
                        }
                    },
                    {
                        "targets": [6],
                        "render": function (data, type, row, meta) {
                            var qty = row.COMP_MST_QTY;
                            var cutt = row.CUTTING;
                            var maxCutt = qty - cutt;
                            var inputDis = '';
                            if (qty == cutt) {
                                inputDis = 'disabled';
                            }
                            // max="' + maxCutt + '"
                            var cutt = '<td><input type="number" style="width:80px;" min="0" max="' + maxCutt + '" id="txtCutt' + no + '" \n\
                                        value="0" ' + inputDis + '/> <small class="label label-danger" style="color:#000;">' + maxCutt + '</small>  <small class="label label-default" style="color:#000;">' + row.CUTTING + '</small></td>';
                            return cutt;
                        }
                    },
                    {
                        "targets": [7],
                        "class": "hidden",
                        "render": function (data, type, row, meta) {
                            var cutt = row.CUTTING;
                            var finsh = row.FINISHING;
                            var max = cutt - finsh;
                            var inputDis = '';
                            if (cutt == finsh) {
//                                inputDis = 'disabled';
                            }
                            // max="' + max + '"
                            var finsh = '<td><input type="number" style="width:80px;" min="0" id="txtFinsh' + no + '" \n\
                                        value="0" ' + inputDis + '/> <small class="label label-default" style="color:#000;">' + row.FINISHING + '</small></td>';
                            return finsh;
                        }
                    },
                    {
                        "targets": [8],
                        "render": function (data, type, row, meta) {
                            var isi = '<input type="checkbox" id="checkInputStock" class="form-control">';
                            return isi;
                        }
                    },
                    {
                        "targets": [9],
                        "class": "hidden",
                        "render": function (data, type, row, meta) {
//                            var isi = "";
                            var isi = row.NESTING_FILE;
                            return isi;
                        }
                    }
                ],
                "order": [[1, 'asc']],
                // menambahkan ID di dalam <tr>
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', no);
                }
            });
        });
    }

    function ViewListNesting(compName, no) {
//        console.log(no);
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: {action: 'ViewListNesting', id1: compName},
            success: function (json) {
//                var x = json.NESTING_FILE
//                console.log(x);
//                var idTr = $('table#list_input_stock').find('tr#' + no);
//                idTr.find("td:eq(9)").text("A");
            }
        });
    }

    function inputStockComp(sentReq) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: sentReq,
            beforeSend: function (xhr) {
            },
            success: function (json) {
                console.log(json);
            },
            complete: function () {
                swal("Good job!", "You clicked the button!", "success")
                listTabel();
                $('#dateInputStockCutt').val("");
                $('#dateInputStockFinsh').val("");
                $('#nestingFile').val("");
                $('#mesinCutt').val("");
                $('#mesinFinsh').val("");
            }
        });
    }


</script>