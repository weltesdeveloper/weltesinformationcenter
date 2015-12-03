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
        <h3 class="box-title"> Monitoring Component<b><span id="txt_job_input_stock"></span> By Assign</b>
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
                                        <th>HEAD MARK</th>
                                        <th>TOTAL QTY</th>
                                        <th>WEIGHT DRAW</th>
                                        <th>WEIGHT COMP</th>
                                        <th>PROGRESS ASG</th>
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
        var hm = d.HEAD_MARK;
        detilCompHM(hm)
        return '<table id="detilCompHM" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<thead>' +
                '<tr>' +
                '<th  rowspan="2"></th>' +
                '<th  rowspan="2">No.</th>' +
                '<th  rowspan="2">Part Name</th>' +
                '<th  rowspan="2">Profile Dimensi</th>' +
                '<th  rowspan="2">QTY</th>' +
                '<th  rowspan="2">Weight</th>' +
                '<th  rowspan="2">Asg</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '</tbody>' +
                '</table>';
    }

    function detilCompHM(hm) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'divpages/list/listcomponent_JSON.php',
            data: {action: 'ViewDetilCompHM', id1: hm},
            beforeSend: function () {
            },
            success: function (json) {
                var no = 0;
                var isi = '';
                $('table#detilCompHM tbody').empty();
                $.each(json, function (index, row) {
                    no++;
                    isi += '<tr>' +
                            '<th></th>' +
                            '<td>' + no + '</td>' +
                            '<td>' + row.COMP_NAME + '</td>' +
                            '<td align="right">' + row.COMP_PROFILE + ' x ' + row.COMP_LENGTH + '</td>' +
                            '<td>' + row.COMP_MST_QTY + '</td>' +
                            '<td>' + row.COMP_WEIGHT + '</td>' +
                            '<td>' + row.COMP_ASG_QTY + '</td>' +
                            '</tr>';
                });
                $('table#detilCompHM tbody').append(isi);
            },
            complete: function () {
//                dataTabel();
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
            data: {action: 'ViewList', id1: job, id2: jobname},
            success: function (json) {
                handleData(json);
            }
        });
    }

    function listTabel() {

        listCompt(function (response) {

            var no = 0;
            var hm = response[0].HEAD_MARK;
            detilCompHM(hm)
            var table = $('#example').DataTable({
                processing: true,
                data: response,
                "columns": [
                    {
                        "className": 'details-control-list',
                        "orderable": false,
                        "defaultContent": ''
                    },
                    {"data": "HEAD_MARK"},
                    {"data": "TOTAL_QTY"},
                    {"data": "WEIGHT"},
                    {"data": "COMP_WEIGHT"},
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
                        "targets": [5],
                        "render": function (data, type, row, meta) {
                            var sum_qty = row.MST;
                            var sum_asg = row.ASG;
                            var progAsg = parseFloat(sum_asg) / parseFloat(sum_qty) * 100;
                            var progres = progAsg + ' %';
                            return progres;
//                            var sum_qty = row.MST;
//                            var sum_cutt = row.CUT;
//                            var progCutt = parseFloat(sum_cutt) / parseFloat(sum_qty) * 100;
//                            var progres = progCutt + ' %';
//                            return progres;

                        }
                    }

                ],
                "order": [[1, 'asc']]
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