<div class="row">
    <div class="col-xs-12">
        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="fa fa-trash"></i>
                <h3 class="box-title">Revisi Component </h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <label class="control-label">Select Project </label>
                <div class="row">
                    <div class="col-md-10">
                        <select class="form-control selectpicker" id="projectNo" data-live-search="true">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="btnAddComp" class="btn btn-block btn-primary">Add Component</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="fa fa-cubes"></i><h3 class="box-title"> <b><span id="txt_job_input_stock"></span> ~ <span id="txt_jobname_input_stock"></span></b></h3>
            </div><!-- /.box-header -->
            <div class="tab-pane active"  class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <table id="TabelRev" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Assembly</th>
                                    <th>Part Name</th>
                                    <th>Profile Dimensi</th>
                                    <th>Lenght</th>
                                    <th>Qty</th>
                                    <th>Weight</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>                                
                        </table>                    
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col -->
</div><!-- /.row -->

<!--<script src="" type="text/javascript"></script>-->

<script type="text/javascript">
    $(document).ready(function () {
        selectProject();

        $('#projectNo').change(function () {
            $('#btnRev').prop('disabled', false);
            var job = $('#projectNo option:selected').data('id');
            var jobname = $('#projectNo').val();

            $('#txt_job_input_stock').text(job);
            $('#txt_jobname_input_stock').text(jobname);

            listTabelRev();
        });

    });

    function selectProject() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: {action: 'selectProject_input_stock'},
            success: function (json) {
                $("select#projectName").empty();
                $("select#projectNo").empty();

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
                $("select#projectName").append(isiOption);
                $("select#projectNo").append(isiOption);

            },
            complete: function () {
                selectpicker__();
            }
        });
    }


    function selectSubJob(project_no, callback) {
        $.ajax({
            async: false,
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstock.php",
            data: {action: 'selectSubJob_input_stock', id1: project_no},
            success: callback
        });

    }

    function selectpicker__() {
        $('#projectNo').selectpicker();
    }


    function listComptRev(handleData) {
        var job = $('#projectNo option:selected').data('id');
        var jobname = $('#projectNo').val();

        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processRev.php",
            data: {action: 'ViewListCompRev', id1: job, id2: jobname},
            success: function (json) {
                handleData(json);
            }
        });
    }

    function listTabelRev() {

        listComptRev(function (response) {
            var no = 0;

            var table = $('#TabelRev').DataTable({
                destroy: true,
                processing: true,
                data: response,
                "columns":
                        [
                            {"data": null},
                            {"data": 'HEAD_MARK'},
                            {"data": 'COMP_NAME'},
                            {"data": "COMP_PROFILE"},
                            {"data": "COMP_MST_QTY"},
                            {"data": "COMP_WEIGHT"},
                        ],
                "columnDefs":
                        [
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
                                    var isi = '<a href="#" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.COMP_ID + '" data-set="COMP_NAME" >' + row.COMP_NAME + '</a>';
                                    return isi;
                                }
                            },
                            {
                                "visible": true,
                                "targets": [3],
                                "render": function (data, type, row, meta) {
                                    var isiProfile = '<a href="#" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.COMP_ID + '" data-set="COMP_PROFILE" >' + row.COMP_PROFILE + '</a>';
                                    return isiProfile;
                                }
                            },
                            {
                                "visible": true,
                                "targets": [4],
                                "render": function (data, type, row, meta) {
                                    var isiLenght = '<a href="#" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.COMP_ID + '" data-set="COMP_LENGTH" >' + row.COMP_LENGTH + '</a>';
                                    return isiLenght;
                                }
                            },
                            {
                                "visible": true,
                                "targets": [5],
                                "render": function (data, type, row, meta) {
                                    var isiQty = '<a href="#" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.COMP_ID + '" data-set="COMP_MST_QTY" >' + row.COMP_MST_QTY + '</a>';
                                    return isiQty;
                                }
                            },
                            {
                                "visible": true,
                                "targets": [6],
                                "render": function (data, type, row, meta) {
                                    var isiWeight = '<a href="#" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.COMP_ID + '" data-set="COMP_WEIGHT" >' + row.COMP_WEIGHT + '</a>';
                                    return isiWeight;
                                }
                            },                            
                            {
                                "visible": true,
                                "targets": [7],
                                "render": function (data, type, row, meta) {
                                    var isiHapus = '<a href="#" style="cursor:pointer" class="text-center" onClick="haspusComp(' + row.COMP_ID + ')"><small class="label label-danger"><i class="fa fa-trash"></i></small></a>';
                                    return isiHapus;
                                }
                            }
                        ],
                "drawCallback": function (settings) {
                    initEditable();
                },
                "order": [[1, 'asc']],
                // menambahkan ID di dalam <tr>
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', no);
                }
            });
        });
    }


    function initEditable() {
        $('#TabelRev .initStockClass').editable({
            title: function () {
                $(this).attr('title');
            },
            validate: function (value) {
                if ($.trim(value) == '') {
                    return 'This field is required';
                }
            },
            success: function (response, newValue) {

                var elmnt = $(this);
                var comp_id = elmnt.data('pk');
                var setDataUpdate = elmnt.data('set');

                if (newValue !== '') {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: "divpages/process/processRev.php",
                        data: {
                            action: 'edit_data',
                            comp_id__: comp_id,
                            setDataUpdate__: setDataUpdate,
                            newValue: newValue.trim()
                        },
                        success: function (json) {
                            if (json !== 'success') {
                                elmnt.text('FAILED');
                                elmnt.css("color", "red").css("font-style", "italic");
                            }
                        }
                    });
                }
            },
            error: function (response, newValue)
            {
                console.log('FAILED');
                console.log(response);
                console.log(newValue);
            }
        });
    }

    function haspusComp(id) {
        alert(id);
    }
</script>