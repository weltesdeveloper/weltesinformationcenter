<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Gallery/view-gallery.php";
		break;

	default:
		break;


}





?>