$(document).ready(function() {
    var modul = $('section#marking');
    modul.find('#btnProses').click(function() {
        uploadBlob();
    });
});

$.ajax({
    type: 'POST',
    data: formData,
    url: "../packingAssignmentNonProduct/divpages/upload_file.php",
    contentType: false,
    cache: false,
    processData: false,
    success: function(response, textStatus, jqXHR) {
        swal(response, "", "success");
//                                redirect("PACKING");
        window.location.reload();
    }
});

