<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Video/view-video.php";
		break;

	default:
		break;


}


?>