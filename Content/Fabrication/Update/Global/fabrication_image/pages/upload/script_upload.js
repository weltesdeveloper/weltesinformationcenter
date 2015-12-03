$(document).ready(function () {
    var modul = $('section#upload');
    getUrlVars();
    penguranganQty();
//    DisableBtnCapture();

//    modul.find('#tabMark').click(function () {
//        alert('TES');
//    });

    modul.find('div#tabMark').bind('expand', function () {
        alert('Expanded');
    }).bind('collapse', function () {
        alert('Collapsed');
    });

    modul.find('#error').hide();
    modul.find('#list').hide();

    modul.find('select#subcont').change(function () {
        var head_mark = modul.find('#head_mark').val();
        var sub_cont = modul.find('select#subcont').val();
        if (sub_cont == '0') {
            modul.find('#error').show();
            modul.find('#list').hide();
        } else {
            view_list(head_mark, sub_cont);
            total_img_mark(head_mark, sub_cont)
            total_img_cutt(head_mark, sub_cont)
            total_img_assy(head_mark, sub_cont)
            total_img_weld(head_mark, sub_cont)
            total_img_drill(head_mark, sub_cont)
            total_img_finsh(head_mark, sub_cont)
            modul.find('#error').hide();
            modul.find('#list').show();


        }
    });


    modul.find('#btnCancel').click(function () {
        var project_typ = modul.find('#param').val();
        window.location = "../fabrication_image/fabrication_image.html?id=" + project_typ;
    });

    modul.find('#btnMarking').click(function () {
        var user = modul.find('#user').val();
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../MARKING/marking.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + "&id4=0" + "&id5=" + user;
    });

    modul.find('#btnCutting').click(function () {
        var user = modul.find('#user').val();
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../CUTTING/cutting.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + "&id4=0" + "&id5=" + user;
    });

    modul.find('#btnAssembly').click(function () {
        var user = modul.find('#user').val();
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../ASSEMBLY/assembly.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + "&id4=0" + "&id5=" + user;
    });

    modul.find('#btnWelding').click(function () {
        var user = modul.find('#user').val();
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../WELDING/welding.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + "&id4=0" + "&id5=" + user;
    });

    modul.find('#btnDrilling').click(function () {
        var user = modul.find('#user').val();
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../DRILLING/drilling.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + "&id4=0" + "&id5=" + user;
    });

    modul.find('#btnFinishing').click(function () {
        var user = modul.find('#user').val();
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../FINISHING/finishing.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + "&id4=0" + "&id5=" + user;
    });

});



//##############################################################################

$(document).ajaxSend(function () {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function () {
    $.mobile.loading('hide');
});



function view_sub_cont(head_mark) {
    var modul = $('section#upload');
    var dataString = 'action=view_sub_cont&id1=' + head_mark;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
//            alert(json);
            modul.find("select#subcont").empty();
            var isiOption = "<option value='0'>- Pilih Subcont -</option>";
            $.each(json, function (index, row) {
                isiOption += "<option  value='" + row.SUBCONT_ID + "'>" + row.SUBCONT_ID + "</option>";
            });
            modul.find("select#subcont").append(isiOption);
            modul.find('select#subcont').selectmenu('refresh', true);
        },
        error: function () {
            alert('Error');
        }
    });

}

// ########################################################################################## MARKING
function total_img_mark(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_mark&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
            modul.find("#list_capture_mark").empty();
            var isi = "";
            var n = 0;
            $.each(json, function (index, row) {
                n++;
                isi += '<span id="detilImgMark"> *) Barang ' + row.QTY + ', Foto ' + row.ID_IMG + '</span>' +
//                        '<input type="text" id="detilImgMark__" value="' + row.QTY + '"/>' +
                        '<input type="hidden" id="image_id_mark" value="' + row.IMAGE_ID + '"/>' +
                        '<a href="#" onclick="tambahImgMark(' + row.IMAGE_ID + ')" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-alt-icon" title="Tambah foto">Black icon</a><br>';
            });
            modul.find("#list_capture_mark").append(isi);
            modul.find("#detilImgMark__").val(json[0].QTY);

        },
        error: function () {
            alert('error');
        }
    });
}

