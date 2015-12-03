$(document).ready(function(){

	var ModulInformasi = $("section#viewInformasi");
	listTabel();
	
	ModulInformasi.find("textarea#isi").ckeditor();
	
	ModulInformasi.find("div#list").addClass("animated fadeInDown");

	ModulInformasi.find("button#btnAdd").click(function(){
		ModulInformasi.find("div#list").removeClass("animated fadeInDown").hide();
		ModulInformasi.find("div#form").addClass("animated fadeInDown").show();
	});
	
	ModulInformasi.find("button#btnCancel").click(function(){
		ModulInformasi.find("input#idInformasi").val('');
		ModulInformasi.find("textarea#isi").val('');
		ModulInformasi.find('form#formInput')[0].reset();
		ModulInformasi.find('form#formInput').data('bootstrapValidator').resetForm();
		
		ModulInformasi.find("div#form").removeClass("animated fadeInDown").hide();
		ModulInformasi.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulInformasi.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			
			judulIn: {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			pf: {
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
			url			: "pages/M-Informasi/model-informasi.php",
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
				ModulInformasi.find("input#idInformasi").val('');
				ModulInformasi.find("textarea#isi").val('');
				ModulInformasi.find('form#formInput')[0].reset();
				ModulInformasi.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				ModulInformasi.find("div#form").removeClass("animated fadeInDown").hide();
				ModulInformasi.find("div#list").addClass("animated fadeInDown").show();
			}
		};
		
		// ACTION UNTUK SUBMIT FORM
		ModulInformasi.find('form#formInput').ajaxSubmit(options); 
			return false; 
		
	});
	
	
	// UNTUK UPLOAD FOTO OTOMATIS    
	ModulInformasi.find('input#imgInput').change(function(){
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
				reset(ModulInformasi.find('input#imgInput'));
				ModulInformasi.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
				return false;
		}
      	    
		if(statusFtype){
			var fsize = this.files[0].size;
			var statusFsize = false;
      	    	
      	    	//Allowed file size is less than 1 MB (1242880)
			if(fsize>1242880) {
				reset(ModulInformasi.find('input#imgInput'));
				ModulInformasi.find('label#statusFile').text("Ukuran file harus di bawah 1 MB/1000 KB");
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
		            ModulInformasi.find('img#displayFoto').attr('src', e.target.result);
		        }
		        reader.readAsDataURL(this.files[0]);

			ModulInformasi.find('label#statusFile').text("Upload "+this.files[0].name+" / "+bytesToSize(this.files[0].size));  
		    }
	    }	
	});
	
	// UNTUK UPLOAD FOTO OTOMATIS SELESAI
	
	
	
});



function listPengumuman(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Informasi/model-informasi.php",
		data		: {action:'selectInformasi' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulInformasi = $("section#viewInformasi");
	
	listPengumuman(function(output){
	
		var jqTabel = ModulInformasi.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_informasi' },
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
					"targets": 5,
					"render": function ( data, type, row, meta ) {
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_informasi+")'><i class='fa fa-edit'></i></a> ";
						  
						//var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						//hapus += "onclick='hapusData("+row.id_informasi+")'><i class='fa fa-trash-o'></i></a> ";
						return edit;
					}
				}		
			]
		});
		
		
		ModulInformasi.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}


function hapusData(idRow){
	
	var ModulInformasi = $("section#viewInformasi");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Informasi/model-informasi.php",
				data		: {action:'hapusInformasi',id:idRow },
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
	var ModulInformasi = $("section#viewInformasi");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Informasi/model-informasi.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			ModulInformasi.find('input#idInformasi').val(json[0].id_informasi);
			ModulInformasi.find('input#judulIn').val(json[0].judul);
			ModulInformasi.find('select#pf').val(json[0].posisi_foto);
			ModulInformasi.find('textarea#isi').val(json[0].isi);
			
					
		},
		complete	: function(){
			
			ModulInformasi.find("div#form").addClass("animated fadeInDown").show();
			ModulInformasi.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
