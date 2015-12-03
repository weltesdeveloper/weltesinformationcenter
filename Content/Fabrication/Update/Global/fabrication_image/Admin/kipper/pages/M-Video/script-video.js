$(document).ready(function(){

	var ModulVideo = $("section#viewVideo");
	listTabel();
	
	
	ModulVideo.find("div#list").addClass("animated fadeInDown");

	ModulVideo.find("button#btnAdd").click(function(){
		ModulVideo.find("div#list").removeClass("animated fadeInDown").hide();
		ModulVideo.find("div#form").addClass("animated fadeInDown").show();
	});
	
	ModulVideo.find("button#btnCancel").click(function(){
		ModulVideo.find("input#idVideo").val('');
		ModulVideo.find('form#formInput')[0].reset();
		ModulVideo.find('form#formInput').data('bootstrapValidator').resetForm();
		
		ModulVideo.find("div#form").removeClass("animated fadeInDown").hide();
		ModulVideo.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulVideo.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			
			nama: {
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
			url			: "pages/M-Video/model-video.php",
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
				ModulVideo.find("input#idVideo").val('');
				ModulVideo.find('form#formInput')[0].reset();
				ModulVideo.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				ModulVideo.find("div#form").removeClass("animated fadeInDown").hide();
				ModulVideo.find("div#list").addClass("animated fadeInDown").show();
			}
		};
		
		// ACTION UNTUK SUBMIT FORM
		ModulVideo.find('form#formInput').ajaxSubmit(options); 
			return false; 
		
		
	});
	
	// UNTUK UPLOAD FOTO OTOMATIS    
	/*ModulVideo.find('input#imgInput').change(function(){
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
				reset(ModulVideo.find('input#imgInput'));
				ModulVideo.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
				return false;
		}
      	    
		if(statusFtype){
			var fsize = this.files[0].size;
			var statusFsize = false;
      	    	
      	    	//Allowed file size is less than 1 MB (1242880)
			if(fsize>500) {
				reset(ModulVideo.find('input#imgInput'));
				ModulVideo.find('label#statusFile').text("Ukuran file harus di bawah 1 MB/1000 KB");
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
		            ModulVideo.find('img#displayFoto').attr('src', e.target.result);
		        }
		        reader.readAsDataURL(this.files[0]);

			ModulVideo.find('label#statusFile').text("Upload "+this.files[0].name+" / "+bytesToSize(this.files[0].size));  
		    }
	    }	
	});*/
	
	// UNTUK UPLOAD FOTO OTOMATIS SELESAI
	
});



function listVideo(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Video/model-video.php",
		data		: {action:'selectVideo' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulVideo = $("section#viewVideo");
	
	listVideo(function(output){
	
		var jqTabel = ModulVideo.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_video' },
				{ data: 'nama' },
				{ data: 'file' },
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
					"targets": 4,
					"render": function ( data, type, row, meta ) {
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_video+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_video+")'><i class='fa fa-trash-o'></i></a> ";
						return edit + hapus;
					}
				}		
			]
		});
		
		
		ModulVideo.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}


function hapusData(idRow){
	
	var ModulVideo = $("section#viewVideo");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Video/model-video.php",
				data		: {action:'hapusVideo',id:idRow },
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
	var ModulVideo = $("section#viewVideo");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Video/model-video.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			ModulVideo.find('input#idVideo').val(json[0].id_video);
			ModulVideo.find('input#nama').val(json[0].nama);
			ModulVideo.find('input#FileInputVideo').val(json[0].file);
					
		},
		complete	: function(){
			ModulVideo.find("div#form").addClass("animated fadeInDown").show();
			ModulVideo.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
