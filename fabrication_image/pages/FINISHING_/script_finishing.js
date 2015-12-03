$(document).ready(function() {
    var modul = $('section#finishing');
    getUrlVars();

    modul.find('#btnProses').click(function() {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head').val();
        var element = "fins";
        var remark = modul.find('#remark').val();
        if (remark == "") {
            alert("Keterangan Tidak Boleh Kosong");
        } else {
            insert_detil_img(project_typ, head_mark, element, remark);
        }
    });
});


//##############################################################################

$(document).ajaxSend(function() {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function() {
    $.mobile.loading('hide');
});


function insert_detil_img(project_typ, head_mark, element, remark) {
    var dataString = 'action=insert_detil_img&id1=' + head_mark + '&id2=' + element + '&id3=' + remark;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_marking.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_marking.php',
        data: dataString,
        timeout: 5000,
        success: function(json) {
            if (json == '1') {
                alert("Data Berhasil Di Inputkan");
                window.location = "../upload/upload.html?id1=" + project_typ + "&id2=" + head_mark;
            } else {
                alert("Gagal");
            }
        },
        error: function() {
            alert('Error');
        }
    });

}


function getUrlVars() {
    var modul = $('section#finishing');
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

        modul.find('input#param').val(project_typ);
        modul.find('input#head').val(head_mark);
    }
}

