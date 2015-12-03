$(document).ready(function(){
	menampilkan_data();
	//alert("tes");
});


function menampilkan_data(){
	
	var Modul = $("section#promosi");
	
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "page/promosi/model-promosi.php",
			data		: {action:'view'},
			success		: function(json){
				Modul.find('#list_promosi').empty();	
				$.each(json, function(index, row) {
					isi = '<div class="row margin-top30">'+
								'<div class="col-md-2 text-center">'+
									'<i class="nomargin featured-icon fa fa-cogs"></i>'+
								'</div>'+
								'<div class="col-md-10">'+
									'<h3 align="center">'+row.judul+'</h3>'+
									'<p>'+row.isi.substring(0,400)+'</p>'+
								'</div>'+
							'</div>'+
							'<div class="divider half-margins">'+
								'<i class="fa fa-plus-circle"></i>'+
							'</div>';
					Modul.find('#list_promosi').append(isi);
				});
						
			}
		});	
	
}
