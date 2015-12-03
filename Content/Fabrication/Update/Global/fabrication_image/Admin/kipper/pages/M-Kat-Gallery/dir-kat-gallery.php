<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Kat-Gallery/view-kat-gallery.php";
		break;

	default:
		break;


}


?>