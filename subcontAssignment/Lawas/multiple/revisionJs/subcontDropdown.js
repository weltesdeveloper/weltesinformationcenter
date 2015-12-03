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

function getComponent(projectName) {		
		
		var strURL="findComponent.php?project="+projectName;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById("revisionComp").innerHTML=req.responseText;
						// document.getElementById("baseplateDiv").innerHTML='<select name="baseplate" class="form-control">'+
						// '<option></option>'+'</select>';						
					} else {
						alert("Problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
        
function getHeadmark(projectName,compVal) {		
		var strURL="findHeadmark.php?project="+projectName+"&componentValue="+compVal;
		var req = getXMLHTTP();
		
		if (req) {
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById("revisionHeadmark").innerHTML=req.responseText;						
					} else {
						alert("Problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
        
        
function getElements(projectName,compVal,hmVal) {		
		var strURL="showUpdateableElements.php?project="+projectName+"&componentValue="+compVal+"&headmarkValue="+hmVal;
		var req = getXMLHTTP();
		
		// if (req) {
		// 	req.onreadystatechange = function() {
		// 		if (req.readyState == 4) {
		// 			// only if "OK"
		// 			if (req.status == 200) {						
		// 				document.getElementById("reviseableElements").innerHTML=req.responseText;						
		// 			} else {
		// 				alert("Problem while using XMLHTTP:\n" + req.statusText);
		// 			}
		// 		}				
		// 	}			
		// 	req.open("GET", strURL, true);
		// 	req.send(null);
		// }	

		$.get("showUpdateableElements.php",
			{
				project: projectName,
				componentValue: compVal,
				headmarkValue: hmVal
			},
			function(res){
				$("#reviseableElements").html(res);
			}
		);	
	}
        
