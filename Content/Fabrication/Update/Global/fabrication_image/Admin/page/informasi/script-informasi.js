$(document).ready(function(){
	menampilkan_data();
	//alert("tes");
});


function menampilkan_data(){
	
	var Modul = $("section#informasi");
		var id_ = Modul.find("#id").val();
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "page/informasi/model-informasi.php",
			data		: {action:'view', id:id_},
			success		: function(json){
			
				Modul.find('#list_informasi article').empty();	
				$.each(json, function(index, row) {
					isi = '<div class="col-md-4">'+								
								'<div>'+
									'<img class="img-responsive" src="uploads/fotoInformasi/kecil_'+row.gambar+'" align="left"  hspace="10" vspace="10" width="555" height="311" alt="">'+
								'</div>'+
							'</div>'+
							'<div class="col-md-8">'+
								'<h3><b>'+row.judul+'</b></h3>'+
								'<p>'+row.hari+'., '+row.tgl_posting+', '+row.jam+'</p>'+
								'<p>'+row.isi+'</p>'+								
								'<hr />'+
							'</div>';
					Modul.find('#list_informasi article').append(isi);
				});
			
			}
		});	
	
}
