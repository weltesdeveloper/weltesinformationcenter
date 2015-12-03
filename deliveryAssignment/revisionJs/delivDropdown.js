function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
function getDONumber(projNo) {
		// var strURL="ShowDONumberElement.php?projNo="+projNo;
		// var req = getXMLHTTP();
		
		// if (req) {
		// 	req.onreadystatechange = function() {
		// 		if (req.readyState == 4) {
		// 			// only if "OK"
		// 			if (req.status == 200) {						
		// 				document.getElementById("DONumberElement").innerHTML=req.responseText;						
		// 			} else {
		// 				alert("Problem while using XMLHTTP:\n" + req.statusText);
		// 			}
		// 		}				
		// 	}			
		// 	req.open("GET", strURL, true);
		// 	req.send(null);
		// }	

		$.get("ShowDONumberElement.php",{
				projNo: projNo
			},
			function(res){
				$("#DONumberElement").html(res);
			}
		);
}
 
function getElements(coliNumber) {		
		var strURL="showUpdateableElements.php?coliNo="+coliNumber;
		var req = getXMLHTTP();
		
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById("deliveryAssignmentElements").innerHTML=req.responseText;						
					} else {
						alert("Problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
       
function checkSttus(ChkParent,ChkChild) {
                // body...
                var ele             = $("input[name='"+ChkParent+"']:checkbox");
                var numberOfChecked = $("input[name='"+ChkChild+"']:checkbox:checked").length;
                var totalCheckboxes = $("input[name='"+ChkChild+"']:checkbox").length;
                var numberNotChecked = totalCheckboxes - numberOfChecked;

                if (numberOfChecked == 0 ) {
                    ele.prop('checked',false);
                } else{
                    ele.prop('checked',true);
                };
            }
        
function checkAll(ChkParent,ChkChild) {
                 var checkboxes = $("input[name='"+ChkChild+"']:checkbox");
                 var ele        = $("input[name='"+ChkParent+"']:checkbox");

                 if (ele.is(':checked')== true) {
                 	checkboxes.prop('checked',true);
                     // for (var i = 0; i < checkboxes.length; i++) {
                     //     // if (checkboxes[i].type == 'checkbox') {
                     //         checkboxes[i].prop('checked',true);
                     //     // }
                     // }
                 } else {
                 	checkboxes.prop('checked',false);
                     // for (var i = 0; i < checkboxes.length; i++) {
                     //     // console.log(i)
                     //     // if (checkboxes[i].type == 'checkbox') {
                     //         checkboxes[i].prop('checked',false);
                     //     // }
                     // }
                 }
            } 
