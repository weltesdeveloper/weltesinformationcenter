$(document).ready(function() {
    var modul = $('section#fabrication');
    getUrlVars();

    modul.find('#pageHome').click(function() {
        var user = modul.find('#user').val();
        window.location = "../home/home.html?id1=" + user;
    });
    modul.find('#pageLogout').click(function() {
        var param = modul.find('#param').val();
        var user = modul.find('#user').val();
        window.location = "../logout/logout.html?id1=" + param + "&id2=" + user;
    });


    //###################################################### LIST MENU
    modul.find('select#head_mark').change(function() {
        var head_mark = modul.find('select#head_mark').val();
        view_sub_cont(head_mark);
    });

    //###################################################### BTN PROSES
    modul.find('#btnProses').click(function() {
        var user = modul.find('#user').val();
        var param = modul.find('#param').val();
        var job = modul.find('#job').val();
        var sub_job = modul.find('#sub_job').val();
        var head_mark = modul.find('#head_mark').val();

        if (head_mark == '0') {
            alert("Pilih Head Mark ...!");
        } else {
            window.location = "../upload/upload.html?id1=" + param + "&id2=" + head_mark + "&id3=" + user;
        }
    })



});

$(document).ajaxSend(function() {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function() {
    $.mobile.loading('hide');
});


function view_head_mark(user) {
//function view_head_mark(project_name) {
    var modul = $('section#fabrication');
//    alert(project_no);
    var dataString = 'action=view_head_mark&id1=' + user;//&
//    var dataString = 'action=view_head_mark&id=' + project_name;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        data: dataString,
//        timeout: 5000,
        success: function(json) {
//            alert(json);
            modul.find("select#head_mark").empty();
            var isiOption = "<option value='0'>- Pilih Head Mark -</option>";
            $.each(json, function(index, row) {
                isiOption += "<option  value='" + row.HEAD_MARK + "'>" + row.HEAD_MARK + "</option>";
            });
            modul.find("select#head_mark").append(isiOption);
            modul.find('select#head_mark').selectmenu('refresh', true);
        },
        error: function() {
            alert('Data Head Mark Kosong');
        }
    });

}


//################################################################### Fungsi untuk menangkap Variable
function getUrlVars() {
    var modul = $('section#fabrication');
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
        var project_typ = queryStringList[i = 'id'].replace(/[+]/g, " ");        
        var user = queryStringList[i = 'id2'].replace(/[+]/g, " ");        
        modul.find('#param').val(project_typ);
        modul.find('#user').val(user);
        view_head_mark(user)
    }

}