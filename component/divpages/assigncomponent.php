<?php
$headmark = $_POST['headmark'];
$job = $_POST['job'];
$subjob = $_POST['subjob'];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Job - SubJob</a></li>
                <li><a href="#tab_2-2" data-toggle="tab">All</a></li>
                <li class="pull-left header"><i class="fa fa-cubes"></i>Component Assignment for <?php echo $job; ?> : <?php echo $subjob; ?> ~ <b><i><small id="AssHeadMark"><?php echo $headmark; ?></small></i></b></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">


                    <table id="tabelCompAss" class="display" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Comp Name</th>
                                <th>QTY</th>
                                <th>Comp Lenght</th>
                                <th>Comp Weight</th>
                                <th>Comp Profile</th>
                                <th class='hidden'>Comp Stock</th>
                                <th>Comp Stock</th>
                                <th>Comp Ass Qty</th>
                                <th>Comp Register</th>
                                <th>Action</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2">
                    The European languages are members of the same family. Their separate existence is a myth.
                    For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                    in their grammar, their pronunciation and their most common words. Everyone realizes why a
                    new common language would be desirable: one could refuse to pay expensive translators. To
                    achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                    words. If several languages coalesce, the grammar of the resulting language is more simple
                    and regular than that of the individual languages.
                </div><!-- /.tab-pane -->
            </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->
    </div><!-- /.col -->
</div><!-- /.row -->

<script>


    $(document).ready(function () {
        listTabelAss();
    });

    function listComptAss(handleData) {
        var AssHeadMark = $('#AssHeadMark').text();

        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/prosesAssign.php",
            data: {action: 'ViewCompAsign', id1: AssHeadMark},
            success: function (json) {
                handleData(json);
            }
        });
    }

    function listTabelAss() {

        listComptAss(function (response) {
            console.log(response);
            var no = 0;

            var table = $('#tabelCompAss').DataTable({
                destroy: true,
                processing: true,
                data: response,
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "defaultContent": ''
                    },
                    {"data": 'COMP_NAME'},
                    {"data": "COMP_MST_QTY"},
                    {"data": "COMP_LENGTH"},
                    {"data": "COMP_WEIGHT"},
                    {"data": "COMP_PROFILE"},
                    {
                        "data": "COMP_STOCK_QTY",
                        "class": 'hidden'
                    },
                    {"data": "COMP_STOCK_QTY"},
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
                        "targets": [8],
                        "render": function (data, type, row, meta) {
                            var qty = row.COMP_MST_QTY;
                            var stock_assign = row.COMP_ASG_QTY;
                            var inputDis = '';
                            if (qty == stock_assign) {
                                inputDis = 'disabled';
                            }
                            var isi = '<input type="number" style="width:80px;" onchange="nilaiLokal(' + row.COMP_ID + ')" min="0" max="' + row.COMP_MST_QTY + '" id="comp_asg_qty_' + row.COMP_ID + '" ' + inputDis + ' value ="0"/>';
                            return isi;
                        }
                    },
                        {
                        "targets": [9],
                        "render": function (data, type, row, meta) {
                            var isi = row.COMP_ASG_QTY;
                            return isi;
                        }
                    },
                    {
                        "targets": [10],
                        "render": function (data, type, row, meta) {
                            var qty = row.COMP_MST_QTY;
                            var stock_assign = row.COMP_ASG_QTY;
                            var inputDis = '';
                            var classBtn = 'success'
                            if (qty == stock_assign) {
                                inputDis = 'disabled';
                                classBtn = 'primary';
                            }
                            var isi = '<a title="Assign Comp" class="btn btn-xs btn-' + classBtn + '" onclick="ProsesInputStockComp(' + row.COMP_ID + ')" ' + inputDis + '><i class="fa fa-edit"></i></a>';
                            return isi;
                        }
                    },
                    {
                        "targets": [11],
                        "render": function (data, type, row, meta) {
                            var qty = row.COMP_MST_QTY;
                            var stock_assign = row.COMP_ASG_QTY;
                            var sts = '<small class="label label-danger">NOT READY</small>';
                            if (qty == stock_assign) {
                                sts = '<small class="label label-success">READY</small>';
                            }
                            var isi = sts;
                            return isi;
                        }
                    }
                ],
                "order": [[1, 'asc']],
                // menambahkan ID di dalam <tr>
                "fnCreatedRow": function (nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData.COMP_ID);
                }
            });
        });
    }

    function nilaiLokal(id) {
        var idTr = $('table#tabelCompAss').find('tr#' + id);
        var tdNilai = idTr.find('input#comp_asg_qty_' + id).val();
        var tdStock = idTr.find("td:eq(6)").text();
        var kurangi = parseInt(tdStock) - parseInt(tdNilai);
        idTr.find("td:eq(7)").text(kurangi);
    }

    function ProsesInputStockComp(id) {
        var idTr = $('table#tabelCompAss').find('tr#' + id);
        var tdStock = idTr.find('input#comp_asg_qty_' + id).val();
        var tdStockComp = idTr.find("td:eq(6)").text();
        var tdStockComp2 = idTr.find("td:eq(7)").text();
        var CompName = idTr.find("td:eq(1)").text();

//        alert(tdStock);

        var sentReq = {
            action: 'inputAssignStockComp',
            id1: id,
            id2: tdStock,
            id3: tdStockComp2,
            id4: CompName,
        };
        if (parseInt(tdStockComp) <= 0 ) {
            alert('Component <= 0 ...!!!');
        } else if (tdStock == 0) {
            alert('Comp Ass Qty Kosong ...!!!');
        } else {
            if (confirm('Are You Sure to Input Stock This Item`s ?')) {
                inputAssignStockComp(sentReq)
            } else {
                return false;
            }
        }
    }

    function inputAssignStockComp(sentReq) {
        var AssHeadMark = $('#AssHeadMark').text();
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "divpages/process/prosesAssign.php",
            data: sentReq,
            beforeSend: function (xhr) {
            },
            success: function (json) {
                console.log(json);
            },
            complete: function () {
                listTabelAss();
            }
        });
    }


