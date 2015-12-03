$(document).ready(function () {
    var modul = $('section#welding');
    getUrlVars();


    modul.find('#btnProses').click(function () {
        var project_typ = modul.find('#param').val();
        var head_mark = modul.find('#head').val();
        var element = "mark";
        var remark = modul.find('#remark').val();

//        if (remark == "") {
//            alert("Keterangan Tidak Boleh Kosong");
//        } else {
        uploadPicture();
//        }        
    });

});


//##############################################################################

$(document).ajaxSend(function () {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function () {
    $.mobile.loading('hide');
});


function getUrlVars() {
    var modul = $('section#welding');
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
        var subcont = queryStringList[i = 'id3'].replace(/[+]/g, " ");
        var image_id = queryStringList[i = 'id4'].replace(/[+]/g, " ");
        var user = queryStringList[i = 'id5'].replace(/[+]/g, " ");

        modul.find('input#param').val(project_typ);
        modul.find('input#head').val(head_mark);
        modul.find('input#subcont').val(subcont);
        modul.find('input#image_id').val(image_id);
        modul.find('input#user').val(user);
    }
    var img_id = modul.find('input#image_id').val();
    if (img_id != "0") {
        modul.find('div#divQty').hide();
    }
}

function uploadPicture() {

    // Get URI of picture to upload
    var img = document.getElementById('camera_image');
    var imageURI = img.src;
    if (!imageURI || (img.style.display == "none")) {
        document.getElementById('camera_status').innerHTML = "Foto Tidak Boleh Kosong ...!";
        return;
    }

    // Verify server has been entered
    project_typ = document.getElementById('param').value;
    server = document.getElementById('serverUrl').value;
    head = document.getElementById('head').value;
    remark = document.getElementById('remark').value;
    subcont = document.getElementById('subcont').value;
    qty = document.getElementById('qty').value;
    image_id = document.getElementById('image_id').value;
    user = document.getElementById('user').value;

    if (server) {

        // Specify transfer options
        var options = new FileUploadOptions();
        options.fileKey = "file";
        options.fileName = imageURI.substr(imageURI.lastIndexOf('/') + 1);
        options.mimeType = "image/jpeg";

        var params = new Object();
        params.value1 = head;
        params.value2 = "weld";
        params.value3 = remark;
        params.value4 = subcont;
        params.value5 = qty;
        params.value6 = image_id;

        options.params = params;
        options.chunkedMode = false;

        // Transfer picture to server
        var ft = new FileTransfer();
        ft.upload(imageURI, server, function (r) {
            //document.getElementById('camera_status').innerHTML = "Upload successful: "+r.bytesSent+" bytes uploaded."; 
            alert("Suskses Upload Data");
            window.location = "../upload/upload.html?id1=" + project_typ + "&id2=" + head + "&id3=" + user;
        }, function (error) {
            //document.getElementById('camera_status').innerHTML = "Upload failed: Code = "+error.code;            	
            document.getElementById('camera_status').innerHTML = "Click Tombol Upload Picture, Sekali Lagi ...!";
        }, options);
    }
}

