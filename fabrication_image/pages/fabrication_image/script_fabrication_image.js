$(document).ready(function() {
    var modul = $('section#fabrication');
    getUrlVars();

    modul.find('#pageHome').click(function() {
        window.location = "../home/home.html";
    });
    modul.find('#pageLogout').click(function() {
        var param = modul.find('#param').val();
        window.location = "../logout/logout.html?id=" + param;
    });


    //###################################################### LIST MENU
    modul.find('select#job').on("change", function() {
        var project_typ = modul.find('input#param').val();
        var project_no = modul.find('select#job').val();
        view_sub_job(project_typ, project_no);
    });

    modul.find('select#sub_job').change(function() {
        var project_no = modul.find('select#job').val();
        var project_name = modul.find('select#sub_job').val();
//        view_head_mark(project_name);
        view_head_mark(project_no, project_name);
    });
    
    modul.find('select#head_mark').change(function() {
        var head_mark = modul.find('select#head_mark').val();
//        view_head_mark(project_name);
        view_sub_cont(head_mark);
    });

    //###################################################### BTN PROSES
    modul.find('#btnProses').click(function() {
        var param = modul.find('#param').val();
        var job = modul.find('#job').val();
        var sub_job = modul.find('#sub_job').val();
        var head_mark = modul.find('#head_mark').val();

        if (job == '0') {
            alert("Pilih Job ...!");
        } else if (sub_job == '0') {
            alert("Pilih Sub Job ...!");
        } else if (head_mark == '0') {
            alert("Pilih Head Mark ...!");
        } else {
            insert_head_mark(param, head_mark);
        }


//        var job = modul.find('#job').val();
//        var sub_job = modul.find('#sub_job').val();
//        var head = modul.find('#head_mark').val();
//        window.location = "../upload/upload.html?id1="+param+"&id2="+job+"&id3="+sub_job+"&id4="+head;
    })



});

$(document).ajaxSend(function() {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function() {
    $.mobile.loading('hide');
});


function view_job(project_typ) {
    var modul = $('section#fabrication');
    var dataString = 'action=view_job&id=' + project_typ;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        data: dataString,
//        timeout: 5000,
        success: function(json) {

//            alert(json);
            modul.find("select#job").empty();
            var isiOption = "<option value='0'>- Pilih Job -</option>";
            $.each(json, function(index, row) {
                isiOption += "<option  value='" + row.PROJECT_NO + "'>" + row.PROJECT_NO + "</option>";
            });
            modul.find("select#job").append(isiOption);
        },
        error: function() {
            alert('Error');
        }
    });

}

function view_sub_job(project_typ, project_no) {
    var modul = $('section#fabrication');
//    alert(project_no);
    var dataString = 'action=view_sub_job&id1=' + project_typ + '&id2=' + project_no;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        data: dataString,
//        timeout: 5000,
        success: function(json) {
//            alert(json);
            modul.find("select#sub_job").empty();
            var isiOption = "<option value='0'>- Pilih Sub Job -</option>";
            $.each(json, function(index, row) {
                isiOption += "<option  value='" + row.PROJECT_NAME + "'>" + row.PROJECT_NAME + "</option>";
            });
            modul.find("select#sub_job").append(isiOption);
            modul.find('select#sub_job').selectmenu('refresh', true);
        },
        error: function() {
            alert('Error');
        }
    });

}


function view_head_mark(project_no, project_name) {
//function view_head_mark(project_name) {
    var modul = $('section#fabrication');
//    alert(project_no);
    var dataString = 'action=view_head_mark&id1=' + project_no + '&id2=' + project_name;//&
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


function insert_head_mark(param, head_mark) {
    var modul = $('section#fabrication');
//    alert(project_no);
    var dataString = 'action=insert_head_mark&id=' + head_mark;//&
    $.ajax({
        type: 'GET',
        dataType: 'json',
//        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_fabrication_image.php',
        data: dataString,
//        timeout: 5000,
        success: function(json) {
//            alert(json);
            if (json == '1') {
//                alert("Data Sudah ada");
                window.location = "../upload/upload.html?id1=" + param + "&id2=" + head_mark;
            } else {
//                alert("Data Kosong");
                window.location = "../upload/upload.html?id1=" + param + "&id2=" + head_mark;
            }
        },
        error: function() {
            alert('error');
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
        view_job(project_typ);
        modul.find('#param').val(project_typ);
    }

}