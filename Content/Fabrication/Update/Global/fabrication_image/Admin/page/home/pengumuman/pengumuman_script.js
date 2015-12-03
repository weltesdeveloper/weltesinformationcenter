$(document).ready(function(){
	menampilkan_data();
	menampilkan_data_promosi();
});

function menampilkan_data(){
	
	var Modul = $("section#pengumuman_agenda");
	
	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "page/home/pengumuman/pengumuman_proses.php",
		data		: {action:'view'},
		success		: function(json){			
			Modul.find('#list_berita').empty();			
			$.each(json, function(index, row) {
				isi = '<div class="col-md-4 col-sm-6 col-xs-12">'+
							'<div class="item-box">'+
								'<figure>'+
									'<a class="item-hover" href="#">'+
										'<span class="overlay color2"></span>'+
										'<span class="inner">'+
											'<span class="block fa fa-plus fsize20"></span>'+
											'<strong>READ</strong> MORE'+
										'</span>'+
									'</a>'+
									'<img alt="" class="img-responsive" src="uploads/fotoBerita/kecil_'+row.gambar+'" width="409" height="271" />'+
								'</figure>'+
								'<div class="item-box-desc">'+
									'<h4>'+row.judul.substring(0,20)+' ...</h4>'+
									'<p>'+row.isi.substring(0,100)+'</p>'+
								'</div>'+
							'</div>'+
						'</div>';
				Modul.find('#list_berita').append(isi);
			});
			
		}
	});		
}


function menampilkan_data_promosi(){
	
	var Modul = $("section#pengumuman_agenda");
	
	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "page/home/pengumuman/pengumuman_proses.php",
		data		: {action:'view_promosi'},
		success		: function(json){			
			Modul.find('#content_promosi ul').empty();			
			$.each(json, function(index, row) {
				isi = '<li>'+
							'<strong class="styleColor">'+row.judul+'</strong>'+
							'<p class="fsize13">'+
								row.isi.substring(0,100)+
								'<small class="block styleSecondColor">'+row.hari+'., '+row.tgl_posting+', '+row.jam+'</small>'+
							'</p>'+
						'</li>';
				Modul.find('#content_promosi ul').append(isi);
			});
			
		}
	});		
}