//
//    function ViewCompAsign(AssHeadMark) {
//        $.ajax({
//            type: "POST",
//            dataType: 'json',
//            url: "divpages/process/prosesAssign.php",
//            data: {action: 'ViewCompAsign', id1: AssHeadMark},
//            success: function (json) {
//                var isi = "";
//                var no = 0;
//                $('table#comp_assign tbody').empty();
//                $.each(json, function (index, row) {
//                    var kurang_stock = qty - stock_assign;
//                    var sts = '<small class="label label-danger">NOT READY</small>';
//                    var inputDis = '';
//                    var classBtn = 'success'
//                    var qty = row.COMP_MST_QTY;
//                    var stock_assign = row.COMP_ASG_QTY;
//                    if (qty == stock_assign) {
//                        sts = '<small class="label label-success">READY</small>';
//                        inputDis = 'disabled';
//                        classBtn = 'primary';
//                    }
//                    no++;
//                    isi += '<tr id="tr-' + row.COMP_ID + '">' +
//                            '<td>' + no + '</td>' +
//                            '<td>' + row.COMP_NAME + '</td>' +
//                            '<td>' + row.COMP_MST_QTY + '</td>' +
//                            '<td>' + row.COMP_ASG_QTY + '</td>' +
//                            '<td>' + row.COMP_PROFILE + '</td>' +
//                            '<td>' + row.COMP_LENGTH + '</td>' +
//                            '<td>' + row.COMP_WEIGHT + '</td>' +
//                            '<td><input type="number" style="width:80px;" onchange="nilaiLokal(' + row.COMP_ID + ')" min="0" max="' + kurang_stock + '" id="comp_asg_qty_' + row.COMP_ID + '" value="' + row.COMP_ASG_QTY + '" ' + inputDis + '/></td>' +
//                            '<td class="hidden">' + row.COMP_STK_QTY + '</td>' +
//                            '<td>' + row.COMP_STK_QTY + '</td>' +
//                            '<td><a title="Assign Comp" class="btn btn-xs btn-' + classBtn + '" onclick="ProsesInputStockComp(' + row.COMP_ID + ')" ' + inputDis + '><i class="fa fa-edit"></i></a></td>' +
//                            '<td>' + sts + '</td>' +
//                            '<td class="hidden">' + kurang_stock + '</td>' +
//                            '<tr>';
//                });
//                $('table#comp_assign tbody').append(isi);
//            },
//            complete: function () {
//            }
//        });
//    }








</script>
