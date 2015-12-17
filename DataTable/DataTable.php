<link href="../css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
<script src="../jQuery/jquery-2.1.1.min.js"></script>
<script src="../AdminLTE/js/plugins/datatables/jquery.dataTables.min.js"></script>
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>MTO_NO</th>
            <th>INV_ID</th>
            <th>PO_REV</th>
            <th>DTL_MTO_RMK</th>
        </tr>
    </thead>
</table>

<button id="Proses"> PROSES </button>

<script>

    $(document).ready(function () {
        listTabel();
    });
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
            var table = $('#example').DataTable({
                processing: true,
                data: response,
                "columns": [
                    {"data": "CAT1"},
                    {"data": "CAT2"},
                    {"data": "CAT3"},
                    {"data": "REM"},
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
                            var isi = row.CAT1;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [2],
                        "render": function (data, type, row, meta) {
                            var isi = row.CAT2;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [3],
                        "render": function (data, type, row, meta) {
                            var isi = row.CAT3;
                            return isi;
                        }
                    },
                    {
                        "visible": true,
                        "targets": [4],
                        "render": function (data, type, row, meta) {
                            var isi = row.REM;
                            return isi;
                        }
                    },
                ],
                "order": [[1, 'asc']]
            });
        });
    }


    $('#Proses').click(function () {
        if (confirm('Are You Sure to Input Stock This ALL Component`s ?')) {
            var CAT1 = [];
            var CAT2 = [];
            var CAT3 = [];
            var REM = [];

            var rows = $('#example').dataTable().fnGetNodes();
            for (var x = 0; x < rows.length; x++) {
                CAT1.push($(rows[x]).find("td:eq(1)").text());
                CAT2.push($(rows[x]).find("td:eq(2)").text());
                CAT3.push($(rows[x]).find("td:eq(3)").text());
                REM.push($(rows[x]).find("td:eq(4)").text());
            }

            var sentReq = {
                action: 'UpdateMiko',
                CAT1__: CAT1,
                CAT2__: CAT2,
                CAT3__: CAT3,
                REM__: REM
            };
            UpdateMiko(sentReq);
        } else {
            return false;
        }
    });
    
    function UpdateMiko(sentReq) {
        return $.ajax({
            type: "POST",
            dataType: 'json',
            url: "processList.php",
            data: sentReq,
            success: function (json){ 
                alert(json);
//                console.log(json);
            }
        });
    }

</script>