$(document).ready(function(){
	
	runAllForms();
	
	var Modul = $('section#viewLogin');
	
	Modul.find('button#btnLogin').click(function(){
		var isValid = Modul.find('#login-form').valid(); // Checking valid form
		
		if(isValid){
			var user = Modul.find('input#username').val();
			var pass = Modul.find('input#password').val();
			
			$.ajax({
				type	: "POST"
				,dataType: "json"
				,url		: "pages/W-Login/model-login.php"
				,data	: {username:user, password:pass}
				,beforeSend: function(){
				
				}
				,success	: function(json) {
					window.location="?page=dashboard";
				}
				,complete: function(){
				
				}
				
			});
		}
	});


	// Validation
	Modul.find("#login-form").validate({
		// Rules for form validation
		rules : {
			username : {
				required : true
			},
			password : {
				required : true
			}
		},

		// Messages for form validation
		messages : {
			username : {
				required : 'Field username harus diisi'
			},
			password : {
				required : 'Field password harus diisi'
			}
		},

		// Do not change code below
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		}
	});
		
});