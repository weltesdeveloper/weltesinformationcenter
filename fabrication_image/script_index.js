$(document).ready(function() {
    $("#submit").click(function() {
        var user = $('#username').val();
        var pass = $('#pass').val();
        var dataString = 'action=login&usr='+user+'&psw='+pass;//&
        $.ajax({
            type: 'GET',
            dataType: 'json',
//            url: 'http://192.168.110.251:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_index.php',
            url: 'http://192.168.100.195:7777/weltesinformationcenter/Content/Fabrication/Update/Global/fabrication_image/API/model_index.php',
            data: dataString,
            timeout: 5000,
            success: function(json) {
//                alert(json);
                if(json=='1'){
                    alert('Login Success.');
                    window.location="pages/home/home.html";
                }else{
                    alert('Login Failed ...!');
                    window.location="index.html";                
                }
            },
            error: function() {
                alert('Error');
            }
        });
    });
});

$(document).ajaxSend(function() {
    $.mobile.loading('show');
});
$(document).ajaxComplete(function() {
    $.mobile.loading('hide');
});