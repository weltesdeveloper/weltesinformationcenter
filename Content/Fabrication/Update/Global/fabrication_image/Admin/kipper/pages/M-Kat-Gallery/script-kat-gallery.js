$(document).ready(function(){

	var Modul = $("section#view_kat_gallery");
	listTabel();
		
	Modul.find("div#list").addClass("animated fadeInDown");

	Modul.find("button#btnAdd").click(function(){
		Modul.find("div#list").removeClass("animated fadeInDown").hide();
		Modul.find("div#form").addClass("animated fadeInDown").show();
	});
	
	Modul.find("button#btnCancel").click(function(){
		Modul.find("input#id").val('');
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
			nama_kat: {
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
			url			: "pages/M-Kat-Gallery/model-kat-gallery.php",
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
				Modul.find("input#id").val('');
				Modul.find('form#formInput')[0].reset();
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
	
});


function list(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Kat-Gallery/model-kat-gallery.php",
		data		: {action:'select' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var Modul = $("section#view_kat_gallery");
	
	list(function(output){
	
		var jqTabel = Modul.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_kat_gallery' },
				{ data: 'nama_kat_gallery' },
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
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_kat_gallery+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_kat_gallery+")'><i class='fa fa-trash-o'></i></a> ";
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


function hapusData(idRow){
	
	var Modul = $("section#view_kat_gallery");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Kat-Gallery/model-kat-gallery.php",
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
	var Modul = $("section#view_kat_gallery");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Kat-Gallery/model-kat-gallery.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			Modul.find('input#id').val(json[0].id_kat_gallery);
			Modul.find('input#nama_kat').val(json[0].nama_kat_gallery);
			
					
		},
		complete	: function(){
			
			Modul.find("div#form").addClass("animated fadeInDown").show();
			Modul.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
