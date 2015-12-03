$(document).ready(function() {

    var modul = $('section#home');
    
    //###################################################### MENU CONTENT
    modul.find('#STRUCTURE').click(function() {
        window.location = "../fabrication_image/fabrication_image.html?id=STRUCTURE";
    });
    modul.find('#EQUIPMENT').click(function() {
        alert("Under Construction");
    });
    modul.find('#OTHERS').click(function() {
        alert("Under Construction");
//        window.location = "../fabrication_image/fabrication_image.html?id=OTHERS";
    });
    modul.find('#TANKAGE').click(function() {
        alert("Under Construction");
//        window.location = "../fabrication_image/fabrication_image.html?id=TANKAGE";
    });
//
//    //###################################################### LIST MENU
//    modul.find('select#job').on("change", function() {
//        var project_no = modul.find('select#job').val();
//        view_sub_job(project_no);
//    });
//
//    modul.find('select#sub_job').change(function() {
//        var project_name = modul.find('select#sub_job').val();
//        view_head_mark(project_name);
//    });

//    //###################################################### UPLOAD UNTUK MARKING    
//    modul.find('input#imgInputMarking').change(function() {
//        var ftype = this.files[0].type;
//        var statusFtype = false;
//
//        //allow file types 
//        switch (ftype)
//        {
//            case 'image/png':
//            case 'image/gif':
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                statusFtype = true;
//                break;
//            default:
//                reset(modul.find('input#imgInputMarking'));
////                modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
//                return false;
//        }
//
//        if (statusFtype) {
//            var fsize = this.files[0].size;
//            var statusFsize = false;
//
//            //Allowed file size is less than 1 MB (1242880)
//            if (fsize > 1242880) {
//                reset(modul.find('input#imgInputMarking'));
////                modul.find('label#statusFileInsert').text("Ukuran file harus di bawah 1 MB/1000 KB");
//                return false;
//            }
//            else {
//                statusFsize = true;
//            }
//        }
//
//        if (statusFtype && statusFsize) {
//            if (this.files && this.files[0]) {
//                var reader = new FileReader();
//                reader.onload = function(e) {
//                    modul.find('img#displayFotoMarking').attr('src', e.target.result);
//                }
//                reader.readAsDataURL(this.files[0]);
////                modul.find('label#statusFileInsert').text("Upload " + this.files[0].name + " / " + bytesToSize(this.files[0].size));
//            }
//        }
//    });
//
//
//    //###################################################### UPLOAD UNTUK CUTTING    
//    modul.find('input#imgInputCutting').change(function() {
//        var ftype = this.files[0].type;
//        var statusFtype = false;
//
//        //allow file types 
//        switch (ftype)
//        {
//            case 'image/png':
//            case 'image/gif':
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                statusFtype = true;
//                break;
//            default:
//                reset(modul.find('input#imgInputCutting'));
////                modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
//                return false;
//        }
//
//        if (statusFtype) {
//            var fsize = this.files[0].size;
//            var statusFsize = false;
//
//            //Allowed file size is less than 1 MB (1242880)
//            if (fsize > 1242880) {
//                reset(modul.find('input#imgInputCutting'));
////                modul.find('label#statusFileInsert').text("Ukuran file harus di bawah 1 MB/1000 KB");
//                return false;
//            }
//            else {
//                statusFsize = true;
//            }
//        }
//
//        if (statusFtype && statusFsize) {
//            if (this.files && this.files[0]) {
//                var reader = new FileReader();
//                reader.onload = function(e) {
//                    modul.find('img#displayFotoCutting').attr('src', e.target.result);
//                }
//                reader.readAsDataURL(this.files[0]);
////                modul.find('label#statusFileInsert').text("Upload " + this.files[0].name + " / " + bytesToSize(this.files[0].size));
//            }
//        }
//    });
//
//
//    //###################################################### UPLOAD UNTUK Assembly    
//    modul.find('input#imgInputAssembly').change(function() {
//        var ftype = this.files[0].type;
//        var statusFtype = false;
//
//        //allow file types 
//        switch (ftype)
//        {
//            case 'image/png':
//            case 'image/gif':
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                statusFtype = true;
//                break;
//            default:
//                reset(modul.find('input#imgInputAssembly'));
////                modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
//                return false;
//        }
//
//        if (statusFtype) {
//            var fsize = this.files[0].size;
//            var statusFsize = false;
//
//            //Allowed file size is less than 1 MB (1242880)
//            if (fsize > 1242880) {
//                reset(modul.find('input#imgInputAssembly'));
////                modul.find('label#statusFileInsert').text("Ukuran file harus di bawah 1 MB/1000 KB");
//                return false;
//            }
//            else {
//                statusFsize = true;
//            }
//        }
//
//        if (statusFtype && statusFsize) {
//            if (this.files && this.files[0]) {
//                var reader = new FileReader();
//                reader.onload = function(e) {
//                    modul.find('img#displayFotoAssembly').attr('src', e.target.result);
//                }
//                reader.readAsDataURL(this.files[0]);
////                modul.find('label#statusFileInsert').text("Upload " + this.files[0].name + " / " + bytesToSize(this.files[0].size));
//            }
//        }
//    });
//
//
//
//    //###################################################### UPLOAD UNTUK Welding    
//    modul.find('input#imgInputWelding').change(function() {
//        var ftype = this.files[0].type;
//        var statusFtype = false;
//
//        //allow file types 
//        switch (ftype)
//        {
//            case 'image/png':
//            case 'image/gif':
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                statusFtype = true;
//                break;
//            default:
//                reset(modul.find('input#imgInputWelding'));
////                modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
//                return false;
//        }
//
//        if (statusFtype) {
//            var fsize = this.files[0].size;
//            var statusFsize = false;
//
//            //Allowed file size is less than 1 MB (1242880)
//            if (fsize > 1242880) {
//                reset(modul.find('input#imgInputWelding'));
////                modul.find('label#statusFileInsert').text("Ukuran file harus di bawah 1 MB/1000 KB");
//                return false;
//            }
//            else {
//                statusFsize = true;
//            }
//        }
//
//        if (statusFtype && statusFsize) {
//            if (this.files && this.files[0]) {
//                var reader = new FileReader();
//                reader.onload = function(e) {
//                    modul.find('img#displayFotoWelding').attr('src', e.target.result);
//                }
//                reader.readAsDataURL(this.files[0]);
////                modul.find('label#statusFileInsert').text("Upload " + this.files[0].name + " / " + bytesToSize(this.files[0].size));
//            }
//        }
//    });
//
//
//    //###################################################### UPLOAD UNTUK Drilling    
//    modul.find('input#imgInputDrilling').change(function() {
//        var ftype = this.files[0].type;
//        var statusFtype = false;
//
//        //allow file types 
//        switch (ftype)
//        {
//            case 'image/png':
//            case 'image/gif':
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                statusFtype = true;
//                break;
//            default:
//                reset(modul.find('input#imgInputDrilling'));
////                modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
//                return false;
//        }
//
//        if (statusFtype) {
//            var fsize = this.files[0].size;
//            var statusFsize = false;
//
//            //Allowed file size is less than 1 MB (1242880)
//            if (fsize > 1242880) {
//                reset(modul.find('input#imgInputDrilling'));
////                modul.find('label#statusFileInsert').text("Ukuran file harus di bawah 1 MB/1000 KB");
//                return false;
//            }
//            else {
//                statusFsize = true;
//            }
//        }
//
//        if (statusFtype && statusFsize) {
//            if (this.files && this.files[0]) {
//                var reader = new FileReader();
//                reader.onload = function(e) {
//                    modul.find('img#displayFotoDrilling').attr('src', e.target.result);
//                }
//                reader.readAsDataURL(this.files[0]);
////                modul.find('label#statusFileInsert').text("Upload " + this.files[0].name + " / " + bytesToSize(this.files[0].size));
//            }
//        }
//    });
//
//
//    //###################################################### UPLOAD UNTUK Finishing    
//    modul.find('input#imgInputFinishing').change(function() {
//        var ftype = this.files[0].type;
//        var statusFtype = false;
//
//        //allow file types 
//        switch (ftype)
//        {
//            case 'image/png':
//            case 'image/gif':
//            case 'image/jpeg':
//            case 'image/pjpeg':
//                statusFtype = true;
//                break;
//            default:
//                reset(modul.find('input#imgInputFinishing'));
////                modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
//                return false;
//        }
//
//        if (statusFtype) {
//            var fsize = this.files[0].size;
//            var statusFsize = false;
//
//            //Allowed file size is less than 1 MB (1242880)
//            if (fsize > 1242880) {
//                reset(modul.find('input#imgInputFinishing'));
////                modul.find('label#statusFileInsert').text("Ukuran file harus di bawah 1 MB/1000 KB");
//                return false;
//            }
//            else {
//                statusFsize = true;
//            }
//        }
//
//        if (statusFtype && statusFsize) {
//            if (this.files && this.files[0]) {
//                var reader = new FileReader();
//                reader.onload = function(e) {
//                    modul.find('img#displayFotoFinishing').attr('src', e.target.result);
//                }
//                reader.readAsDataURL(this.files[0]);
////                modul.find('label#statusFileInsert').text("Upload " + this.files[0].name + " / " + bytesToSize(this.files[0].size));
//            }
//        }
//    });
//
//
//    modul.find('#capturePhoneButton').click(function(e) {
//        e.preventDefault();
//        capturePhoto();
//        alert("Tes");
//        return false;
//    });
//
//    var idUrl = getUrlVars()["id"];
////    var arg1 = getUrlVars.param("id");
//    alert(idUrl);
//


});


