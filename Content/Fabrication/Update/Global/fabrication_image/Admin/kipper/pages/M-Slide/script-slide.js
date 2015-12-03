$(document).ready(function(){

	var ModulSlide = $("section#viewSlide");
	listTabel();
	
	ModulSlide.find("textarea#ket").ckeditor();
	
	ModulSlide.find("div#list").addClass("animated fadeInDown");

	ModulSlide.find("button#btnAdd").click(function(){
		ModulSlide.find("div#list").removeClass("animated fadeInDown").hide();
		ModulSlide.find("div#form").addClass("animated fadeInDown").show();
	});
	
	ModulSlide.find("button#btnCancel").click(function(){
		ModulSlide.find("input#idSlide").val('');
		ModulSlide.find("textarea#ket").val('');
		ModulSlide.find('form#formInput')[0].reset();
		ModulSlide.find('form#formInput').data('bootstrapValidator').resetForm();
		
		ModulSlide.find("div#form").removeClass("animated fadeInDown").hide();
		ModulSlide.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulSlide.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			
			judulSlide: {
				validators : {
					notEmpty : {
						message : 'Required - harus diket'
					}
				}
			}
		}
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	
		
		var options = {
			type		: "POST",
			dataType	: 'json',
			url			: "pages/M-Slide/model-slide.php",
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
				ModulSlide.find("input#idSlide").val('');
				ModulSlide.find("textarea#ket").val('');
				ModulSlide.find('form#formInput')[0].reset();
				ModulSlide.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				ModulSlide.find("div#form").removeClass("animated fadeInDown").hide();
				ModulSlide.find("div#list").addClass("animated fadeInDown").show();
			}
		};
		
		// ACTION UNTUK SUBMIT FORM
		ModulSlide.find('form#formInput').ajaxSubmit(options); 
			return false; 
		
	});
	
	// UNTUK UPLOAD FOTO OTOMATIS    
	ModulSlide.find('input#imgInput').change(function(){
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
				reset(ModulSlide.find('input#imgInput'));
				ModulSlide.find('label#statusFile').text("Tipe data harus berupa *.png,*.gif, dan *.jpeg");
				return false;
		}
      	    
		if(statusFtype){
			var fsize = this.files[0].size;
			var statusFsize = false;
      	    	
      	    	//Allowed file size is less than 1 MB (1242880)
			if(fsize>1242880) {
				reset(ModulSlide.find('input#imgInput'));
				ModulSlide.find('label#statusFile').text("Ukuran file harus di bawah 1 MB/1000 KB");
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
		            ModulSlide.find('img#displayFoto').attr('src', e.target.result);
		        }
		        reader.readAsDataURL(this.files[0]);

			ModulSlide.find('label#statusFile').text("Upload "+this.files[0].name+" / "+bytesToSize(this.files[0].size));  
		    }
	    }	
	});
	
	// UNTUK UPLOAD FOTO OTOMATIS SELESAI
	
});



function listSlide(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Slide/model-slide.php",
		data		: {action:'selectSlide' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulSlide = $("section#viewSlide");
	
	listSlide(function(output){
	
		var jqTabel = ModulSlide.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_slide' },
				{ data: 'judul' },
				{ data: 'keterangan' },				
				{ data: 'gambar' },
				{ data: 'status' }
				
			],
			"columnDefs": [ 
				{ 
					"vketble": true,  
					"targets": [ 0 ] 
				},
				{
					"targets": 1,
					"render": function ( data, type, row, meta ) {
						var ket = data;
						return ket;
					}
				},
				{
					"targets": 2,
					"render": function ( data, type, row, meta ) {
						var conten = row.keterangan.substring(0,200);
						var isi = conten;						
						return isi;
					}
				},	/**/
				{
					"targets": 5,
					"render": function ( data, type, row, meta ) {
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_slide+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_slide+")'><i class='fa fa-trash-o'></i></a> ";
						return edit + hapus;
					}
				}		
			]
		});
		
		
		ModulSlide.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':vketble' ).search( this.value ).draw();
		});
		
		
	});
}


function hapusData(idRow){
	
	var ModulSlide = $("section#viewSlide");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Slide/model-slide.php",
				data		: {action:'hapusSlide',id:idRow },
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
	var ModulSlide = $("section#viewSlide");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Slide/model-slide.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			ModulSlide.find('input#idSlide').val(json[0].id_slide);
			ModulSlide.find('input#judulSlide').val(json[0].judul);
			ModulSlide.find('textarea#ket').val(json[0].keterangan);
			ModulSlide.find('input[type="radio"].statusSlide#'+json[0].status).prop('checked',true);
			
					
		},
		complete	: function(){
			ModulSlide.find("div#form").addClass("animated fadeInDown").show();
			ModulSlide.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
