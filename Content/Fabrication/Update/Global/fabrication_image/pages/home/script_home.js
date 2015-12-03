$(document).ready(function() {

    var modul = $('section#home');
    getUrlVars();
    
    //###################################################### MENU CONTENT
    modul.find('#STRUCTURE').click(function() {
        var user = modul.find('input#user').val();
        window.location = "../fabrication_image/fabrication_image.html?id=STRUCTURE&id2="+user;
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
    
});


function getUrlVars() {
    var modul = $('section#home');
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
        var user = queryStringList[i = 'id1'].replace(/[+]/g, " ");
        modul.find('input#user').val(user);
    }
}