function tambahImgMark(IMAGE_ID) {
    var modul = $('section#upload');
    var user = modul.find('#user').val();
    var project_typ = modul.find('#param').val();
    var head_mark = modul.find('#head_mark').val();
    var subcont = modul.find('#subcont').val();
    window.location = "../MARKING/marking.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + '&id4=' + IMAGE_ID + '&id5=' + user;
}


// ########################################################################################## CUTTING
function total_img_cutt(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_cutt&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
            modul.find("#list_capture_cutt").empty();
            var isi = "";
            var n = 0;
            $.each(json, function (index, row) {
                n++;
                isi += '<span id="detilImgCutt">' + n + '. Foto ' + row.ID_IMG + ', Total Barang ' + row.QTY + '</span>' +
                        '<input type="hidden" id="detilImgCutt__" value="' + row.QTY + '"/>' +
                        '<input type="hidden" id="image_id_cutt" value="' + row.IMAGE_ID + '"/>' +
                        '<a href="#" onclick="tambahImgCutt(' + row.IMAGE_ID + ')" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-alt-icon" title="Tambah foto">Black icon</a><br>';
            });
            modul.find("#list_capture_cutt").append(isi);
        },
        error: function () {
            alert('error');
        }
    });

}

function tambahImgCutt(IMAGE_ID) {
    var modul = $('section#upload');
    var user = modul.find('#user').val();
    var project_typ = modul.find('#param').val();
    var head_mark = modul.find('#head_mark').val();
    var subcont = modul.find('#subcont').val();
    window.location = "../CUTTING/cutting.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + '&id4=' + IMAGE_ID + '&id5=' + user;
}


// ########################################################################################## ASSEMBLY
function total_img_assy(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_assy&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
            modul.find("#list_capture_assy").empty();
            var isi = "";
            var n = 0;
            $.each(json, function (index, row) {
                n++;
                isi += '<span id="detilImgAssy">' + n + '. Foto ' + row.ID_IMG + ', Total Barang ' + row.QTY + '</span>' +
                        '<input type="hidden" id="detilImgAssy__" value="' + row.QTY + '"/>' +
                        '<input type="hidden" id="image_id_assy" value="' + row.IMAGE_ID + '"/>' +
                        '<a href="#" onclick="tambahImgAssy(' + row.IMAGE_ID + ')" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-alt-icon" title="Tambah foto">Black icon</a><br>';
            });
            modul.find("#list_capture_assy").append(isi);
        },
        error: function () {
            alert('error');
        }
    });

}


function tambahImgAssy(IMAGE_ID) {
    var modul = $('section#upload');
    var user = modul.find('#user').val();
    var project_typ = modul.find('#param').val();
    var head_mark = modul.find('#head_mark').val();
    var subcont = modul.find('#subcont').val();
    window.location = "../ASSEMBLY/assembly.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + '&id4=' + IMAGE_ID + '&id5=' + user;
}


// ########################################################################################## WELDING
function total_img_weld(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_weld&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
        //        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
            modul.find("#list_capture_weld").empty();
            var isi = "";
            var n = 0;
            $.each(json, function (index, row) {
                n++;
                isi += '<span id="detilImgWeld">' + n + '. Foto ' + row.ID_IMG + ', Total Barang ' + row.QTY + '</span>' +
                        '<input type="hidden" id="detilImgWeld__" value="' + row.QTY + '"/>' +
                        '<input type="hidden" id="image_id_weld" value="' + row.IMAGE_ID + '"/>' +
                        '<a href="#" onclick="tambahImgWeld(' + row.IMAGE_ID + ')" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-alt-icon" title="Tambah foto">Black icon</a><br>';
            });
            modul.find("#list_capture_weld").append(isi);
        },
        error: function () {
            alert('error');
        }
    });

}

function tambahImgWeld(IMAGE_ID) {
    var modul = $('section#upload');
    var user = modul.find('#user').val();
    var project_typ = modul.find('#param').val();
    var head_mark = modul.find('#head_mark').val();
    var subcont = modul.find('#subcont').val();
    window.location = "../WELDING/welding.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + '&id4=' + IMAGE_ID + '&id5=' + user;
}



