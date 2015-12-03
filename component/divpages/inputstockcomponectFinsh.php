<div class="box box-danger">
    <div class="box-header with-border">
        <i class="fa fa-trash"></i>
        <h3 class="box-title"> Finish <b><span id="txt_job_input_stock"></span> ~ Manual Stock Input</b>
            <!--<small>Collect Waste Data & Insert It Here For Waste Management</small>-->
        </h3>
    </div> 

    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nesting File No</label>
                    <select class="selectpicker" data-live-search="true" data-width="100%" id="selectNestingFile" data-live-search="true">

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div id="inv-spec-type">

                    <label>Finish</label>
                    <input type="text" id="dateInputStockFinsh" data-datepick1="datepick1Value" class="form-control" placeholder="Tanggal Finishing">
                </div>
            </div>
            <div class="col-md-4">
                <div id="inv-spec-type">
                    <label>Machine</label>
                    <input type="text" id="mesinFinsh" class="form-control" placeholder="Input Mesin"/>
                </div>
            </div>
        </div>
    </div>

</div> 


<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <i class="fa fa-cubes"></i><h3 class="box-title">Nesting File No.  <b><span id="txtNestingFile"></span></b></h3>
            </div><!-- /.box-header -->

            <div class="tab-pane active"  class="box-body">


                <div class="row">
                    <div class="col-md-12">
                        <table id="tabelCompFinsh" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Part Name</th>
                                    <th>Profile Dimensi</th>
                                    <th>Qty</th>
                                    <th>Weight</th>
                                    <th>Cutt</th>
                                    <th>Tanggal Cutt</th>
                                    <th>Finsh</th>
                                    <th>Tanggal Finsh</th>
                                    <!--<th>Nesting File</th>-->
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>                                
                        </table>                        
                        <button type="button" id="btnInputStockFisnhAll" disabled class="btn btn-block btn-primary">Input Stock ALL</button>
                        <span id="checkID"></span>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

    </div><!-- /.col -->
</div><!-- /.row -->
<script>

    $(document).ready(function () {
        ViewNestingFile();

        $('#selectNestingFile').change(function () {
            var NestingFile = $('#selectNestingFile option:selected').val();
            $('#txtNestingFile').text(NestingFile);
            $('#btnInputStockFisnhAll').prop('disabled', false);
            listTabelFinsh();
        });

        $('#btnInputStockFisnhAll').click(function () {
            if (confirm('Are You Sure to Input Stock This ALL Component`s ?')) {
                var NestingFile = $('#selectNestingFile option:selected').val();
                var dateFinsh = $('#dateInputStockFinsh').val();
                var mesinFinsh = $('#mesinFinsh').val();
                var compName = [];
                var finsh = [];

                var rows = $('#tabelCompFinsh').dataTable().fnGetNodes();
                for (var x = 0; x < rows.length; x++) {
                    var check = $(rows[x]).find("td:eq(7)").find('#checkInputStockFinsh').is(':checked');
                    if (check) {
                        compName.push($(rows[x]).find("td:eq(1)").text());
                        finsh.push($(rows[x]).find("td:eq(5)").text());
                    }
                }
                var sentReq = {
                    action: 'inputStockCompFinshAll',
                    NestingFile__: NestingFile,
                    dateFinsh__: dateFinsh,
                    mesinFinsh__: mesinFinsh,
                    compName__: compName,
                    finsh__: finsh
                };
                if (dateFinsh == "") {
                    sweetAlert("Oops...", "Tanggal belum di isi ... !!!", "error");
                } else if (mesinFinsh == "") {
                    sweetAlert("Oops...", "Mesin belum di isi ... !!!", "error");
                } else {
                    console.log(sentReq);
                    inputStockCompFinsh(sentReq);
                }
            } else {
                return false;
            }
        });

    });

    // FUNCTION CODE

    function selectpicker_input_stock() {
        $('#selectNestingFile').selectpicker();
        $('#dateInputStockFinsh').datepicker();
    }

    function ViewNestingFile() {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstockFinsh.php",
            data: {action: 'ViewNestingFile'},
            success: function (json) {

                var isiOption = '<option value="">-[Select Nesting File]-</option>';
                $.each(json, function (index, row) {
                    isiOption += '<option value="' + row.NESTING_FILE + '">' + row.NESTING_FILE + '</option>';
                });
                $("select#selectNestingFile").append(isiOption);

            },
            complete: function () {
                selectpicker_input_stock();
            }
        });
    }


    function listComptFinsh(handleData) {
        var NestingFile = $('#selectNestingFile').val();

        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstockFinsh.php",
            data: {action: 'listComptFinsh', NestingFile__: NestingFile},
            success: function (json) {
                handleData(json);
//                console.log(json);
            }
        });
    }

    function listTabelFinsh() {

        listComptFinsh(function (json) {
            var no = 0;
            var table = $('#tabelCompFinsh').DataTable({
                destroy: true,
                processing: true,
                data: json,
                "columns": [
                    {"data": null},
                    {"data": 'COMP_NAME'},
                    {"data": "COMP_PROFILE"},
                    {"data": "COMP_MST_QTY"},
                    {"data": "COMP_WEIGHT"},
                    {"data": "CUTTING"},
                    {"data": "TANGGAL_CUTTING"},
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
                        "targets": [7],
                        "render": function (data, type, row, meta) {
                            var finsh = row.FINISHING;
                            var disable = '<input type="checkbox" id="checkInputStockFinsh" class="form-control">';
                            if (finsh != '0') {
                                disable = '<i class="fa fa-check"></i>'
                            }
                            var isi = disable;
                            return isi;
                        }
                    },
                    {
                        "targets": [8],
                        "render": function (data, type, row, meta) {
                            var isiTglFinsh = row.TANGGAL_FINISHING
                            return isiTglFinsh;
                        }
                    },
                ],
                "order": [[1, 'asc']],
                // menambahkan ID di dalam <tr>
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', no);
                }
            });
        });
    }

    function inputStockCompFinsh(sentReq) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processinputstockFinsh.php",
            data: sentReq,
            beforeSend: function (xhr) {
            },
            success: function (json) {
                console.log(json);
            },
            complete: function () {
                swal("Good job!", "You clicked the button!", "success")
                listTabelFinsh();
                $('#dateInputStockFinsh').val("");
                $('#mesinFinsh').val("");
            }
        });
    }


</script>