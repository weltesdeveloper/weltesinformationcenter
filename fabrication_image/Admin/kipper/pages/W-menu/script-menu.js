
$(document).ready(function(){

	var ModulMenu = $("section#viewMenu");
		
	ModulMenu.find("div#list").addClass("animated fadeInDown");
	
	var jqTabel;
	listTabel();
		
	ModulMenu.find("button#btnAdd").click(function(){
		ModulMenu.find('input#idMenu').val('');
		ModulMenu.find('form#formInput').data('bootstrapValidator').resetForm();
		ModulMenu.find("div#list").removeClass("animated fadeInDown").hide();
		ModulMenu.find("div#form").addClass("animated fadeInDown").show();
	});
	
	
	ModulMenu.find("button#btnCancel").click(function(){
		ModulMenu.find('form#formInput').data('bootstrapValidator').resetForm();
		ModulMenu.find("div#form").removeClass("animated fadeInDown").hide();
		ModulMenu.find("div#list").addClass("animated fadeInDown").show();
	});
	
	
	ModulMenu.find("select#subLevel").change(function(){
		var subLevel = $(this).val();
	
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "pages/W-menu/model-menu.php",
			data		: {action:'selectParent', subLevel:subLevel},
			success: function (json) {
				if(json == false){
					ModulMenu.find("select#menuParent").empty();
					var isiOption = "<option value='0'>Menu Parent</option>";
					ModulMenu.find("select#menuParent").append(isiOption);
				}
				else{
				
					ModulMenu.find("select#menuParent").empty();
					var isiOption = "<option value=''>- Pilih Menu Parent -</option>";
					$.each(json, function(index, row) {											
						isiOption += "<option value='"+row.id_menu+"'>"+row.nama_menu+"</option>";
					});
					ModulMenu.find("select#menuParent").append(isiOption);
					
				}

			},
			complete	: function(){
				
			}
		});
	});
	
	
	
	ModulMenu.find('form#formInput').on('success.form.bv', function(e) {
		// Prevent form submission
		e.preventDefault();
		// Get the form instance
		var $form = $(e.target);
		// Get the BootstrapValidator instance
		//var bv = $form.data('bootstrapValidator');	
		
		var dataSerialize = "action=commit&";
		dataSerialize += $form.serialize();
		
		alert(dataSerialize);
		
		$.ajax({
			type		: "POST",
			dataType	: 'json',
			url			: "pages/W-menu/model-menu.php",
			data		: dataSerialize,
			success: function (json) {
				alert(json);
			},
			complete	: function(){
				window.location="index.php?page=menu";
				/*
				listTabel();
				ModulMenu.find("div#form").removeClass("animated fadeInDown").hide();
				ModulMenu.find("div#list").addClass("animated fadeInDown").show();*/
			}
		});
		
	});

	
	ModulMenu.find('form#formInput').bootstrapValidator({
		feedbackIcons : {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating : 'glyphicon glyphicon-refresh'
		},
		fields : {
			subLevel : {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			namaMenu : {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			url : {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			letakMenu : {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					}
				}
			},
			urutanMenu : {
				validators : {
					notEmpty : {
						message : 'Required - harus diisi'
					},
					digits : {
						message : 'Hanya bisa diisi oleh angka'
					}
				}
			}
		}
	});
	
	
	
	
	
	
	
});

function listMenu(handleData){
	return $.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/W-menu/model-menu.php",
		data		: {action:'selectMenu' },
		success		: function(json){
			handleData(json);
		}
	});	
}

function listTabel(){
	var ModulMenu = $("section#viewMenu");
	
	listMenu(function(output){
	
		jqTabel = ModulMenu.find('table#tabelData').DataTable({
			"bDestroy" : true,
			paging: true,
			searching: true,
			ordering: true,
			data: output,
			columns: [
				{ data: 'id_menu' },
				{ data: 'sub_level' },
				{ data: 'nama_menu' },
				{ data: 'url' },
				{ data: 'letak_menu' },
				{ data: 'urutan_menu' }
			],
			"columnDefs": [ 
				{ 
					"visible": true,  
					"targets": [ 0 ] 
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
		
		
		ModulMenu.find("table#tabelData thead th.textSearch input[type=text]").on( 'keyup change', function () {	
			jqTabel.column( $(this).parent().index()+':visible' ).search( this.value ).draw();
		});
		
		
	});
}



function editData(idRow){
	alert(idRow);
	
	var ModulMenu = $("section#viewMenu");
	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/W-menu/model-menu.php",
		data		: {action:'selectEdit', id:idRow },
		success: function (json) {
			alert(json);
			
			ModulMenu.find('input#idMenu').val(json[0].id_menu);
			ModulMenu.find('select#subLevel').val(json[0].sub_level);
			ModulMenu.find('select#menuParent').val(json[0].id_parent);
			ModulMenu.find('input#namaMenu').val(json[0].nama_menu);
			ModulMenu.find('input#url').val(json[0].url);
			ModulMenu.find('input#icon').val(json[0].icon);
			ModulMenu.find('input[type="radio"]#'+json[0].letak_menu).prop('checked',true);
			ModulMenu.find('input#urutanMenu').val(json[0].urutan_menu);	
		},
		complete	: function(){
			listTabel();
			ModulMenu.find("div#form").addClass("animated fadeInDown").show();
			ModulMenu.find("div#list").removeClass("animated fadeInDown").hide();
		}
	});
}

function hapusData(idRow){
	alert("Menghapus id: "+idRow);
		
	$.ajax({
		type		: "POST",
		dataType	: 'json',
		url			: "pages/W-menu/model-menu.php",
		data		: {action:'delete', id:idRow },
		success: function (json) {
			alert(json);
		},
		complete	: function(){
			listTabel();
		}
	});
}



	