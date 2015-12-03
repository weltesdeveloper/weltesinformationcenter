$(document).ready(function() {
    var modul = $('section#logout');
    getUrlVars();

    modul.find('#btnCancel').click(function() {
        var project_typ = modul.find('#param').val();
        window.location = "../fabrication_image/fabrication_image.html?id="+project_typ;
    })

    modul.find('#btnProses').click(function() {
        window.location = "../../index.html";
    })

});


function getUrlVars() {
    var modul = $('section#logout');
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
        var project_typ = queryStringList[i='id'].replace(/[+]/g, " ");
        modul.find('#param').val(project_typ);
//        view_job(project_typ);
    }
    
}
