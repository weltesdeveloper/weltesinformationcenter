$(document).ready(function(){

	var ModulBerita = $("section#viewBerita");
	listTabel();
	
	ModulBerita.find("textarea#isi").ckeditor();
	
	ModulBerita.find("div#list").addClass("animated fadeInDown");

	ModulBerita.find("button#btnAdd").click(function(){
		ModulBerita.find("div#list").removeClass("animated fadeInDown").hide();
		ModulBerita.find("div#form").addClass("animated fadeInDown").show();
	});
	
	ModulBerita.find("button#btnCancel").click(function(){
		ModulBerita.find("input#idBerita").val('');
		ModulBerita.find("textarea#isi").val('');
		ModulBerita.find('form#formInput')[0].reset();
		ModulBerita.find('form#formInput').data('bootstrapValidator').resetForm();
		
		ModulBerita.find("div#form").removeClass("animated fadeInDown").hide();
		ModulBerita.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulBerita.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {			
			judulBerita: {
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
			url			: "pages/M-Berita/model-berita.php",
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
				ModulBerita.find("input#idBerita").val('');
				ModulBerita.find("textarea#isi").val('');
				ModulBerita.find('form#formInput')[0].reset();
				ModulBerita.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				ModulBerita.find("div#form").removeClass("animated fadeInDown").hide();
				ModulBerita.find("div#list").addClass("animated fadeInDown").show();
			}
		};
		
		// ACTION UNTUK SUBMIT FORM
		ModulBerita.find('form#formInput').ajaxSubmit(options); 
			return false; 
		
	});
	
	
	// UNTUK UPLOAD FOTO OTOMATIS    
	ModulBerita.find('input#imgInput').change(function(){
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
				reset(ModulBerita.find('input#imgInput'));
				ModulBerita.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
				return false;
		}
      	    
		if(statusFtype){
			var fsize = this.files[0].size;
			var statusFsize = false;
      	    	
      	    	//Allowed file size is less than 1 MB (1242880)
			if(fsize>1242880) {
				reset(ModulBerita.find('input#imgInput'));
				ModulBerita.find('label#statusFile').text("Ukuran file harus di bawah 1 MB/1000 KB");
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
		            ModulBerita.find('img#displayFoto').attr('src', e.target.result);
		        }
		        reader.readAsDataURL(this.files[0]);

			ModulBerita.find('label#statusFile').text("Upload "+this.files[0].name+" / "+bytesToSize(this.files[0].size));  
		    }
	    }	
	});
	
	// UNTUK UPLOAD FOTO OTOMATIS SELESAI
	
	
	
});



function listBerita(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Berita/model-berita.php",
		data		: {action:'selectBerita' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulBerita = $("section#viewBerita");
	
	listBerita(function(output){
	
		var jqTabel = ModulBerita.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: false,
			data: output,
			columns: [
				{ data: 'id_berita' },
				{ data: 'judul' },
				{ data: 'isi' },
				{ data: 'gambar' },
				{ data: 'tgl_posting' }
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
						var conten = row.isi.substring(0,200);
						var isi = conten;						
						return isi;
					}
				},	/**/
				{
					"targets": 4,
					"render": function ( data, type, row, meta ) {
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_berita+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_berita+")'><i class='fa fa-trash-o'></i></a> ";
						return edit + hapus;
					}
				}		
			]
		});
		
		
		ModulBerita.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}


function hapusData(idRow){
	
	var ModulBerita = $("section#viewBerita");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Berita/model-berita.php",
				data		: {action:'hapusBerita',id:idRow },
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
	var ModulBerita = $("section#viewBerita");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Berita/model-berita.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			ModulBerita.find('input#idBerita').val(json[0].id_berita);
			ModulBerita.find('input#judulBerita').val(json[0].judul);
			ModulBerita.find('textarea#isi').val(json[0].isi);
			ModulBerita.find('input#gambar').val(json[0].gambar);
					
		},
		complete	: function(){
			ModulBerita.find("div#form").addClass("animated fadeInDown").show();
			ModulBerita.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
