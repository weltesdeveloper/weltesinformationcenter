$(document).ready(function(){

	var ModulMenuInfo = $("section#viewMenuInfo");
	listTabel();
	
	
	
	ModulMenuInfo.find("div#list").addClass("animated fadeInDown");

	ModulMenuInfo.find("button#btnAdd").click(function(){
		ModulMenuInfo.find("div#list").removeClass("animated fadeInDown").hide();
		ModulMenuInfo.find("div#form").addClass("animated fadeInDown").show();
	});
	
/*	ModulMenuInfo.find("button#btnCancel").click(function(){
		ModulMenuInfo.find("input#idMenuI").val('');
		ModulMenuInfo.find('form#formInput')[0].reset();
		ModulMenuInfo.find('form#formInput').data('bootstrapValidator').resetForm();
		
		ModulMenuInfo.find("div#form").removeClass("animated fadeInDown").hide();
		ModulMenuInfo.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulMenuInfo.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			
			judulmenu: {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			}
			url: {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			}/*,
			singkatan: {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			faksimile: {
				validators : {
					digits : {
						message : 'Digits - data harus berupa angka'
					}
				}
			},
			telpFak: {
				validators : {
					digits : {
						message : 'Digits - data harus berupa angka'
					}
				}
			},
			emailFak: {
				validators : {
					emailAddress : {
						message : 'Email - format harus berupa email: example@email.com'
					}
				}
			},
			situsFak: {
				validators : {
					url : {
						message : 'URL - format harus berupa url: http://www.url.com'
					}
				}
			}*/
		}
	}).on('success.form.bv', function(e) {
		e.preventDefault();
	
		var $form = $(e.target);
		
		var dataSerialize = "action=commit&";
		dataSerialize += $form.serialize();
		
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "pages/M-Menu/model-menu-info.php",
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
				ModulMenuInfo.find("input#idMenuI").val('');
				ModulMenuInfo.find('form#formInput')[0].reset();
				ModulMenuInfo.find('form#formInput').data('bootstrapValidator').resetForm();
				
				listTabel();
				ModulMenuInfo.find("div#form").removeClass("animated fadeInDown").hide();
				ModulMenuInfo.find("div#list").addClass("animated fadeInDown").show();
			}
		});
		
	});
	
});



function listMenuInfo(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Menu/model-menu-info.php",
		data		: {action:'selectMenuI' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulMenuInfo = $("section#viewMenuInfo");
	
	listMenuInfo(function(output){
	
		var jqTabel = ModulMenuInfo.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_menu' },
				{ data: 'parent_id' },
				{ data: 'judul' },
				{ data: 'url' },
				{ data: 'letak' }
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
					"targets": 6,
					"render": function ( data, type, row, meta ) {
						var edit = "<a title='Edit Data' class='btn btn-xs btn-success' ";
						edit += "onclick='editData("+row.id_menu+")'><i class='fa fa-edit'></i></a> ";
						  
						var hapus = "<a title='Hapus Data' class='btn btn-xs btn-danger' ";
						hapus += "onclick='hapusData("+row.id_menu+")'><i class='fa fa-trash-o'></i></a> ";
						return edit + hapus;
					}
				}		
			]
		});
		
		
		ModulMenuInfo.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}


function hapusData(idRow){
	
	var ModulMenuInfo = $("section#viewMenuInfo");
	
	$.SmartMessageBox({
		title : "Hapus Data",
		content : "Anda yakin akan menghapus data ini?",
		buttons : '[Tidak][Ya]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Ya") {
			
			$.ajax({
				type		: "POST",
				dataType	: 'json',
				url			: "pages/M-Menu/model-menu-info.php",
				data		: {action:'hapusMenuI',id:idRow },
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
	var ModulMenuInfo = $("section#viewMenuInfo");

	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/M-Menu/model-menu-info.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
		
			ModulMenuInfo.find('input#idMenuI').val(json[0].id_berita);
			ModulMenuInfo.find('input#judulMenu').val(json[0].judul);
			ModulMenuInfo.find('input#url').val(json[0].url);
			ModulMenuInfo.find('select#pnt').val(json[0].parent_id);
		    ModulMenuInfo.find('select#urut').val(json[0].menu_order);
			ModulMenuInfo.find('select#ltk').val(json[0].letak);
					
		},
		complete	: function(){
			ModulMenuInfo.find("div#form").addClass("animated fadeInDown").show();
			ModulMenuInfo.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}
*/