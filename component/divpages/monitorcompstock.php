<style>
    td.details-control-list {
        background: url('icon/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control-list {
        background: url('icon/details_close.png') no-repeat center center;
    }
</style>
<div class="box box-primary">
    <div class="box-header with-border">
        <i class="fa fa-desktop"></i>
        <h3 class="box-title"> Monitoring Component<b><span id="txt_job_input_stock"></span> By Plat</b>
            <!--<small>Collect Waste Data & Insert It Here For Waste Management</small>-->
        </h3>
    </div> 

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Select Project</label>
                    <select class="selectpicker" data-live-search="true" data-width="100%" id="jobname" data-live-search="true">

                    </select>
                </div>
            </div>
        </div>
    </div>
</div> 

<hr>

<div class="box box-primary">
    <div class="box-header with-border">
        <i class="fa fa-cubes"></i>
        <h3 class="box-title"> Monitoring Component<b><span id="txt_job_input_stock"></span> By Assign</b>
            <!--<small>Collect Waste Data & Insert It Here For Waste Management</small>-->
        </h3>
    </div> 
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">                       
                            <table id="example" class="display" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Part Name</th>
                                        <th>Profile Dimensi</th>
                                        <th>Qty</th>
                                        <th>Weight</th>
                                        <th>Cutting</th>
                                        <th>Finishing</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

<script>

    $(document).ready(function () {
        selectProject();


        $('#jobname').change(function () {
            listTabel();
        });
    });

    /* Formatting function for row details - modify as you need */
    function format(d) {
        var hm = d.COMP_NAME;
        detilCompHM(hm)
        return '<div class="box-body table-responsive"><table id="tabelDetilCompPlat" border="1" class="table table-bordered table-striped"  >' +
                '<thead>' +
                '<tr>' +
                '<td rowspan="2" align="center">No.</td>' +
                '<td colspan="3" align="center">Cutting</td>' +
                '<td colspan="3" align="center">Finishing</td>' +
                '<td rowspan="2" align="center">Nesting File</td>' +
                '</tr>' +
                '<tr>' +
                '<th>Tgl</th>' +
                '<th>Qty</th>' +
                '<th>Mesin</th>' +
                '<th>Tgl</th>' +
                '<th>Qty</th>' +
                '<th>Mesin</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '</tbody>' +
                '</table></div>';
    }

    function detilCompHM(CompName) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listcomponent_JSON.php',
            data: {action: 'detilCompPlat', CompName__: CompName},
            beforeSend: function () {
            },
            success: function (json) {
                var no = 0;
                var table = $('#tabelDetilCompPlat').DataTable({
                    destroy: true,
                    processing: true,
                    data: json,
                    "columns": [
                        {"data": null},
                        {"data": "TANGGAL_CUTTING"},
                        {"data": "CUTTING"},
                        {"data": "MESIN_CUTT"},
                        {"data": "TANGGAL_FINISHING"},
                        {"data": "FINISHING"},
                        {"data": "MESIN_FINSH"},
                        {"data": "NESTING_FILE"},
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
                                var isiCutt = '<a href="#" style="cursor:pointer" class="initStockClass text-center" data-pk="' + row.NESTING_FILE + '" data-pk2="' + row.COMP_NAME + '" data-set="CUTTING" >' + row.CUTTING + '</a>';
                                return isiCutt;
                            }
                        },
                    ],
                    "order": [[1, 'asc']],
                    "drawCallback": function (settings) {
                        initEditable();
                    }
                });
            },
            complete: function () {
            }
        });
    }

    function initEditable() {
        $('#tabelDetilCompPlat .initStockClass').editable({
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
                var pk_NestingFile = elmnt.data('pk');
                var pk_compName = elmnt.data('pk2');
                var setDataUpdate = elmnt.data('set');

                if (newValue !== '') {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: 'divpages/list/listcomponent_JSON.php',
                        data: {
                            action: 'edit_data',
                            pk_NestingFile__: pk_NestingFile,
                            pk_compName__: pk_compName,
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



    function listCompt(handleData) {

        var job = $('#jobname option:selected').data('id');
        var jobname = $('#jobname').val();

        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listcomponent_JSON.php',
            data: {action: 'ViewListByPlat', id1: job, id2: jobname},
            success: function (json) {
                handleData(json);
            }
        });
    }

    function listTabel() {

        listCompt(function (response) {
            var table = $('#example').DataTable({
                destroy: true,
                processing: true,
                data: response,
                "columns": [
                    {
                        "className": 'details-control-list',
                        "orderable": false,
                        "defaultContent": ''
                    },
                    {"data": "COMP_NAME"},
                    {"data": "COMP_PROFILE"},
                    {"data": "COMP_MST_QTY"},
                    {"data": "COMP_WEIGHT"},
                    {"data": "CUTTING"},
                    {"data": "FINISHING"},
                ],
                "columnDefs": [
                    {
                        "visible": true,
                        "targets": [0],
                        "render": function (data, type, row, meta) {

                        }
                    },
                    {
                        "visible": true,
                        "targets": [2],
                        "render": function (data, type, row, meta) {
                            var isi = data + " x " + row.COMP_LENGTH;
                            return isi;
                        }
                    },
                ],
                "order": [[1, 'asc']],
            });
            childTable(table);
        });
    }



    function childTable(table) {
        // Add event listener for opening and closing details
        $('#example tbody').on('click', 'td.details-control-list', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    }

    function selectpicker() {
        $('#jobname').selectpicker();
    }

    function selectProject() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listcomponent_JSON.php',
            data: {action: 'selectProject'},
            success: function (json) {
//                    alert(json);
                $("select#job").empty();
                $("select#jobname").empty();

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

                $("select#job").append(isiOption);
                $("select#jobname").append(isiOption);
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