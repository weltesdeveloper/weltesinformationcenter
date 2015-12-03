$(document).ready(function(){
	var Modul = $("section#view_product");
	
	listTabel();
	select_kat_product();	
	
	Modul.find('#ket').ckeditor();	
	
	Modul.find("button#btnAdd").click(function(){
		Modul.find("div#list").removeClass("animated fadeInDown").hide();
		Modul.find("div#form").addClass("animated fadeInDown").show();
	});
	
	Modul.find("button#btnCancel").click(function(){
		Modul.find("input#idProduct").val('');
		Modul.find("textarea#ket").val('');
		Modul.find('form#formInput')[0].reset();
		Modul.find('form#formInput').data('bootstrapValidator').resetForm();
		
		Modul.find("div#form").removeClass("animated fadeInDown").hide();
		Modul.find("div#list").addClass("animated fadeInDown").show();
	});
	
	Modul.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			kat_product : {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			nama_product: {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			}
		}
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	
		
		var options = {
			type		: "POST",
			dataType	: 'json',
			url			: "pages/M-Product/model-product.php",
			data		: {action:'commit'},
			success: function (json) {
				$.smallBox({
					title : "Saved",
					content : "<i class='fa fa-save'></i> "+json,
					color : "#3276B1",
					iconSmall : "fa fa-check",
					timeout : 3000
				});
			},
			complete	: function(){	
				Modul.find("input#idProduct").val('');
				Modul.find('form#formInput')[0].reset();
				Modul.find("#ket").val('');
				Modul.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				Modul.find("div#form").removeClass("animated fadeInDown").hide();
				Modul.find("div#list").addClass("animated fadeInDown").show();
			}
		};
		
		// ACTION UNTUK SUBMIT FORM
		Modul.find('form#formInput').ajaxSubmit(options); 
			return false;
		
	});
	

	// UNTUK UPLOAD FOTO OTOMATIS    
	Modul.find('input#imgInput').change(function(){
	    var ftype = this.files[0].type;
	    var statusFtype = false;
	    
	    //allow file types 
	    switch(ftype)
	    {
			case 'image/png': 
			case 'image/gif': 
			case 'image/jpeg': 
			case 'image/pjpeg':
				statusFtype = true;
				break;
			default:
				reset(Modul.find('input#imgInput'));
				Modul.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
				return false;
		}
      	    
		if(statusFtype){
			var fsize = this.files[0].size;
			var statusFsize = false;
      	    	
      	    	//Allowed file size is less than 1 MB (1242880)
			if(fsize>1242880) {
				reset(Modul.find('input#imgInput'));
				Modul.find('label#statusFile').text("Ukuran file harus di bawah 1 MB/1000 KB");
				return false;
			}
			else{
				statusFsize = true;
			}
      	}
	    
	    if(statusFtype && statusFsize){
		    if(this.files && this.files[0]) {
		        var reader = new FileReader();
		        reader.onload = function (e) {
		            Modul.find('img#displayFoto').attr('src', e.target.result);
		        }
		        reader.readAsDataURL(this.files[0]);

			Modul.find('label#statusFile').text("Upload "+this.files[0].name+" / "+bytesToSize(this.files[0].size));  
		    }
	    }	
	});
	
	// UNTUK UPLOAD FOTO OTOMATIS SELESAI
	
	

});

function list_gallery(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Product/model-product.php",
		data		: {action:'select' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var Modul = $("section#view_product");
	
	list_gallery(function(output){
	
		var jqTabel = Modul.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_product' },
				{ data: 'nama_product' },
				{ data: 'ket_product' },
				{ data: 'gambar_product' }
			],
			"columnDefs": [ 
				{ 
					"visible": true,  
					"targets": [ 0 ] 
				},
				{
					"targets": 1,
					"render": function ( data, type, row, meta ) {
						var isi = data;
						return isi;
					}
				},	
				{
					"targets": 2,
					"render": function ( data, type, row, meta ) {
						var conten = row.ket_product.substring(0,200);
						var isi = conten;						
						return isi;
					}
				},	/**/				
				{
					"targets": 4,
					"render": function ( data, type, row, meta ) {
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_product+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_product+")'><i class='fa fa-trash-o'></i></a> ";
						return edit + hapus;
					}
				}		
			]
		});
		
		
		Modul.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}



function select_kat_product(){
	var Modul = $("section#view_product");
	
	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Product/model-product.php",
		data		: {action:'selectKatProduct' },
		success		: function(json){
			Modul.find("select#kat_product").empty();
			var isiOption = "<option value=''>- Pilih Kategori Product -</option>";
			$.each(json, function(index, row) {											
				isiOption += "<option value='"+row.id_kat_product+"'>"+row.nama_kat_product+"</option>";
			});
			Modul.find("select#kat_product").append(isiOption);
		}
	});	

}


function hapusData(idRow){
	
	var Modul = $("section#view_product");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Product/model-product.php",
				data		: {action:'hapus',id:idRow },
				success		: function(json){
					$.smallBox({
						title : "Deleted",
						content : "<i class='fa fa-trash-o'></i> "+json,
						color : "#C46A69",
						iconSmall : "fa fa-check",
						timeout : 3000
					});
				},
				complete	: function(){
					listTabel();
				}
			});
			
		}
		if (ButtonPressed === "Tidak") {
			//No action close modal
		}

	});
	e.preventDefault();

}


function editData(idRow){
	var Modul = $("section#view_product");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Product/model-product.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			Modul.find('input#idProduct').val(json[0].id_product);
			Modul.find('select#kat_product').val(json[0].id_kat_product);
			Modul.find('input#nama_product').val(json[0].nama_product);
			Modul.find('textarea#ket').val(json[0].ket_product);
			Modul.find('input#gambar').val(json[0].gambar_product);
					
		},
		complete	: function(){
			Modul.find("div#form").addClass("animated fadeInDown").show();
			Modul.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
