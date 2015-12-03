<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Informasi/view-informasi.php";
		break;

	default:
		break;


}


?>