//##############################################################################


//
//function view_job(project_typ) {
//    var modul = $('section#home');
//    var dataString = 'action=view_job&id=' + project_typ;//&
//    $.ajax({
//        type: 'GET',
//        dataType: 'json',
////        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_home.php',
//        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_home.php',
//        data: dataString,
//        timeout: 5000,
//        success: function(json) {
//            modul.find("select#job").empty();
//            var isiOption = "<option value='0'>- Pilih Job -</option>";
//            $.each(json, function(index, row) {
//                isiOption += "<option  value='" + row.PROJECT_NO + "'>" + row.PROJECT_NO + "</option>";
//            });
//            modul.find("select#job").append(isiOption);
//        },
//        error: function() {
//            alert('Error');
//        }
//    });
//
//}
//
//
//function view_sub_job(project_no) {
//    var modul = $('section#home');
////    alert(project_no);
//    var dataString = 'action=view_sub_job&id=' + project_no;//&
//    $.ajax({
//        type: 'GET',
//        dataType: 'json',
////        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_home.php',
//        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_home.php',
//        data: dataString,
//        timeout: 5000,
//        success: function(json) {
////            alert(json);
//            modul.find("select#sub_job").empty();
//            var isiOption = "<option value='0'>- Pilih Sub Job -</option>";
//            $.each(json, function(index, row) {
//                isiOption += "<option  value='" + row.PROJECT_NAME + "'>" + row.PROJECT_NAME + "</option>";
//            });
//            modul.find("select#sub_job").append(isiOption);
//            modul.find('select#sub_job').selectmenu('refresh', true);
//        },
//        error: function() {
//            alert('Error');
//        }
//    });
//
//}
//
//
//function view_head_mark(project_name) {
//    var modul = $('section#home');
////    alert(project_no);
//    var dataString = 'action=view_head_mark&id=' + project_name;//&
//    $.ajax({
//        type: 'GET',
//        dataType: 'json',
////        url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_home.php',
//        url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_home.php',
//        data: dataString,
//        timeout: 5000,
//        success: function(json) {
////            alert(json);
//            modul.find("select#head_mark").empty();
//            var isiOption = "<option value='0'>- Pilih Head Mark -</option>";
//            $.each(json, function(index, row) {
//                isiOption += "<option  value='" + row.HEAD_MARK + "'>" + row.HEAD_MARK + "</option>";
//            });
//            modul.find("select#head_mark").append(isiOption);
//            modul.find('select#head_mark').selectmenu('refresh', true);
//        },
//        error: function() {
//            alert('Data Head Mark Kosong');
//        }
//    });
//
//}
//
//function HandleFileButtonClick() { /* this click event is in the image */
//    document.frmUpload.imgInputMarking.click(); /* "myFile" is name field for the browse input button */
//}
//
//
//
////var pictureSource;
////var destinationType;
//
//function updateCameraStatus(status) {
//    var modul = $('section#home');
//    modul.find("#cameraStatus").html(status);
//}
//
//function photoOnFail(message) {
//    updateCameraStatus("ERROR: " + message);
//}
//
//function onPhotoDataSuccess(imageData) {
//    var modul = $('section#home');
//    modul.find("#popupPhoto").popup("open");
//    modul.find("#popImage").attr("src", "data:image/jpeg;base64," + imageData);
//}
//
//function onPhotoURISuccess(imageURI) {
//    var modul = $('section#home');
//    modul.find("#popImage").attr("src", imageURI);
//    //$("#pictBox").empty();
//    //$("#pictBox").append(imageURI).trigger("create");
//    modul.find("#pictBox").html(imageURI);
//    updateCameraStatus("SUCCESS: Image loaded");
//    modul.find("#popupPhoto").popup("open");
//}
//
//function capturePhoto() {
//    navigator.camera.getPicture(onPhotoURISuccess, photoOnFail, {quality: 50, destinationType: destinationType.FILE_URI});
//    alert("Photo");
//}
//
////Android ignores the allowEdit parameter
//function capturePhotoEdit() {
//    navigator.camera.getPicture(onPhotoDataSuccess, photoOnFail, {quality: 20, allowEdit: true, destinationType: destinationType.DATA_URL});
//}
//
////source could be Camera.PictureSourceType.PHOTOLIBRARY and SAVEDPHOTOALBUM, in Android, they are the same.
//function getPhoto(source) {
//    updateCameraStatus("");
//    navigator.camera.getPicture(onPhotoURISuccess, photoOnFail, {quality: 50, destinationType: destinationType.FILE_URI, sourceType: source});
//}
//
//function getCameraReady() {
//    var modul = $('section#home');
////            modul.find("popupPhoto").popup("close");     
//    updateCameraStatus("");
////            pictureSource   = navigator.camera.PictureSourceType;
////            destinationType = navigator.camera.DestinationType;
//
//    modul.find('#capturePhoneButton').on('vclick', function(e) {
//        e.preventDefault();
//        capturePhoto();
//        return false;
//    });
//
//}
//
////*********************************************************    
//// initialize the environment
////********************************************************* 
////$(document).bind("pagebeforeshow", function() {
////    var modul = $('section#home');
//////            getCameraReady(); 
////    modul.find('#capturePhoneButton').on('vclick', function(e) {
////        e.preventDefault();
////        capturePhoto();
////        return false;
////    });
////});
//
//
//function getUrlVars(id) {
//
//    var url = window.location.toString();
//    
////ambil bagian parameternya
//    url.match(/\?(.+)$/);
//    var params = RegExp.$1;
//    
//// pisahkan parameter URL ke associative array
//    var params = params.split("&");
//    var queryStringList = {};
//    for (var i = 0; i < params.length; i++)
//    {
//        var tmp = params[i].split("=");
//        queryStringList[tmp[0]] = unescape(tmp[1]);
//    }
//    
//// tampilkan isi associative array
//    for (var i in queryStringList)
//    {
//        var res1 = queryStringList[i='id'].replace(/[+]/g, " ");
//        var res2 = queryStringList[i='id2'].replace(/[+]/g, " ");
//        var res3 = queryStringList[i='id3'].replace(/[+]/g, " ");
////        document.write(i + " = " + res + "<br/>");
//        $('#tes').val(res1);
//        $('#tes2').val(res2);
//        $('#tes3').val(res3);
//    }
//    
//}
//
