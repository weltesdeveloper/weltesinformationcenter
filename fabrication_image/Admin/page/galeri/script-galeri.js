$(document).ready(function(){	
	menampilkan_data_gallery();
	menampilkan_data_gallery_detail();	
	alert("Product Kami");
});


function menampilkan_data_gallery(){
	
	var Modul = $("section#section_galeri");
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "page/galeri/model-galeri.php",
			data		: {action:'view_gallery'},
			success		: function(json){
				$.each(json, function(index, row) {
					isi = '<li data-option-value=".'+row.id_kat_gallery+'"><a href="#">'+row.nama_kat_gallery+'</a></li>';
					Modul.find('.nav').append(isi);
				});				
			}
		});	
	
}

function menampilkan_data_gallery_detail(){
	
	var Modul = $("section#section_galeri");
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "page/galeri/model-galeri.php",
			data		: {action:'view_gallery_detil'},
			success		: function(json){
				$.each(json, function(index, row) {
					isi = '<li class="isotope-item col-md-3 '+row.id_kat_gallery+'">'+
								'<div class="item-box fixed-box">'+
									'<figure>'+
										'<a class="item-hover" href="uploads/fotoGallery/'+row.gambar_gallery+'">'+
											'<span class="overlay color2"></span>'+
											'<span class="inner">'+
												'<span class="block fa fa-plus fsize20"></span>'+
												'<strong>VIEW</strong> IMAGE'+
											'</span>'+
										'</a>'+
										'<img class="img-responsive" src="uploads/fotoGallery/kecil_'+row.gambar_gallery+'" width="260" height="260" alt="">'+
									'</figure>'+
									'<div class="item-box-desc">'+
										'<h4>'+row.nama_gallery+'</h4>'+
										'<small class="styleColor">'+row.ket_gallery.substring(0,100)+' ...</small>'+
										'<div class="text-center">'+
											'<a href="https://www.facebook.com" class="social fa fa-facebook"></a>'+
											'<a href="#" class="social fa fa-twitter"></a>'+
											'<a href="#" class="social fa fa-google-plus"></a>'+
										'</div>'+
									'</div>'+
								'</div>'+
							'</li>';
					Modul.find('.sort-destination').append(isi);
				});				
			}
		});	
	
}


