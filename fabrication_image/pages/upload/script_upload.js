$(document).ready(function() {
    var modul = $('section#upload');
    getUrlVars();

    modul.find('#error').hide();
    modul.find('#list').hide();

    modul.find('select#subcont').change(function() {
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


    modul.find('#btnCancel').click(function() {
        var project_typ = modul.find('#param').val();
        window.location = "../fabrication_image/fabrication_image.html?id=" + project_typ;
    });

    modul.find('#btnMarking').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../MARKING/marking.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont;
    });

    modul.find('#btnCutting').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../CUTTING/cutting.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont;
    });

    modul.find('#btnAssembly').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../ASSEMBLY/assembly.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont;
    });

    modul.find('#btnWelding').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../WELDING/welding.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont;
    });

    modul.find('#btnDrilling').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../DRILLING/drilling.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont;
    });

    modul.find('#btnFinishing').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head_mark').val();
        var subcont = modul.find('#subcont').val();
        window.location = "../FINISHING/finishing.html?id1=" + project_typ + "&id2=" + head_mark + "&id3=" + subcont;
    });

});



//##############################################################################

$(document).ajaxSend(function() {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function() {
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
        success: function(json) {
            modul.find("select#subcont").empty();
            var isiOption = "<option value='0'>- Pilih Subcont -</option>";
            $.each(json, function(index, row) {
                isiOption += "<option  value='" + row.SUBCONT_ID + "'>" + row.SUBCONT_ID + "</option>";
            });
            modul.find("select#subcont").append(isiOption);
            modul.find('select#subcont').selectmenu('refresh', true);
        },
        error: function() {
            alert('Error');
        }
    });

}

function total_img_mark(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_mark&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
//            alert(json);
            modul.find('#imgTotalMark').text(json[0].ELEMENT_TYP);
        },
        error: function() {
            alert('error');
        }
    });

}

function total_img_cutt(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_cutt&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
            modul.find('#imgTotalCutt').text(json[0].ELEMENT_TYP);
        },
        error: function() {
            alert('error');
        }
    });

}

function total_img_assy(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_assy&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
            modul.find('#imgTotalAssy').text(json[0].ELEMENT_TYP);
        },
        error: function() {
            alert('error');
        }
    });

}

function total_img_weld(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_weld&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
            modul.find('#imgTotalWeld').text(json[0].ELEMENT_TYP);
        },
        error: function() {
            alert('error');
        }
    });

}

function total_img_drill(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_drill&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
            modul.find('#imgTotalDrill').text(json[0].ELEMENT_TYP);
        },
        error: function() {
            alert('error');
        }
    });

}


function total_img_finsh(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=total_img_finsh&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
            modul.find('#imgTotalFinsh').text(json[0].ELEMENT_TYP);
        },
        error: function() {
            alert('error');
        }
    });

}



function view_list(head_mark, sub_cont) {
    var modul = $('section#upload');
    var dataString = 'action=view_list&id1=' + head_mark + '&id2=' + sub_cont;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_upload.php',
        data: dataString,
        success: function(json) {
            modul.find("#tot_gambar_mark").text(json[0].MARK)
            modul.find("#tot_gambar_cutt").text(json[0].CUTT)
            modul.find("#tot_gambar_assy").text(json[0].ASSY)
            modul.find("#tot_gambar_weld").text(json[0].WELD)
            modul.find("#tot_gambar_drill").text(json[0].DRILL)
            modul.find("#tot_gambar_finsh").text(json[0].FINSH)
        },
        error: function() {
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

        modul.find('#param').val(project_typ);
        modul.find('#head_mark').val(head_mark);
    }

    var head_mark = modul.find('#head_mark').val();
    view_sub_cont(head_mark)


}