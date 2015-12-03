<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Slide/view-slide.php";
		break;

	default:
		break;


}


?>