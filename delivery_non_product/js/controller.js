$(function () {
    $('#projNo').selectpicker();
    $('#deliveryDate').datetimepicker({
        pickTime: false
    });

});
var table = $('#table-input').dataTable({
    "paging": false,
    "searching": false
});

var table2 = $('#table-detail').dataTable({
    "paging": false,
    "searching": false
});
var counter = 0;
//ADD ITEM MASTER
function AddItem() {
    var newTargetRow = table.fnAddData([
        "<input type='text' id='parent-name" + counter + "' class='form-control' onkeyup=UpperCase('" + counter + "','" + "parent-name" + "') maxlength='50'>",
        "<input type='number' value='' id='parent-qty" + counter + "' class='form-control' placeholder='0'>",
        "<input type='text' value='' id='parent-unit" + counter + "' class='form-control' placeholder='Pcs, Botol' onkeyup=UpperCase('" + counter + "','" + "parent-unit" + "')>",
        "<textarea value='' id='remark-item" + counter + "' class='form-control' placeholder='Remark Item'></textarea>",
        "<span class='glyphicon glyphicon-plus' style='color:green; cursor:pointer;' onclick=AddRowDtl('" + counter + "')></span>"
                + "&nbsp;&nbsp;&nbsp;~~&nbsp;&nbsp;&nbsp;" +
                "<span class='glyphicon glyphicon-trash' style='color:red; cursor:pointer;' onclick=DeleteRow('" + counter + "')></span>"
    ]);
    var oSettings = table.fnSettings();
    var nTr = oSettings.aoData[ newTargetRow[0] ].nTr;
    //menambahkan id
    var row = 'rowtarget' + counter;
    nTr.setAttribute('id', row);
    //menambahkan class
    $('td', nTr)[0].setAttribute('class', 'text-center');
    $('td', nTr)[1].setAttribute('class', 'text-center');
    $('td', nTr)[2].setAttribute('class', 'text-center');
    $('td', nTr)[3].setAttribute('class', 'text-center');
    $('td', nTr)[4].setAttribute('class', 'text-center');
    counter++;
    $('#button-submit').prop("disabled", false);
}

//DELETE ITEM MASTER
function DeleteRow(param) {
    var table_targetrem = $('#table-input').DataTable();
    table_targetrem.row('#rowtarget' + param).remove().draw(false);
    if ($('#table-input').dataTable().fnSettings().aoData.length === 0) {
        $('#button-submit').prop("disabled", true);
    }
}

function AddRowDtl(param) {
    var content = $('#remark-item' + param).val();
    $('#myModal').modal('show');
    $('#content').val("");
    $('#content').val(content);
    $('#baris').val(param);
}

function SubmitData() {
    var content = $('#content').val();
    var baris = $('#baris').val();
    $('#remark-item' + baris).val(content);
    $('#myModal').modal('hide');

}

function ChangeJob() {
    var typeDo = $('#type-do').val();
    var job = $('#job').val();
    $.ajax({
        type: 'POST',
        url: "divpages/process.php",
        data: {typeDo: typeDo, job: job, action: "ubah_do"},
        success: function (response, textStatus, jqXHR) {
            $('#do-no').val(response);
        }
    });
}

function SubmitTODB() {
    var rows = $('#table-input').dataTable().fnGetNodes();
    var item_name = [];
    var item_qty = [];
    var item_unit = [];
    var item_detail = [];
    for (var x = 0; x < rows.length; x++) {
        item_name.push($(rows[x]).find("td:eq(0)").find('input').val());
        item_qty.push($(rows[x]).find("td:eq(1)").find('input').val());
        item_unit.push($(rows[x]).find("td:eq(2)").find('input').val());
        item_detail.push($(rows[x]).find("td:eq(3)").find('textarea').val());
    }

    var sentReq = {
        item_name: item_name,
        item_qty: item_qty,
        item_unit: item_unit,
        item_detail: item_detail
    };
    console.log(sentReq);
    /*---INPUT SELAIN DI TABLE*/
    var tanggal = $('#tanggal').val();
    var job = $('#job').val();
    var do_no = $('#do-no').val();
    var address = $('#alamat').val();
    var city = $('#kota').val();
    var spk = $('#spk-no').val();
    var subject = $('#subject').val();
    var pono = $('#po-no').val();
    var vehicleno = $('#vehicle-no').val();
    var transporter = $('#transporter').val();
    var driver = $('#driver').val();
    var attention = $('#attention').val();
    var spv = $('#spv').val();
    var remark_do = $('#remark-do').val();
    
    if (tanggal == "") {
        swal("TOLONG ISI TANGGAL", "", "error");
        $('#tanggal').focus();
    } else if (job == "") {
        swal("TOLONG IS JOB", "", "error");
        $('#job').focus();
    } else if (do_no == "") {
        swal("TOLONG IS DO NO", "", "error");
        $('#do-no').focus();
    } else if (address == "") {
        swal("TOLONG IS ADDRESS", "", "error");
        $('#alamat').focus();
    } else if (city == "") {
        swal("TOLONG IS CITY", "", "error");
        $('#kota').focus();
    } else if (spk == "") {
        swal("TOLONG IS SPK NO", "", "error");
        $('#spk-no').focus();
    } else if (subject == "") {
        swal("TOLONG IS SUBJECT", "", "error");
        $('#subject').focus();
    } else if (pono == "") {
        swal("TOLONG IS PO NO", "", "error");
        $('#po-no').focus();
    } else if (vehicleno == "") {
        swal("TOLONG IS VEHICLE NO", "", "error");
        $('#vehicle-no').focus();
    } else if (transporter == "") {
        swal("TOLONG IS TRANSPORTER", "", "error");
        $('#transporter').focus();
    } else if (driver == "") {
        swal("TOLONG IS DRIVER", "", "error");
        $('#driver').focus();
    } else if (attention == "") {
        swal("TOLONG IS ATTENTION", "", "error");
        $('#attention').focus();
    } else if (spv == "") {
        swal("TOLONG IS SPV", "", "error");
        $('#spv').focus();
    } else if (item_name.length == 0) {
        swal("TOLONG MASUKKAN 1 ITEM MINIMAL", "", "error");
    } else {
        var cf = confirm("DO YOU WANT SUBMIT THIS DO ? ");
        if (cf == true) {
            $.ajax({
                type: 'POST',
                url: "divpages/process.php",
                data: sentReq,
                success: function (response, textStatus, jqXHR) {
                    if (response.indexOf("GAGAL") > 0) {
                        alert("GAGAL INSERT");
                    } else {
                        alert("SUKSES INSERT");
                        window.location.reload();
                    }
                }
            });
        } else {
            return false;
        }
    }
}
