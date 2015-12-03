<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Berita/view-berita.php";
		break;

	default:
		break;


}


?>