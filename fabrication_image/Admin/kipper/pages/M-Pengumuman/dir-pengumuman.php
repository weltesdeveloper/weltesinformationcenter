<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Pengumuman/view-pengumuman.php";
		break;

	default:
		break;


}


?>