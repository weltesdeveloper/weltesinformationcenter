<div class="row">
    <div class="col-xs-12">

        <div class="box box-danger">
            <div class="box-header with-border">
                <i class="fa fa-cubes"></i><h3 class="box-title">Component List / Component Bank <b><span id="txt_job_input_stock"></span> ~ <span id="txt_jobname_input_stock"></span></b></h3>
            </div><!-- /.box-header -->


            <div class="tab-pane active"  class="box-body">


                <div class="row">
                    <div class="col-md-12">
                        <table id="TableListWaste" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waste Name</th>
                                    <th>Width</th>
                                    <th>Lenght</th>
                                    <th>Weight</th>
                                    <th>Grade</th>
                                    <th>Date Input Waste</th>
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
<script>
    $(document).ready(function (){
        listTabelWaste();
    });
    function listWaste(handleData) {

        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/processListWaste.php",
            data: {action: 'ViewListWaste'},
            success: function (json) {
                handleData(json);
                console.log(json)
            }
        });
    }

    function listTabelWaste() {

        listWaste(function (json) {

            var no = 0;

            var table = $('#TableListWaste').DataTable({
                destroy: true,
                processing: true,
                data: json,
                "columns": [
                    {"data": 'WASTE_NM'},
                    {"data": "WASTE_WIDTH"},
                    {"data": "WASTE_LENGTH"},
                    {"data": "WASTE_WEIGHT"},
                    {"data": "WASTE_GRADE"},
                    {"data": "WASTE_INP_DT"},
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
                        "targets": [1],
                        "render": function (data, type, row, meta) {
                            var isi = row.WASTE_NM;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [2],
                        "render": function (data, type, row, meta) {
                            var isi = row.WASTE_WIDTH;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [3],
                        "render": function (data, type, row, meta) {
                            var isi = row.WASTE_LENGTH;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [4],
                        "render": function (data, type, row, meta) {
                            var isi = row.WASTE_WEIGHT;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [5],
                        "render": function (data, type, row, meta) {
                            var isi = row.WASTE_GRADE;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [6],
                        "render": function (data, type, row, meta) {
                            var isi = row.WASTE_INP_DT;
                            return isi;
                        }
                    }
                ],
//                "order": [[1, 'asc']],
                // menambahkan ID di dalam <tr>
//                "fnCreatedRow": function (nRow, aData, iDataIndex) {
//                    $(nRow).attr('id', no);
//                }
            });
//            childTableList(table);
        });
    }
</script>