// ########################################################################################## DRILLING
function total_img_drill(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_drill&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',         
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
            modul.find("#list_capture_drill").empty();
            var isi = "";
            var n = 0;
            $.each(json, function (index, row) {
                n++;
                isi += '<span id="detilImgDrill">' + n + '. Foto ' + row.ID_IMG + ', Total Barang ' + row.QTY + '</span>' +
                        '<input type="hidden" id="detilImgDrill__" value="' + row.QTY + '"/>' +
                        '<input type="hidden" id="image_id_drill" value="' + row.IMAGE_ID + '"/>' +
                        '<a href="#" onclick="tambahImgDrill(' + row.IMAGE_ID + ')" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-alt-icon" title="Tambah foto">Black icon</a><br>';
            });
            modul.find("#list_capture_drill").append(isi);
        },
        error: function () {
            alert('error');
        }
    });

}


function tambahImgDrill(IMAGE_ID) {
    var modul = $('section#upload');
    var user = modul.find('#user').val();
    var project_typ = modul.find('#param').val();
    var head_mark = modul.find('#head_mark').val();
    var subcont = modul.find('#subcont').val();
    window.location = "../DRILLING/drilling.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + '&id4=' + IMAGE_ID + '&id5=' + user;
}


// ########################################################################################## FINISHING
function total_img_finsh(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_finsh&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',         
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
            modul.find("#list_capture_finsh").empty();
            var isi = "";
            var n = 0;
            $.each(json, function (index, row) {
                n++;
                isi += '<span id="detilImgFinsh">' + n + '. Foto ' + row.ID_IMG + ', Total Barang ' + row.QTY + '</span>' +
                        '<input type="hidden" id="detilImgFinsh__" value="' + row.QTY + '"/>' +
                        '<input type="hidden" id="image_id_finsh" value="' + row.IMAGE_ID + '"/>' +
                        '<a href="#" onclick="tambahImgFinsh(' + row.IMAGE_ID + ')" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-alt-icon" title="Tambah foto">Black icon</a><br>';
            });
            modul.find("#list_capture_finsh").append(isi);
        },
        error: function () {
            alert('error');
        }
    });

}


function tambahImgFinsh(IMAGE_ID) {
    var modul = $('section#upload');
    var user = modul.find('#user').val();
    var project_typ = modul.find('#param').val();
    var head_mark = modul.find('#head_mark').val();
    var subcont = modul.find('#subcont').val();
    window.location = "../FINISHING/finishing.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont + '&id4=' + IMAGE_ID + '&id5=' + user;
}

//*** END


function view_list(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=view_list&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function (json) {
//            alert(json);
            modul.find("#tot_gambar_mark").text(json[0].ASG_QTY);
            modul.find("#tot_gambar_cutt").text(json[0].ASG_QTY);
            modul.find("#tot_gambar_assy").text(json[0].ASG_QTY);
            modul.find("#tot_gambar_weld").text(json[0].ASG_QTY);
            modul.find("#tot_gambar_drill").text(json[0].ASG_QTY);
            modul.find("#tot_gambar_finsh").text(json[0].ASG_QTY);

            modul.find("#tot_gambar_mark__").val(json[0].ASG_QTY);
            modul.find("#tot_gambar_cutt__").val(json[0].ASG_QTY);
            modul.find("#tot_gambar_assy__").val(json[0].ASG_QTY);
            modul.find("#tot_gambar_weld__").val(json[0].ASG_QTY);
            modul.find("#tot_gambar_drill__").val(json[0].ASG_QTY);
            modul.find("#tot_gambar_finsh__").val(json[0].ASG_QTY);


        },
        error: function () {
            alert('Error');
        }
    });

}

