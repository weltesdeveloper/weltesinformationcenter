$(document).ready(function(){

	var ModulPengumuman = $("section#viewPengumuman");
	listTabel();
	
	ModulPengumuman.find("textarea#isi").ckeditor();
	
	ModulPengumuman.find("div#list").addClass("animated fadeInDown");

	ModulPengumuman.find("button#btnAdd").click(function(){
		ModulPengumuman.find("div#list").removeClass("animated fadeInDown").hide();
		ModulPengumuman.find("div#form").addClass("animated fadeInDown").show();
	});
	
	ModulPengumuman.find("button#btnCancel").click(function(){
		ModulPengumuman.find("input#idPengumuman").val('');
		ModulPengumuman.find("textarea#isi").val('');
		ModulPengumuman.find('form#formInput')[0].reset();
		ModulPengumuman.find('form#formInput').data('bootstrapValidator').resetForm();
		
		ModulPengumuman.find("div#form").removeClass("animated fadeInDown").hide();
		ModulPengumuman.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulPengumuman.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			
			judulPeng: {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			}
		}
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	
		var $form = $(e.target);
		
		var dataSerialize = "action=commit&";
		dataSerialize += $form.serialize();
		
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "pages/M-Pengumuman/model-pengumuman.php",
			data		: dataSerialize,
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
				ModulPengumuman.find("input#idPengumuman").val('');
				ModulPengumuman.find("textarea#isi").val('');
				ModulPengumuman.find('form#formInput')[0].reset();
				ModulPengumuman.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				ModulPengumuman.find("div#form").removeClass("animated fadeInDown").hide();
				ModulPengumuman.find("div#list").addClass("animated fadeInDown").show();
			}
		});
		
	});
	
});



function listPengumuman(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Pengumuman/model-pengumuman.php",
		data		: {action:'selectPengumuman' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulPengumuman = $("section#viewPengumuman");
	
	listPengumuman(function(output){
	
		var jqTabel = ModulPengumuman.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_pengumuman' },
				{ data: 'judul' },
				{ data: 'isi' },
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
						edit += "onclick='editData("+row.id_pengumuman+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_pengumuman+")'><i class='fa fa-trash-o'></i></a> ";
						return edit + hapus;
					}
				}		
			]
		});
		
		
		ModulPengumuman.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}


function hapusData(idRow){
	
	var ModulPengumuman = $("section#viewPengumuman");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Pengumuman/model-pengumuman.php",
				data		: {action:'hapusPengumuman',id:idRow },
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
	var ModulPengumuman = $("section#viewPengumuman");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Pengumuman/model-pengumuman.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			ModulPengumuman.find('input#idPengumuman').val(json[0].id_pengumuman);
			ModulPengumuman.find('input#judulPeng').val(json[0].judul);
			ModulPengumuman.find('textarea#isi').val(json[0].isi);
			
					
		},
		complete	: function(){
			
			ModulPengumuman.find("div#form").addClass("animated fadeInDown").show();
			ModulPengumuman.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
