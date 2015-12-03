<link href="../css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<script src="../jQuery/jquery-2.1.1.min.js"></script>
<script src="../AdminLTE/js/plugins/datatables/jquery.dataTables.min.js"></script>
<style>
    td.details-control {
        background: url('icon/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('icon/details_close.png') no-repeat center center;
    }
</style>

<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>HEAD MARK</th>
            <th>TOTAL QTY</th>
            <th>WEIGHT DRAW</th>
            <th>WEIGHT COMP</th>
            <th>PROGRESS CUTT</th>
            <th>PROGRESS FINSH</th>
        </tr>
    </thead>
</table>
<script>
    /* Formatting function for row details - modify as you need */
    function format(d) {
        var hm = d.HEAD_MARK;
        detilCompHM(hm)
        // `d` is the original data object for the row
        return '<table id="detilCompHM" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<thead>' +
                '<tr>' +
                '<th></th>' +
                '<th>No.</th>' +
                '<th>Comp Name</th>' +
                '<th>Head Mark</th>' +
                '<th>QTY</th>' +
                '<th>Comp Profile</th>' +
                '<th>Comp Lenght</th>' +
                '<th>Comp Weight</th>' +
                '<th>Cutting</th>' +
                '<th>Finishing</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '</tbody>' +
                '</table>';
    }

    $(document).ready(function () {
        listTabel();
    });



    function detilCompHM(hm) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "processList.php",
            data: {action: 'ViewDetilCompHM', id1: hm},
            beforeSend: function () {
//                $('#list_comp').DataTable().clear().destroy();
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
                            '<td>' + row.HEAD_MARK + '</td>' +
                            '<td>' + row.COMP_MST_QTY + '</td>' +
                            '<td>' + row.COMP_PROFILE + '</td>' +
                            '<td>' + row.COMP_LENGTH + '</td>' +
                            '<td>' + row.COMP_WEIGHT + '</td>' +
                            '<td>' + row.CUTTING + '</td>' +
                            '<td>' + row.FINISHING + '</td>' +
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
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "processList.php",
            data: {action: 'ViewList'},
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
                        "className": 'details-control',
                        "orderable": false,
                        "defaultContent": ''
                    },
                    {"data": "HEAD_MARK", },
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
                        "targets": [4],
                        "render": function (data, type, row, meta) {
                            var isi = row.WEIGHT;
                            return isi;
//                            return RenderDecimalNumber(row.WEIGHT, {
//                                "decimalPlaces": 2,
//                                "thousandSeparator": " ",
//                                "decimalSeparator": ","});
                        }
                    },
                    {
                        "visible": true,
                        "targets": [5],
                        "render": function (data, type, row, meta) {
                            var sum_qty = row.MST;
                            var sum_cutt = row.CUT;
                            var progCutt = parseFloat(sum_cutt) / parseFloat(sum_qty) * 100;
                            var progres = progCutt + '%';
                            return progres;

                        }
                    },
                    {
                        "visible": true,
                        "targets": [6],
                        "render": function (data, type, row, meta) {
                            var sum_qty = row.MST;
                            var sum_finsh = row.FIN;
                            var progFinsh = parseFloat(sum_finsh) / parseFloat(sum_qty) * 100;
                            var progres = progFinsh + '%';
                            return progres;

                        }
                    }

                ],
//                "aoColumns": [{
//                        "sType": "numeric",
//                        "render": function (oObj) {
//                            return RenderDecimalNumber(oObj, {
//                                "decimalPlaces": 2,
//                                "thousandSeparator": " ",
//                                "decimalSeparator": ","});
//                        }
//                    }],
                "order": [[1, 'asc']]
            });
            childTable(table);
        });
    }

//    function RenderDecimalNumber(oObj) {
//        var num = new NumberFormat();
//        num.setInputDecimal('.');
//        num.setNumber(oObj.aData[oObj.iDataColumn]);
//        num.setPlaces(this.oCustomInfo.decimalPlaces, true);
//        num.setCurrency(false);
//        num.setNegativeFormat(num.LEFT_DASH);
//        num.setSeparators(true, this.oCustomInfo.decimalSeparator, this.oCustomInfo.thousandSeparator);
//
//        return num.toFormatted();
//    }

    function childTable(table) {
        // Add event listener for opening and closing details
        $('#example tbody').on('click', 'td.details-control', function () {
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
</script>