function getUrlVars() {
    var modul = $('section#upload');
    var url = window.location.toString();

//ambil bagian parameternya
    url.match(/\?(.+)$/);
    var params = RegExp.$1;

// pisahkan parameter URL ke associative array
    var params = params.split("&");
    var queryStringList = {};
    for (var i = 0; i < params.length; i++)
    {
        var tmp = params[i].split("=");
        queryStringList[tmp[0]] = unescape(tmp[1]);
    }

// tampilkan isi associative array
    for (var i in queryStringList)
    {
        var project_typ = queryStringList[i = 'id1'].replace(/[+]/g, " ");
        var head_mark = queryStringList[i = 'id2'].replace(/[+]/g, " ");
        var user = queryStringList[i = 'id3'].replace(/[+]/g, " ");

        modul.find('#param').val(project_typ);
        modul.find('#head_mark').val(head_mark);
        modul.find('#user').val(user);
    }

    var head_mark = modul.find('#head_mark').val();
    view_sub_cont(head_mark);


}


function penguranganQty() {
    var modul = $('section#upload');

    // PENGURANGAN MARKING
    var nil1_mark = modul.find("#tot_gambar_mark__").val();
    var nil2_mark = modul.find("#detilImgMark__").val();
//    if (nil1_mark == nil2_mark || nil2_mark != "") {
//        modul.find("#btnMarking").hide();
//    }
//    var pengurangan_mark = parseInt(nil1_mark) - parseInt(nil2_mark);
//    modul.find("#kurang_gambar_mark__").val(pengurangan_mark);

//    if (nil2_mark == "") {
//        modul.find("#kurang_gambar_mark__").val(nil1_mark);
//    } else {
//    }

    /*
     // PENGURANGAN CUTTING
     var nil1_cutt = modul.find("#tot_gambar_cutt__").val();
     var nil2_cutt = modul.find("#detilImgCutt__").val();
     var pengurangan_cutt = parseInt(nil1_cutt) - parseInt(nil2_cutt);
     if (nil2_cutt == "") {
     modul.find("#kurang_gambar_cutt").text(nil1_cutt);
     } else {
     modul.find("#kurang_gambar_cutt").text(pengurangan_cutt);
     modul.find("#kurang_gambar_cutt__").val(pengurangan_cutt);
     }
     
     // PENGURANGAN ASSEMBLY
     var nil1_assy = modul.find("#tot_gambar_assy__").val();
     var nil2_assy = modul.find("#detilImgAssy__").val();
     var pengurangan_assy = parseInt(nil1_assy) - parseInt(nil2_assy);
     if (nil2_assy == "") {
     modul.find("#kurang_gambar_assy").text(nil1_assy);
     } else {
     modul.find("#kurang_gambar_assy").text(pengurangan_assy);
     modul.find("#kurang_gambar_assy__").val(pengurangan_assy);
     }
     
     // PENGURANGAN WELDING
     var nil1_weld = modul.find("#tot_gambar_weld__").val();
     var nil2_weld = modul.find("#detilImgWeld__").val();
     var pengurangan_weld = parseInt(nil1_weld) - parseInt(nil2_weld);
     if (nil2_weld == "") {
     modul.find("#kurang_gambar_weld").text(nil1_weld);
     } else {
     modul.find("#kurang_gambar_weld").text(pengurangan_weld);
     modul.find("#kurang_gambar_weld__").val(pengurangan_weld);
     }
     
     // PENGURANGAN DRILLING
     var nil1_drill = modul.find("#tot_gambar_drill__").val();
     var nil2_drill = modul.find("#detilImgDrill__").val();
     var pengurangan_drill = parseInt(nil1_drill) - parseInt(nil2_drill);
     if (nil2_drill == "") {
     modul.find("#kurang_gambar_drill").text(nil1_drill);
     } else {
     modul.find("#kurang_gambar_drill").text(pengurangan_drill);
     modul.find("#kurang_gambar_drill__").val(pengurangan_drill);
     }
     
     // PENGURANGAN FINISHING
     var nil1_finsh = modul.find("#tot_gambar_finsh__").val();
     var nil2_finsh = modul.find("#detilImgFinsh__").val();
     var pengurangan_finsh = parseInt(nil1_finsh) - parseInt(nil2_finsh);
     if (nil2_finsh == "") {
     modul.find("#kurang_gambar_finsh").text(nil1_finsh);
     } else {
     modul.find("#kurang_gambar_finsh").text(pengurangan_finsh);
     modul.find("#kurang_gambar_finsh__").val(pengurangan_finsh);
     }
     */
}
