<style>
    td.details-control-list {
        background: url('icon/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control-list {
        background: url('icon/details_close.png') no-repeat center center;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <select class="selectpicker" data-live-search="true" data-width="100%" id="jobname_input_stock" data-live-search="true">

        </select>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <i class="fa fa-cubes"></i><h3 class="box-title">Component List / Component Bank <b><span id="txt_job_input_stock"></span> ~ <span id="txt_jobname_input_stock"></span></b></h3>
            </div><!-- /.box-header -->


            <div class="tab-pane active"  class="box-body">


                <div class="row">
                    <div class="col-md-12">
                        <table id="list_input_stock" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="90"></th>
                                    <th>Part Name</th>
                                    <th>Profile Dimensi</th>
                                    <th>Qty</th>
                                    <th>Weight</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>                                
                        </table>                        
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
            listTabel();
        });

    });

    function selectpicker_input_stock() {
        $('#jobname_input_stock').selectpicker();
    }

    function selectProject_input_stock() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processList.php",
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
            url: "divpages/process/processList.php",
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
            url: "divpages/process/processList.php",
            data: {action: 'ViewList_input', id1: job, id2: jobname},
            success: function (json) {
                handleData(json);
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
                        "className": 'details-control-list',
                        "orderable": false,
                        "defaultContent": ''
                    },
                    {"data": 'COMP_NAME'},
                    {"data": "COMP_PROFILE"},
                    {"data": "COMP_MST_QTY"},
                    {"data": "COMP_WEIGHT"},
                    {"data": "COMP_STOCK_QTY"},
                ],
                "columnDefs": [
                    {
                        "visible": true,
                        "targets": [0],
                        "render": function (data, type, row, meta) {
//                            no++;
//                            return no;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [2],
                        "render": function (data, type, row, meta) {
                            var isi = row.COMP_PROFILE + " x " + row.COMP_LENGTH;
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
            childTableList(table);
        });
    }
    
    function childTableList(table){
        // Add event listener for opening and closing details
        $('#list_input_stock tbody').on('click', 'td.details-control-list', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(formatList(row.data())).show();
                tr.addClass('shown');
            }
        });
    }
    
    function formatList(d){
        var cn = d.COMP_NAME;
        detilCompAss(cn);
//        alert(cn);
        return '<table id="detilCompAss" cellpadding="5" cellspacing="0" border="0" style="padding-left:500px;">' +
                '<thead>' +
                '<tr>' +
                '<th>No.</th>' +
                '<th>Assembly</th>' +
                '<th>Profile Dimensi</th>' +
                '<th>Qty Assign</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '</tbody>' +
                '</table>';
    }
    
    function detilCompAss(cn){
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processList.php",
            data: {action: 'detilCompAss', id1: cn},
            beforeSend: function () {
            },
            success: function (json) {
//                alert(json);
                var no = 0;
                var isi = '';
                $('table#detilCompAss tbody').empty();
                $.each(json, function (index, row) {
                    no++;
                    isi += '<tr>' +
                            '<td>' + no + '</td>' +
                            '<td>' + row.HEAD_MARK + '</td>' +
                            '<td align="right">' + row.COMP_PROFILE + ' x ' + row.COMP_LENGTH + '</td>' +
                            '<td>' + row.COMP_ASG_QTY + '</td>' +
                            '</tr>';
                });
                $('table#detilCompAss tbody').append(isi);
            },
            complete: function () {
//                dataTabel();
            }
        });
    }


</script>