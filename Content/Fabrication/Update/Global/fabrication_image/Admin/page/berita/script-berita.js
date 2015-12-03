$(document).ready(function(){
	menampilkan_data_berita();
	//alert("tes");
});


function menampilkan_data_berita(){
	
	var Modul = $("section#berita");
	
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "page/berita/model-berita.php",
			data		: {action:'view_berita'},
			success		: function(json){
				Modul.find('#list_berita').empty();	
				$.each(json, function(index, row) {
					isi = '<div class="row margin-top30">'+
								'<div class="col-md-2 text-center">'+
									'<img alt="" class="img-responsive " src="uploads/fotoBerita/kecil_'+row.gambar+'"/>'+
								'</div>'+
								'<div class="col-md-10">'+
									'<h3 align="center">'+row.judul+'</h3>'+
									'<p>'+row.isi.substring(0,400)+'</p>'+
								'</div>'+
							'</div>'+
							'<div class="divider half-margins">'+
								'<i class="fa fa-plus-circle"></i>'+
							'</div>';
					Modul.find('#list_berita').append(isi);
				});
						
			}
		});	
	
}
